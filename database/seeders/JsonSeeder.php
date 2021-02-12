<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Brand;
use App\Models\Note;
use App\Models\Category;

class JsonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $jsonData = file_get_contents('https://parfumdeparis.biz/page/json_all');
        $data = json_decode($jsonData, true);
        //dd($data);

        $products = [];
        $brands = [];
        $notes = [];
        $noteProduct = [];
        $variants = [];
        $categories = [];
        $strNotes = '';

        foreach ($data as $key => $item){

            $products[$key]['sort'] = $item['sort'];
            $products[$key]['name'] = $item['name'];
            $products[$key]['description'] = $item['text'];
            $products[$key]['vendor'] = 'PdParis';
            $products[$key]['img'] = '/images/product/'.$item['img'];
            $products[$key]['hide'] = 0;
            $products[$key]['bname'] = $item['bname'];
            $products[$key]['filters'] = $item['filters'];

            $brands[] = $item['bname'];
            $strNotes .= ','.str_replace('.','',$item['filters']);
            $notes =  array_map( function ($item) {return trim($item);} , array_unique(explode(',', $strNotes)));

            $noteProduct[] = explode(', ',$item['filters']);

            // варианты
            $variants[$key]['man'] = $item['man'];
            $variants[$key]['woman'] = $item['woman'];

            $variants[$key]['active_ua'] = $item['active_ua'];
            $variants[$key]['active_ru'] = $item['active_ru'];

            $variants[$key]['active25ru'] = $item['active25ru'];
            $variants[$key]['active50ru'] = $item['active50ru'];
            $variants[$key]['active100ru'] = $item['active100ru'];

            $variants[$key]['price25'] = $item['price25'];
            $variants[$key]['price50'] = $item['price50'];
            $variants[$key]['price100'] = $item['price100'];

            $variants[$key]['price50ru'] = $item['price50ru'];
            $variants[$key]['price100ru'] = $item['price100ru'];

            $variants[$key]['art25'] = $item['art25'];
            $variants[$key]['art50'] = $item['art50'];
            $variants[$key]['art100'] = $item['art100'];

            // категории (если есть категория - ставлю id этой категории из нашего справ. категорий)
            // те женские парф. у нас идут с id=1, мужские id = 2 ...
            $categories[$key]['woman'] = ($item['woman'] == '1') ? 1 : 0;
            $categories[$key]['man'] = ($item['man'] == '1') ? 2 : 0;
            $categories[$key]['antiseptics'] = ($item['antiseptics'] == '1') ? 3 : 0;
            $categories[$key]['auto'] = ($item['auto'] == '1') ? 4 : 0;
            $categories[$key]['hair_spray'] = ($item['hair_spray'] == '1') ? 5 : 0;
            $categories[$key]['woman500'] = ($item['woman500'] == '1') ? 6: 0;
            $categories[$key]['man500'] = ($item['man500'] == '1') ? 7 : 0;
        }

        //dd($products, array_unique($notes), $variants);

        // {OK} заполнение нот и брендов должно отработать 1 раз
        $allNotes = array_unique($notes);
        foreach ($allNotes as $note) {
            if (!empty($note)) {
                Note::firstOrCreate(['name_ru'=> $note], ['name_ua' => '']);
            }
        }
        // {OK} (но можно гонять по кругу - дублей не делает)
        foreach (array_unique($brands) as $key => $brand) {
            Brand::firstOrCreate(['name' => $brand]);
        }


        // {} цикл по созданию продукта + атач его нот + insert его вариантов
        foreach ($products as $key => $product) {

            $brandId = Brand::where('name', $product['bname'])->first()->id;
            $aromaId = 1;//его пока нет ?
            $newProduct = Product::create([
                'aroma_id' => $aromaId,
                'brand_id' => $brandId ?? 1,
                'vendor' => $product['vendor'],
                'name' => $product['name'],
                'description' => $product['description'],
                'sort' => $product['sort'],
                'img' => $product['img'],
                'hide' => $product['hide'],
            ]);

            // {OK}
            if (is_array($noteProduct[$key])) {
                $attach = [];
                foreach ($noteProduct[$key] as $notes) {
                    $noteId = Note::where('name_ru', $notes)->first();
                    if (!is_null($noteId)) {
                        $attach[] = $noteId->id;
                    }
                }
                $newProduct->notes()->attach($attach);
            }

            // приаттачивание категорий
            if (is_array($categories[$key])) {
                $attachCats = [];
                foreach ($categories[$key] as $cats) {
                    $cat = Category::where('id', $cats)->first();
                    if (!is_null($cat)) {
                        $attachCats[] = $cat->id;
                    }
                }
                $newProduct->categories()->attach($attachCats);
            }

            //dd($variants[$key]);
            //{OK} (вроде отрабатыв без ошибок, но содержимое ?)
            $newVariant=[];
            if (is_array($variants[$key]) ) {

                if ($variants[$key]['man'] == '1' || $variants[$key]['woman'] =='1' ) {
                    //--------------------------------
                    $newVariant[0]['product_id'] = $newProduct->id;
                    $newVariant[0]['name']= '25ml';
                    $newVariant[0]['volume']= 25;
                    $newVariant[0]['art'] = $variants[$key]['art25'];

                    $newVariant[0]['price_ua'] = (int) $variants[$key]['price25'] ?? 0;
                    $newVariant[0]['price_ru'] = 0; //(int) $variants[$key]['price25ru'];// ???

                    $newVariant[0]['active_ua'] = (int) $variants[$key]['active_ua'] ?? 0;
                    $newVariant[0]['active_ru'] = (int) $variants[$key]['active25ru'] ?? 0;
                    //--------------------------------
                    $newVariant[1]['product_id'] = $newProduct->id;
                    $newVariant[1]['name']= '50ml';
                    $newVariant[1]['volume']= 50;
                    $newVariant[1]['art'] = $variants[$key]['art50'];

                    $newVariant[1]['price_ua'] = (int) $variants[$key]['price50'] ?? 0;
                    $newVariant[1]['price_ru'] = (int) $variants[$key]['price50ru'] ?? 0;

                    $newVariant[1]['active_ua'] = (int) $variants[$key]['active_ua'] ?? 0;
                    $newVariant[1]['active_ru'] = (int) $variants[$key]['active50ru'] ?? 0;
                    //--------------------------------
                    $newVariant[2]['product_id'] = $newProduct->id;
                    $newVariant[2]['name']= '100ml';
                    $newVariant[2]['volume']= 100;
                    $newVariant[2]['art'] = $variants[$key]['art100'];

                    $newVariant[2]['price_ua'] = (int) $variants[$key]['price100'] ?? 0;
                    $newVariant[2]['price_ru'] = (int) $variants[$key]['price100ru'] ?? 0;

                    $newVariant[2]['active_ua'] = (int) $variants[$key]['active_ua'] ?? 0;
                    $newVariant[2]['active_ru'] = (int) $variants[$key]['active100ru'] ?? 0;
                    //--------------------------------
                }

            }

            //dd($newVariant);
            ProductVariant::insert($newVariant);// такая вставка, чтоб за раз вставить неск строк

        }

    }
}
