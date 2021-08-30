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
use App\Http\Middleware\VerifyCsrfToken;
use App\Modules\Fops\Core\Http\Controllers\Admin\FopController;
use App\Modules\Advs\Core\Http\Controllers\Admin\AdvController;
use App\Modules\Operators\Core\Http\Controllers\Admin\OperatorController;
use App\Modules\Clients\Core\Http\Controllers\Admin\ClientsController;
// вход в админку
Route::get('/', function () {
    return view('admin.admin');
})->middleware(['auth'])->name('site.index');

Route::get('/policy', [SiteController::class, 'policy'])->name('site.policy');
Route::get('/terms', [SiteController::class, 'terms'])->name('site.terms');
Route::get('/page/json_all', [SiteController::class, 'jsonAll']);
//Route::get('/import',[SiteController::class, 'import']);

//отдаем json данные
Route::get('/auction/getNotes', [AuctionController::class, 'getNotes']);
Route::get('/auction/getBrand', [AuctionController::class, 'getBrand']);
Route::get('/auction/getAroma', [AuctionController::class, 'getAroma']);
Route::get('/auction/getManufacturer', [AuctionController::class, 'getManufacturer']);
Route::get('/auction/getFamily', [AuctionController::class, 'getFamily']);
Route::get('/auction/getProduct', [AuctionController::class, 'getProduct']);
//Route::get('/auction/import', [AuctionController::class, 'import']);

//->withoutMiddleware() need for we can use this POST route without csrf token (from Yii2 for example)
//вижу повторы пробовал group, но withoutMiddle() на группе кидает ошибку и работает только для 1-го роута
Route::post('/auction/register', [AuctionController::class, 'register'])
    ->withoutMiddleware([VerifyCsrfToken::class]);
Route::post('/auction/registerConfirm', [AuctionController::class, 'registerConfirm'])
    ->withoutMiddleware([VerifyCsrfToken::class]);
Route::post('/auction/login', [AuctionController::class, 'login'])
    ->withoutMiddleware([VerifyCsrfToken::class]);
Route::post('/auction/changePass', [AuctionController::class, 'changePass'])
    ->withoutMiddleware([VerifyCsrfToken::class]);
Route::post('/auction/changePhone', [AuctionController::class, 'changePhone'])
    ->withoutMiddleware([VerifyCsrfToken::class]);
Route::post('/auction/sendCart', [AuctionController::class, 'sendCart'])
    ->withoutMiddleware([VerifyCsrfToken::class]);
Route::post('/auction/setDiscount', [AuctionController::class, 'setDiscount'])
    ->withoutMiddleware([VerifyCsrfToken::class]);
Route::post('/auction/checkSum', [AuctionController::class, 'checkSum'])
    ->withoutMiddleware([VerifyCsrfToken::class]);
Route::post('/auction/addComment', [AuctionController::class, 'addComment'])
    ->withoutMiddleware([VerifyCsrfToken::class]);
Route::post('/auction/addClientPaymentRequest', [AuctionController::class, 'addClientPaymentRequest'])
    ->withoutMiddleware([VerifyCsrfToken::class]);

Route::get('/auction/getOrders', [AuctionController::class, 'getOrders']);
Route::get('/auction/getPayStatusList',  [AuctionController::class, 'getPayStatusList']);
Route::get('/auction/getOrderStatusList',  [AuctionController::class, 'getOrderStatusList']);
Route::get('/auction/getClientBalance', [AuctionController::class, 'getClientBalance']);

Route::get('/xml/prom.xml', [AggregatorController::class, 'promUa']);
Route::get('/xml/google-local', [AggregatorController::class, 'googleLocal']);
Route::get('/xml/google-original', [AggregatorController::class, 'googleOriginal']);


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
    Route::resource('fop', FopController::class)->except(['show']);
    Route::resource('adv', AdvController::class)->except(['show']);
    Route::resource('operator', OperatorController::class)->except(['show']);
    Route::resource('client', ClientsController::class)->except(['show']);

});
