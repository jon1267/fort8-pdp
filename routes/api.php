<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Modules\Novaposhta\Core\Http\Controllers\Api\NovaPoshtaController;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('products', [ProductController::class, 'index']);
Route::get('products/{product}', [ProductController::class, 'show']);

Route::get('/novaposhta/cities', [NovaPoshtaController::class, 'cities']);
Route::get('/novaposhta/offices', [NovaPoshtaController::class, 'offices']);
