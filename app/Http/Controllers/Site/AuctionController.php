<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Aroma;
use App\Models\Brand;
use App\Models\Note;
use App\Models\Product;
use App\Models\Category;

class AuctionController extends Controller
{
    const API_KEY = '12345678';

    public function getManufacturer(Request $request)
    {
        if ($request->get('key') !== self::API_KEY) abort(404);

        $id = $request->get('id');

        // меняем подход: считаем у нас 1 vendor='PdParis'. отдаем только c id=1, и без id одна запись (тоже с id=1 :)
        if ($id) {
            $dat = ($id == 1) ? Product::where('id', $id)->get(['id', 'vendor'])->toArray() : [];
        } else {
            $dat = Product::all(['id', 'vendor'])->take(1)->toArray();
        }



        $data = [];
        foreach ($dat as $item) {
            $data[$item['id']] = [
                [
                    'ru' => ['name' => $item['vendor']],
                    'ua' => ['name' => $item['vendor']],
                ]
            ];
        }
        return response()->json($data);
    }

    public function getAroma(Request $request)
    {
        if ($request->get('key') !== self::API_KEY) abort(404);

        $id = $request->get('id');

        if ($id) {
            $dat = Category::where('id', $id)->get(['id', 'name', 'name_ua'])->toArray();
        } else {
            $dat = Category::all(['id', 'name', 'name_ua'])->toArray();
        }

        $data = [];
        foreach ($dat as $item) {
            $data[$item['id']] = [
                [
                    'ru' => ['name' => $item['name']],
                    'ua' => ['name' => $item['name_ua']],
                ]
            ];
        }

        return response()->json($data);
    }

    public function getBrand(Request $request)
    {
        if ($request->get('key') !== self::API_KEY) abort(404);

        $id = $request->get('id');

        if ($id) {
            $dat = Brand::where('id', $id)->get(['id', 'name'])->toArray();
        } else {
            $dat = Brand::all(['id', 'name'])->toArray();
        }

        $data = [];
        foreach ($dat as $item) {
            $data[$item['id']] = [
                [
                    'ru' => ['name' => $item['name']],
                    'ua' => ['name' => $item['name']],
                ]
            ];
        }

        return response()->json($data);
    }

    public function getFamily(Request $request)
    {
        if ($request->get('key') !== self::API_KEY) abort(404);

        $id = $request->get('id');

        if ($id) {
            $dat = Aroma::where('id', $id)->get(['id', 'name', 'name_ua'])->toArray();
        } else {
            $dat = Aroma::all(['id', 'name', 'name_ua'])->toArray();
        }

        $data = [];
        foreach ($dat as $item) {
            $data[$item['id']] = [
                [
                    'ru' => ['name' => $item['name']],
                    'ua' => ['name' => $item['name_ua']],
                ]
            ];
        }

        return response()->json($data);
    }

    public function getNotes(Request $request)
    {
        if ($request->get('key') !== self::API_KEY) abort(404);

        $id = $request->get('id');

        if ($id) {
            $dat = Note::where('id', $id)->get(['id', 'name_ru', 'name_ua'])->toArray();
        } else {
            $dat = Note::all(['id', 'name_ru', 'name_ua'])->toArray();
        }

        $data = [];
        foreach ($dat as $item) {
            $data[$item['id']] = [
                [
                    'ru' => ['name' => $item['name_ru']],
                    'ua' => ['name' => $item['name_ua']],
                ]
            ];
        }

        return response()->json($data);
    }

    public function getProduct(Request $request)
    {
        if ($request->get('key') !== self::API_KEY) abort(404);

        $id = $request->get('id');

        //это при дальнейшем усложнении переделать как в AggregatorController или сервис, по данным вариантов товара ?
        if ($id) {
            $dat = Product::with(['categories', 'notes', 'notes2', 'notes3', 'productVariants'])
                ->whereHas('productVariants', function ($query) {
                    $query->where([['active_ua', '=' , 1 ], ['volume', '=', 100.00] ]);
                })
                ->whereHas('categories', function ($query) {
                    $query->whereIn('categories.id', [1,2]);
                })
                ->where('id', $id)
                ->get([
                    'id', 'vendor', 'name', 'description', 'description_ua', 'img', 'img2', 'img3', 'aroma_id', 'brand_id'
                ])->toArray();
        } else {
            $dat = Product::with(['categories', 'notes', 'notes2', 'notes3', 'productVariants'])
                ->whereHas('productVariants', function ($query) {
                    $query->where([['active_ua', '=' , 1 ], ['volume', '=', 100.00] ]);
                })
                ->whereHas('categories', function ($query) {
                    $query->whereIn('categories.id', [1,2]);
                })
                ->get([
                    'id', 'vendor', 'name', 'description', 'description_ua', 'img', 'img2', 'img3', 'aroma_id', 'brand_id'
                ])->toArray();
        }
        //dd($dat);

        $data = [];
        foreach ($dat as $item) {
            if (is_array($item) && count($item)) {

                $price = array_map( function ($priceUa) {
                    return ($priceUa['active_ua'] == 1 && $priceUa['volume'] == 100) ? $priceUa['price_ua'] : 0;},
                    $item['product_variants']
                );

                $data[$item['id']] = [
                    [
                        'ru' => [
                            'name' => $item['name'],
                            'descr' => $item['description'],
                        ],

                        'ua' => [
                            'name' => $item['name'],
                            'descr' => $item['description_ua'],
                        ],
                        'images' => [
                            0 => $item['img'] ? url('/') . $item['img'] : null,
                            //1 => $item['img2'] ? url('/') . $item['img2'] : null,
                            //2 => $item['img3'] ? url('/') . $item['img3'] : null,
                        ],
                        'p_price' => max($price),
                        'p_priceD' => 0,
                        'count' => 100,
                        'volume' => 100,
                        'manuf_id' => 1, //$item['vendor'],
                        'aroma_id' => $item['aroma_id'],
                        'brand_id' => $item['brand_id'],
                        'note1' => array_map(function ($note) {return $note['id'];}, $item['notes']),
                        'note2' => array_map(function ($note2) {return $note2['id'];}, $item['notes2']),
                        'note3' => array_map(function ($note3) {return $note3['id'];}, $item['notes3']),
                        'categories' => array_map(function ($category) {return $category['id'];}, $item['categories']),
                        'family' => [],
                        //'notes' =>  implode(', ', array_map( function ($note) {return $note['name_ru'];}, $item['notes'])),
                        //'categories' =>  implode(', ', array_map( function ($category) {return $category['name'];}, $item['categories'])),
                    ]
                ];
            }
        }
        return response()->json($data);
    }
}
