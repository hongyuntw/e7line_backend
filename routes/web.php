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
    Route::post('update_status', 'CustomersController@update_status')->name('customers.update_status');
    Route::post('update_concat_record', 'CustomersController@update_concat_record')->name('customers.update_concat_record');
    Route::post('delete_welfare_type', 'CustomersController@delete_welfare_type')->name('customers.delete_welfare_type');

    Route::post('update_concat_person', 'CustomersController@update_concat_person')->name('customers.update_concat_person');
    Route::post('add_welfare_types', 'CustomersController@add_welfare_types')->name('customers.add_welfare_types');
    Route::get('customers/{customer}/record','CustomersController@record')->name('customers.record');

    Route::post('add_concat_person/{request?}','CustomersController@add_concat_person')->name('customers.add_concat_person');
    Route::post('add_concat_record/{request?}','CustomersController@add_concat_record')->name('customers.add_concat_record');
    Route::get('customers/{request?}','CustomersController@index')->name('customers.index');






//    for welfare
    Route::patch('welfare_status/{welfare_status}', 'WelfareStatusController@update')->name('welfare_status.update');

    Route::get('welfare_status/{welfare_status}/edit', 'WelfareStatusController@edit')->name('welfare_status.edit');
    Route::get('welfare_status/add_welfare_type', 'WelfareStatusController@add_welfare_type')->name('welfare_status.add_welfare_type');
    Route::post('welfare_status', 'WelfareStatusController@store_welfare_type')->name('welfare_status.store_welfare_type');

    Route::get('welfare_status/{request?}','WelfareStatusController@index')->name('welfare_status.index');





//  for user
    Route::get('users','UserController@index')->name('users.index');
    Route::get('users/create', 'UserController@create')->name('users.create');
    Route::post('users', 'UserController@store')->name('users.store');


});

Auth::routes();



Route::group([
    'namespace' => 'Auth',

], function () {
    Route::get('/member/password/reset/{token}', 'PasswordResetController@resetpassword');

});
