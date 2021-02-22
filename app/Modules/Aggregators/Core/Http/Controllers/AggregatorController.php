<?php

namespace App\Modules\Aggregators\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class AggregatorController extends Controller
{
    public function promUa()
    {
        $products = Product::where('id', '<=', 10 )->get();
        $categories = Category::whereIn('id', [1,2])->get();

        return response()->view('aggregators.prom_ua_xml', [
            'products' => $products,
            'categories' => $categories
        ])->header('Content-Type', 'text/xml');
    }
}
