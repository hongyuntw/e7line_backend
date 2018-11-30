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




Route::get('/', function () {
    return redirect()->route('dashboard.index');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');

    Route::get('products', 'ProductController@index')->name('products.index');
    Route::get('products/create', 'ProductController@create')->name('products.create');
    Route::post('products', 'ProductController@store')->name('products.store');
    Route::get('products/{product}/edit', 'ProductController@edit')->name('products.edit');
    Route::patch('products/{product}', 'ProductController@update')->name('products.update');
    Route::delete('products/{product}', 'ProductController@destroy')->name('products.destroy');
    Route::post('products/{product}', 'ProductController@remove')->name('products.remove');

    Route::post('categories', 'CategoryController@store')->name('categories.store');

    Route::get('sales', 'SaleController@index')->name('sales.index');
//    Route::get('sales/create', 'SaleController@create')->name('sales.create');
    Route::post('sales', 'SaleController@store')->name('sales.store');
    Route::get('sales/{sale}/edit', 'SaleController@edit')->name('sales.edit');
    Route::patch('sales/{sale}', 'SaleController@update')->name('sales.update');
    Route::delete('sales/{sale}', 'SaleController@destroy')->name('sales.destroy');


    Route::get('members', 'MemberController@index')->name('members.index');
//    Route::get('members/create', 'MemberController@create')->name('members.create');
    Route::post('members', 'MemberController@store')->name('members.store');
    Route::get('members/{member}/edit', 'MemberController@edit')->name('members.edit');
    Route::patch('members/{member}', 'MemberController@update')->name('members.update');
    Route::delete('members/{member}', 'MemberController@destroy')->name('members.destroy');




});

Auth::routes();
Route::group([
    'namespace' => 'Auth',

], function () {
    Route::get('/password/reset/{token}', 'PasswordResetController@resetpassword');

});
