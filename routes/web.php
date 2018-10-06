<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'DashboardController@index');

Route::get('products', function () {
    return view('products.index');
});

Route::get('products/create', function () {
    return view('products.create');
});

Route::get('products/{product}/edit', function ($product) {
    return view('products.edit');
});
