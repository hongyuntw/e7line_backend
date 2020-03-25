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




//Route::get('/', function () {
//    return redirect()->route('dashboard.index');
//});

Route::middleware('auth')->group(function () {

//    index
    Route::get('/','DashboardController@index')->name('dashboard.index');



//    for customer
    Route::get('customers/{customer}/edit', 'CustomersController@edit')->name('customers.edit');
    Route::patch('customers/{customer}', 'CustomersController@update')->name('customers.update');
//    Route::delete('customers/{customer}', 'CustomersController@destroy')->name('customers.destroy');
    Route::get('customers/create', 'CustomersController@create')->name('customers.create');
    Route::post('customers', 'CustomersController@store')->name('customers.store');
    Route::get('customers/{customer}','CustomersController@show')->name('customers.show');
    Route::post('customers/{customer}', 'CustomersController@delete')->name('customers.delete');
    Route::get('customers/{customer}/record','CustomersController@record')->name('customers.record');
    Route::get('customers/{request?}','CustomersController@index')->name('customers.index');

//    Route::get('customers/mycustomer/{user}','CustomersController@mycustomer')->name('customers.mycustomer');
//    Route::get('customers/filter','CustomersController@filter')->name('customers.filter');





//    for welfare
    Route::get('welfarestatus','WelfareStatusController@index')->name('welfarestatus.index');




//  for user
    Route::get('users','UserController@index')->name('users.index');

});

Auth::routes();



Route::group([
    'namespace' => 'Auth',

], function () {
    Route::get('/member/password/reset/{token}', 'PasswordResetController@resetpassword');

});
