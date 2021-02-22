<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
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

        if ($id) {
            $data = Product::where('id', $id)->get(['id', 'vendor'])->toArray();
        } else {
            $data = Product::all(['id', 'vendor'])->toArray();
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
                    'ru' => [$item['name']],
                    'ua' => [$item['name_ua']],
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
            $data = Brand::where('id', $id)->get(['id', 'name'])->toArray();
        } else {
            $data = Brand::all(['id', 'name'])->toArray();
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
                    'ru' => [$item['name']],
                    'ua' => [$item['name_ua']],
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
                    'ru' => [$item['name_ru']],
                    'ua' => [$item['name_ua']],
                ]
            ];
        }

        return response()->json($data);
    }

    public function getProduct(Request $request)
    {
        if ($request->get('key') !== self::API_KEY) abort(404);

        $id = $request->get('id');

        if ($id) {
            $dat = Product::with(['categories', 'notes'])->where('id', $id)->get([
                'id', 'name', 'description', 'description_ua', 'img', 'img2', 'img3', 'aroma_id', 'brand_id'
            ])->toArray();
            //dd($dat);
        } else {
            $dat = Product::with(['categories', 'notes'])->get([
                'id', 'name', 'description', 'description_ua', 'img', 'img2', 'img3', 'aroma_id', 'brand_id'
            ])->toArray();
            //dd($dat);
        }

        $data = [];
        foreach ($dat as $item) {
            $data[$item['id']] = [
                [
                    'ru' => [
                        'name' => $item['name'],
                        'description' => $item['description'],
                    ],

                    'ua' => [
                        'name' => $item['name'],
                        'description_ua' => $item['description_ua'],
                    ],
                    'images' => [
                        0 => $item['img'],
                        1 => $item['img2'],
                        2 => $item['img3'],
                    ],
                    'aroma_id' => $item['aroma_id'],
                    'brand_id' => $item['brand_id'],
                    'notes' =>  implode(', ', array_map( function ($note) {return $note['name_ru'];}, $item['notes'])),
                    'categories' =>  implode(', ', array_map( function ($category) {return $category['name'];}, $item['categories'])),
                ]
            ];
        }
        return response()->json($data);
    }
}
