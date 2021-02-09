<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\AromaController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Site\SiteController;

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', [SiteController::class, 'index'])->name('site.index');
Route::get('/policy', [SiteController::class, 'policy'])->name('site.policy');
Route::get('/terms', [SiteController::class, 'terms'])->name('site.terms');

Auth::routes(['register' => false]); //Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// пока простой вход в админку (потом закроем middleware)
Route::get('/home', function () {
    return view('admin.admin');
})->middleware(['auth']);

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    Route::resource('user', UserController::class)->except(['show']);
    Route::resource('aroma', AromaController::class)->except(['show']);
    Route::resource('brand', BrandController::class)->except(['show']);
    Route::resource('category', CategoryController::class)->except(['show']);
    Route::resource('product', ProductController::class)->except(['show']);
    Route::resource('settings', SettingController::class)->only(['edit','update']);

    Route::delete('/delete_product_image/{image}', [ProductController::class, 'deleteProductImage']);

});
