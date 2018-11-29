<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('products', 'Api\ProductController@index');
Route::get('products/{product}', 'Api\ProductController@show');

Route::get('carts', 'Api\CartController@index');
Route::get('carts/{cart}', 'Api\CartController@show');
Route::get('categories', 'Api\CategoryController@index');

Route::get('types', 'Api\TypeController@index');

Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');


Route::get('image/{filename}','PhotoController@image');
Route::post('password/reset','PasswordResetController@create');
Route::middleware('auth:api')->group(function () {

    Route::post('sales', 'Api\OrderController@store');
    Route::post('logout', 'Api\AuthController@logout');
    Route::get('me', 'Api\AuthController@me');
    Route::post('refresh', 'Api\AuthController@refresh');

});

Route::group([
    'namespace' => 'Auth',
    'middleware' => 'api',
    'prefix' => 'password'
], function () {
    Route::post('create', 'PasswordResetController@create');
    Route::get('find/{token}', 'PasswordResetController@find');

    Route::post('reset', 'PasswordResetController@reset')->name('member_reset');
});

