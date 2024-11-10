<?php

use App\Http\Controllers\Dashboard\authentication;
use App\Http\Controllers\Dashboard\BrandController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\ColorController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\ProductImageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Middleware\AuthMiddleWare;
use App\Http\Middleware\DashboardMiddleWare;
use Illuminate\Routing\Controllers\Middleware;

Route::get('/', function () {
    return view('welcome');
});
//Authentication
Route::group(['prefix' => 'admin'],function(){
Route::get('/login',[authentication::class,'login'])->name('auth.index');
Route::post('/login/authentication',[authentication::class,'authenticate'])->name('auth.authenticate');

Route::middleware(AuthMiddleWare::class)->group(function(){

    Route::get('/logout',[authentication::class,'logout'])->name('auth.logout');


       //color
    Route::controller(ColorController::class)->group(function(){
        Route::get("/color",'index')->name("color.index");
        Route::post("/color/list",'list')->name("color.list");
        Route::post("/color/store",'store')->name("color.store");
        Route::post("/color/edit",'edit')->name("color.edit");
        Route::post("/color/update",'update')->name("color.update");
        Route::post("/color/destroy",'destroy')->name("color.destroy");
    });

    //Brand
    Route::controller(BrandController::class)->group(function(){
        Route::get('/brand','index')->name('brand.index');
        Route::post('/brand/store','store')->name('brand.store');
        Route::post('/brand/list','list')->name('brand.list');
        Route::post('/brand/destroy','destroy')->name('brand.destroy');
        Route::post('brand/edit','edit')->name('brand.edit');
        Route::post('/brand/update','update')->name('brand.update');
    });


    //category
    Route::controller(CategoryController::class)->group(function(){
        Route::get('/category','index')->name('category.index');
        Route::post('/category/upload','upload')->name('category.upload');
        Route::post('/category/cancel','cancel')->name('category.cancel');
        Route::post('/category/store','store')->name('category.store');
        Route::post('/category/list','list')->name('category.list');
        Route::post('/category/destroy','destroy')->name('category.destroy');
        Route::post('category/edit','edit')->name('category.edit');
        Route::post('/category/update','update')->name('category.update');
    });

    Route::middleware(DashboardMiddleWare::class)->group(function(){


    });

    Route::controller(DashboardController::class)->group(function(){
        Route::get('/','index')->name('dashboard');
    });
    Route::controller(UserController::class)->group(function(){
        Route::get('/dashboard','index')->name('user.index');
        Route::post('/user/list','list')->name('user.list');
        Route::get("/user/create", "create")->name("user.create");
        Route::post('/user/store','store')->name('user.store');
        Route::post('/user/destroy','destroy')->name('user.destroy');
       });

    Route::controller(ProductController::class)->group(function( ){
        Route::get("/product",'index')->name("product.index");
        Route::post("/product/list",'list')->name("product.list");
        Route::post('product/data','data')->name('product.data');
        Route::post("/product/store",'store')->name("product.store");
        Route::post("/product/edit",'edit')->name("product.edit");
        Route::post("/product/update",'update')->name("product.update");
        Route::post("/product/destroy",'destroy')->name("product.destroy");
    });

    Route::controller(ProductImageController::class)->group(function(){
        Route::post('product/uploads','uploads')->name('product.uploads');
        Route::post('product/cancel','cancel')->name('product.cancel');
    });

});
});
