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
    Route::post('products/up/{product}', 'ProductController@upup')->name('products.upup');
    Route::get('products/showup}', 'ProductController@showup')->name('products.showup');
    Route::get('products/showremove}', 'ProductController@showremove')->name('products.showremove');
    Route::post('categories', 'CategoryController@store')->name('categories.store');

    Route::get('products/search', 'ProductController@search')->name('products.search');

    Route::post('users/levelup/{user}', 'UserController@levelup')->name('users.levelup');
    Route::post('users/leveldown/{user}', 'UserController@leveldown')->name('users.leveldown');

    Route::get('sales', 'SaleController@index')->name('sales.index');
    Route::post('sales', 'SaleController@store')->name('sales.store');
    Route::get('sales/{sale}/edit', 'SaleController@edit')->name('sales.edit');
    Route::patch('sales/{sale}', 'SaleController@update')->name('sales.update');
    Route::delete('sales/{sale}', 'SaleController@destroy')->name('sales.destroy');
    Route::get('sales/showup}', 'SaleController@showup')->name('sales.showup');
    Route::get('sales/showremove}', 'SaleController@showremove')->name('sales.showremove');

    Route::get('coupons', 'CouponController@index')->name('coupons.index');

    Route::get('suggestions', 'SuggestionController@index')->name('suggestions.index');
    Route::get('suggestions/{suggestion}/edit', 'SuggestionController@edit')->name('suggestions.edit');
    Route::post('suggestions/reply/{suggestion}', 'SuggestionController@reply')->name('suggestions.reply');


    Route::get('members', 'MemberController@index')->name('members.index');
//    Route::get('members/create', 'MemberController@create')->name('members.create');
    Route::post('members', 'MemberController@store')->name('members.store');
    Route::get('members/{member}/edit', 'MemberController@edit')->name('members.edit');
    Route::patch('members/{member}', 'MemberController@update')->name('members.update');
    Route::delete('members/{member}', 'MemberController@destroy')->name('members.destroy');

    Route::get('users', 'UserController@index')->name('users.index');
    Route::get('users/create', 'UserController@create')->name('users.create');
    Route::post('users', 'UserController@store')->name('users.store');


    Route::get('ads', 'ADController@index')->name('ads.index');
    Route::get('ads/{ad}/edit', 'AdController@edit')->name('ads.edit');
    Route::patch('ads/{ad}', 'AdController@update')->name('ads.update');


});

Auth::routes();
Route::get('/redirect', 'SocialAuthFacebookController@redirect')->name('facebook');
Route::get('/callback', 'SocialAuthFacebookController@callback');


Route::group([
    'namespace' => 'Auth',

], function () {
    Route::get('/member/password/reset/{token}', 'PasswordResetController@resetpassword');

});
