<?php

namespace App\Modules\Aggregators\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class AggregatorController extends Controller
{
    public function promUa()
    {
        //only categories 1 и 2 ( man & woman parfumes) (? it can be edited ...)
        //$categories = Category::whereIn('id', [1,2])->get();
        $categories = [
            0 => ['id' => 1, 'name' => 'Женская парфюмерия 50мл'],
            1 => ['id' => 2, 'name' => 'Женская парфюмерия 100мл'],
            2 => ['id' => 3, 'name' => 'Мужская парфюмерия 50мл'],
            3 => ['id' => 4, 'name' => 'Мужская парфюмерия 100мл'],
        ];

        //all data for create prom.ua xml-file; here only Man & Woman parfume volume 50 or 100 ml
        $prods = DB::table('product_variants')
            ->leftJoin('products', 'product_variants.product_id', '=', 'products.id')
            ->leftJoin('category_product', 'products.id', '=', 'category_product.product_id')
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->select(
                'products.id', 'products.vendor', 'products.name as name', 'products.description', 'products.img2',
                'product_variants.name as variant', 'product_variants.volume as volume','product_variants.active_ua',
                'product_variants.price_ua', 'category_product.category_id as category_id', 'brands.name as brand'
            )
            ->whereIn('product_variants.volume', [50.00, 100.00])
            ->where('product_variants.active_ua', '=', 1)
            ->whereIn('category_product.category_id', [1,2])
            ->orderBy('category_id')
            ->orderBy('volume')
            ->get();
        //dd($categories, $prods);

        $products =[];
        foreach ($prods as $key => $product) {
            $noteStr = $this->getProductNotes($product->id);
            $products[$key] = $product;
            $products[$key]->notes = $noteStr;
        }
        //dd($products);

        return response()->view('aggregators.prom_ua_xml', [
            'products' => $products,
            'categories' => $categories
        ])->header('Content-Type', 'text/xml');
    }

    private function getProductNotes(int $productId)
    {
        $product = Product::where('id', $productId)->first();
        $noteStr = '';
        if ($product->notes) {
            foreach ($product->notes as $note) {
                $noteStr .= ', ' . $note->name_ru;
            }
        }
        return $noteStr;
    }
}
