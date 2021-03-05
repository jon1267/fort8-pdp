<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\AromaController;
use App\Modules\Brands\Core\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Site\SiteController;
use App\Http\Controllers\Site\AuctionController;
use App\Modules\Aggregators\Core\Http\Controllers\AggregatorController;

// вход в админку
Route::get('/', function () {
    return view('admin.admin');
})->middleware(['auth'])->name('site.index');

Route::get('/policy', [SiteController::class, 'policy'])->name('site.policy');
Route::get('/terms', [SiteController::class, 'terms'])->name('site.terms');
Route::get('/page/json_all', [SiteController::class, 'jsonAll']);
//Route::get('/import',[SiteController::class, 'import']);

//отдаем json данные
Route::get('/getNotes', [AuctionController::class, 'getNotes']);
Route::get('/getBrand', [AuctionController::class, 'getBrand']);
Route::get('/getAroma', [AuctionController::class, 'getAroma']);
Route::get('/getManufacturer', [AuctionController::class, 'getManufacturer']);
Route::get('/getFamily', [AuctionController::class, 'getFamily']);
Route::get('/getProduct', [AuctionController::class, 'getProduct']);

Route::get('/xml/prom.xml', [AggregatorController::class, 'promUa']);

Auth::routes(['register' => false]); //Auth::routes();

// вход в админку
Route::get('/admin', function () {
    return view('admin.admin');
})->middleware(['auth'])->name('admin.index');

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    Route::resource('user', UserController::class)->except(['show']);
    Route::resource('aroma', AromaController::class)->except(['show']);
    Route::resource('brand', BrandController::class)->except(['show']);
    Route::resource('category', CategoryController::class)->except(['show']);
    Route::resource('product', ProductController::class)->except(['show']);
    Route::resource('settings', SettingController::class)->only(['edit','update']);

});
