<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductStoreController;
use App\Http\Controllers\ProductSaleController;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PostControllers;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/verify-email/{token}', [UserController::class, 'verifyEmail']);

Route::middleware('auth:api')->group(function () {

    Route::put('/user/{id}', [UserController::class, 'update']);
    Route::post('/user/change-password/{id}', [UserController::class, 'changePassword']);
    Route::get('/order/history/{userId}', [OrderController::class, 'orderHistory']);
    Route::get('/orders/user/{id}', [OrderController::class, 'getOrdersByUser']);
    Route::put('/orders/{id}/cancel', [OrderController::class, 'cancel']);

});

Route::prefix('admin')
    ->middleware(['auth:api', 'admin'])
    ->group(function () {

    Route::apiResources([
        'banner' => BannerController::class,
        'brand' => BrandController::class,
        'category' => CategoryController::class,
        'contact' => ContactController::class,
        'menu' => MenuController::class,
        'product' => ProductController::class,
        'productstore' => ProductStoreController::class,
        'productsale' => ProductSaleController::class,
        'topic' => TopicController::class,
        'post' => PostController::class,
        'user' => UserController::class,
        'order' => OrderController::class,
        'setting' => SettingController::class,
        'attribute' => AttributeController::class,
    ]);

    Route::get('/me', function (Request $request) {
        return response()->json([
            'id' => $request->user()->id,
            'name' => $request->user()->name,
            'email' => $request->user()->email,
            'username' => $request->user()->username,
            'phone' => $request->user()->phone,
            'role' => $request->user()->roles, // hoặc role
        ]);
    });

    Route::post('/upload/summernote', [UploadController::class, 'summernote']);

    Route::get('/productsale/template', [ProductSaleController::class, 'exportTemplate']);
    Route::post('productsale/import', [ProductSaleController::class, 'import']);

    Route::put('contact/{id}/reply', [ContactController::class, 'reply']);
    Route::post('/menu/import', [MenuController::class, 'importFromSource']);

    Route::get('/product/{id}/attributes', [ProductController::class, 'getAttributes']);
    Route::post('/product/{id}/attributes', [ProductController::class, 'updateAttributes']);

    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/chart', [DashboardController::class, 'chart']);

    // Route::get('/me', function (Request $request) {
    //         return response()->json($request->user());
    //     });
});

Route::get('/product', [ProductController::class, 'index']);
Route::get('product-new', [ProductController::class,'product_new']);
Route::get('product-sale', [ProductController::class,'product_sale']);
Route::get('product-category/{catid}', [ProductController::class,'product_by_category']);
Route::get('product-all', [ProductController::class,'product_all']);
Route::get('/products/search', [ProductController::class, 'search']);
Route::get('/product/{id}', [ProductController::class, 'show']);

Route::get('/topic', [TopicController::class,'index']);
Route::get('/post', [PostController::class,'index']);
Route::get('/post/{id}', [PostController::class,'show']);
Route::get('post-new', [PostController::class,'post_new']);

Route::get('category/all', [CategoryController::class, 'all']);
Route::get('/category', [CategoryController::class, 'index']);
Route::get('/category/{id}', [CategoryController::class, 'show']);

Route::get('/banner', [BannerController::class, 'index']);
Route::get('/menu', [MenuController::class, 'index']);

Route::post('/logout', [UserController::class, 'logout'])
    ->middleware('auth:api');
    
    //Route::get('/productsale/{id}', [ProductSaleController::class, 'show']);

    //Route::get('/productsale/template', [ProductSaleController::class, 'exportTemplate']);
    //Route::post('productsale/import', [ProductSaleController::class, 'import']);
    //Route::apiResource('productsale', ProductSaleController::class)->except(['show']);

    // Route::get('/product/{id}/attributes', [ProductController::class, 'getAttributes']);
    // Route::post('/product/{id}/attributes', [ProductController::class, 'updateAttributes']);
    //Route::get('/product/{id}', [ProductController::class, 'show']);


    // Route::apiResources([
//     'banner' => BannerController::class,
//     'brand' => BrandController::class,
//     'category' => CategoryController::class,
//     'contact' => ContactController::class,
//     'menu' => MenuController::class,
//     'product' => ProductController::class,
//     'productstore' => ProductStoreController::class,
//     //'productsale' => ProductSaleController::class,
//     'topic' => TopicController::class,
//     'post' => PostController::class,
//     'user' => UserController::class,
//     'order' => OrderController::class,
//     'setting' => SettingController::class,
//     'attribute' => AttributeController::class,
// ]);


    //Route::get('product-new', [ProductController::class,'product_new']);
    //Route::get('product-sale', [ProductController::class,'product_sale']);
    //Route::get('product-category/{catid}', [ProductController::class,'product_by_category']);
    //Route::get('product-all', [ProductController::class,'product_all']);
    //Route::get('/products/search', [ProductController::class, 'search']);
    //Route::get('category/all', [CategoryController::class, 'all']);


    //Route::get('post-new', [PostController::class,'post_new']);

    //Route::post('/register', [UserController::class, 'store']);
    //Route::get('/verify-email/{token}', [UserController::class, 'verifyEmail']);
    //Route::post('/login', [UserController::class, 'login']);

    //Route::post('/user/change-password/{id}', [UserController::class, 'changePassword']);
    //Route::get('/order/history/{userId}', [OrderController::class, 'orderHistory']);
    //Route::get('/orders/user/{id}', [OrderController::class, 'getOrdersByUser']);

    //Route::put('contact/{id}/reply', [ContactController::class, 'reply']);
    //Route::post('/menu/import', [MenuController::class, 'importFromSource']);

    //Route::get('/dashboard', [DashboardController::class, 'index']);
    //Route::get('/dashboard/chart', [DashboardController::class, 'chart']);
    //Route::post('/upload/summernote', [UploadController::class, 'summernote']);