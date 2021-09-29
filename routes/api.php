<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Modules\Novaposhta\Core\Http\Controllers\Api\NovaPoshtaController;
use App\Modules\Sdek\Core\Http\Controllers\Api\SdekController;
use App\Modules\Postru\Core\Http\Controllers\Api\PostruController;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('products', [ProductController::class, 'index']);
Route::get('products/{product}', [ProductController::class, 'show']);

Route::get('/novaposhta/cities', [NovaPoshtaController::class, 'cities']);
Route::get('/novaposhta/offices', [NovaPoshtaController::class, 'offices']);

Route::get('/sdek/offices', [SdekController::class, 'offices']);
Route::get('/postru/office', [PostruController::class, 'office']);
Route::get('/postru/offices', [PostruController::class, 'offices']);
Route::get('/postru/address', [PostruController::class, 'address']);

Route::post('/postru/create-order', [PostruController::class, 'createOrder']);
Route::delete('/postru/delete-order/{ids}', [PostruController::class, 'deleteOrders']);

Route::post('/postru/create-batch', [PostruController::class, 'createBatch']);
Route::get('/postru/get-all-batches', [PostruController::class, 'getAllBatches']);
Route::get('/postru/get-orders-batches/{batch}', [PostruController::class, 'getOrdersInBatch']);
Route::delete('/postru/delete-orders-batch/{ids}', [PostruController::class, 'deleteOrdersInBatch']);

Route::post('/postru/register', [PostruController::class, 'createOrUpdateRegister']);
Route::get('/postru/print-pdf-forms/{id}', [PostruController::class, 'printPdfForms']);
Route::get('/postru/print-f103/{batch}', [PostruController::class, 'printF103']);
