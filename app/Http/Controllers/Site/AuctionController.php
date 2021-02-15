<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Note;

class AuctionController extends Controller
{
    const API_KEY = '12345678';

    public function getManufacturer(Request $request)
    {
        if ($request->get('key') !== self::API_KEY) abort(404);

        return response()->json(['success' => 'OK']);
    }

    public function getAroma()
    {

    }

    public function getBrand(Request $request)
    {
        if ($request->get('key') !== self::API_KEY) abort(404);

        return response()->json(['brands' => Brand::all(['id', 'name'])]);
    }

    public function getFamily()
    {

    }

    public function getNotes(Request $request)
    {
        if ($request->get('key') !== self::API_KEY) abort(404);

        return response()->json(['notes' => Note::all(['id', 'name_ru', 'name_ua'])]);
    }

    public function getProduct()
    {

    }
}
