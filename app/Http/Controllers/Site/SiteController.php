<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Http\Resources\ProductJsonAllResource;

class SiteController extends Controller
{
    public function index()
    {
        return view('site.index');
    }

    public function policy()
    {
        return view('site.policy');
    }

    public function terms()
    {
        return view('site.terms');
    }

    public function jsonAll()
    {
        $products = Product::with(['categories', 'notes', 'notes2', 'notes3', 'productVariants'])->get();

        return  ProductJsonAllResource::collection($products);
    }

    /*public function import()
    {
    }*/
}
