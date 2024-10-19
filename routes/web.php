<?php

use App\Http\Controllers\Dashboard\BrandController;
use App\Http\Controllers\Dashboard\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\UserController;

Route::get('/', function () {
    return view('welcome');
});
Route::controller(UserController::class)->group(function(){
 Route::get('/dashboard','index')->name('user.index');
 Route::post('/user/list','list')->name('user.list');
 Route::get("/user/create", "create")->name("user.create");
 Route::post('/user/store','store')->name('user.store');
 Route::post('/user/destroy','destroy')->name('user.destroy');
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

//Brand
Route::controller(BrandController::class)->group(function(){
    Route::get('/brand','index')->name('brand.index');
    Route::post('/brand/store','store')->name('brand.store');
    Route::post('/brand/list','list')->name('brand.list');
    Route::post('/brand/destroy','destroy')->name('brand.destroy');
    Route::post('brand/edit','edit')->name('brand.edit');
    Route::post('/brand/update','update')->name('brand.update');
});
