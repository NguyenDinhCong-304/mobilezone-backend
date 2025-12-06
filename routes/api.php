<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductStoreController;
use App\Http\Controllers\ProductSaleController;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PostControllers;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AttributeController;

Route::apiResources([
    'banner' => BannerController::class,
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

//Route::delete('/product/{id}', [ProductController::class, 'destroy']);       // xóa mềm
//Route::delete('/product/force/{id}', [ProductController::class, 'forceDestroy']); // xóa cứng
//Route::put('/product/restore/{id}', [ProductController::class, 'restore']);  // khôi phục
Route::get('product-new', [ProductController::class,'product_new']);
Route::get('product-sale', [ProductController::class,'product_sale']);
Route::get('product-category/{catid}', [ProductController::class,'product_by_category']);
Route::get('product-all', [ProductController::class,'product_all']);
Route::get('/product/search', [ProductController::class, 'search']);

Route::get('post-new', [PostController::class,'post_new']);

Route::apiResource('productsale', ProductSaleController::class)->except(['show']);
Route::post('productsale/import', [ProductSaleController::class, 'import']);
Route::get('/productsale/template', [ProductSaleController::class, 'exportTemplate']);

Route::get('/product/{id}/attributes', [ProductController::class, 'getAttributes']);
Route::post('/product/{id}/attributes', [ProductController::class, 'updateAttributes']);

Route::get('category/all', [CategoryController::class, 'all']);

Route::post('/register', [UserController::class, 'store']);
Route::get('/verify-email/{token}', [UserController::class, 'verifyEmail']);
Route::post('/login', [UserController::class, 'login']);

Route::post('/user/change-password/{id}', [UserController::class, 'changePassword']);

Route::get('/order/history/{userId}', [OrderController::class, 'orderHistory']);
Route::get('/orders/user/{id}', [OrderController::class, 'getOrdersByUser']);

Route::put('contact/{id}/reply', [ContactController::class, 'reply']);

Route::post('/menu/import', [MenuController::class, 'importFromSource']);
