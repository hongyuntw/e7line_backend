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

//    dashboard
    Route::get('/','DashboardController@index')->name('dashboard.index');
    Route::get('/ajax/getPage','DashboardController@getPage')->name('dashboard.getPage');
    Route::get('/ajax/getwPage','DashboardController@getwPage')->name('dashboard.getwPage');
    Route::get('/ajax/getoPage','DashboardController@getoPage')->name('dashboard.getoPage');

    Route::post('/ajax/set_concat_status','DashboardController@set_concat_status')->name('dashboard.set_concat_status');





//    for customer
    Route::get('customers/{customer}/edit{request?}', 'CustomersController@edit')->name('customers.edit');
    Route::patch('customers/{customer}', 'CustomersController@update')->name('customers.update');
//    Route::delete('customers/{customer}', 'CustomersController@destroy')->name('customers.destroy');
    Route::get('customers/create', 'CustomersController@create')->name('customers.create');
    Route::post('customers', 'CustomersController@store')->name('customers.store');
    Route::get('customers/{customer}','CustomersController@show')->name('customers.show');
    Route::post('customers/{customer}/delete_back_to_index', 'CustomersController@delete_back_to_index')->name('customers.delete_back_to_index');

    Route::post('customers/{customer}', 'CustomersController@delete')->name('customers.delete');


    Route::get('customers/{customer}/record','CustomersController@record')->name('customers.record');
    Route::post('update_status', 'CustomersController@update_status')->name('customers.update_status');
    Route::post('update_concat_record', 'CustomersController@update_concat_record')->name('customers.update_concat_record');
    Route::post('delete_welfare_type', 'CustomersController@delete_welfare_type')->name('customers.delete_welfare_type');

    Route::post('delete_concat_record', 'CustomersController@delete_concat_record')->name('customers.delete_concat_record');


    Route::post('update_concat_person', 'CustomersController@update_concat_person')->name('customers.update_concat_person');
    Route::post('add_welfare_types', 'CustomersController@add_welfare_types')->name('customers.add_welfare_types');
    Route::get('customers/{customer}/record','CustomersController@record')->name('customers.record');

    Route::post('add_concat_person/{request?}','CustomersController@add_concat_person')->name('customers.add_concat_person');
    Route::post('add_concat_record/{request?}','CustomersController@add_concat_record')->name('customers.add_concat_record');
    Route::get('customers/{request?}','CustomersController@index')->name('customers.index');






//    for welfare
    Route::patch('welfare_status/{welfare_status}', 'WelfareStatusController@update')->name('welfare_status.update');
    Route::get('welfare_status/add_customer_welfare', 'WelfareStatusController@add_customer_welfare')->name('welfare_status.add_customer_welfare');
    Route::get('ajax/get_customer_welfare_status', 'WelfareStatusController@get_customer_welfare_status')->name('welfare_status.get_customer_welfare_status');
    Route::get('ajax/get_welfare_purpose_budget_status', 'WelfareStatusController@get_welfare_purpose_budget_status')->name('welfare_status.get_welfare_purpose_budget_status');


    Route::post('welfare_status/update_customer_welfare', 'WelfareStatusController@update_customer_welfare')->name('welfare_status.update_customer_welfare');


    Route::get('welfare_status/{welfare_status}/edit', 'WelfareStatusController@edit')->name('welfare_status.edit');
    Route::get('welfare_status/add_welfare_type', 'WelfareStatusController@add_welfare_type')->name('welfare_status.add_welfare_type');
    Route::post('welfare_status', 'WelfareStatusController@store_welfare_type')->name('welfare_status.store_welfare_type');

    Route::get('welfare_status/{request?}','WelfareStatusController@index')->name('welfare_status.index');





//  for user
    Route::get('users','UserController@index')->name('users.index');
    Route::get('users/create', 'UserController@create')->name('users.create');
    Route::post('users', 'UserController@store')->name('users.store');
    Route::get('users/{user}/edit', 'UserController@edit')->name('users.edit');
    Route::patch('users/{user}', 'UserController@update')->name('users.update');





//    for mails
    Route::get('mails','MailsController@index')->name('mails.index');
    Route::get('mails/export','MailsController@export')->name('mails.export');

//    orders
    Route::get('orders','OrderController@index')->name('orders.index');
    Route::get('orders/{order}/detail','OrderController@detail')->name('orders.detail');
    Route::get('orders/create','OrderController@create')->name('orders.create');
    Route::post('orders','OrderController@store')->name('orders.store');
    Route::get('orders/{order}/edit{request?}', 'OrderController@edit')->name('orders.edit');
    Route::post('orders/update/{order}', 'OrderController@update')->name('orders.update');
    Route::post('orders/{order}', 'OrderController@delete')->name('orders.delete');
    Route::post('orders/delete_backto_index/{order}', 'OrderController@delete_backto_index')->name('orders.delete_backto_index');



    Route::get('orders/{order}/get_code','OrderController@get_code')->name('orders.get_code');
    Route::post('ajax/orders/get_e7line_account_info','OrderController@get_e7line_account_info')->name('orders.get_e7line_account_info');

    Route::post('ajax/index_get_code','OrderController@index_get_code')->name('orders.index_gex_code');

    Route::get('ajax/get_customer_concat_persons', 'OrderController@get_customer_concat_persons')->name('orders.get_customer_concat_persons');
    Route::get('ajax/get_product_details', 'OrderController@get_product_details')->name('orders.get_product_details');
    Route::get('ajax/get_product_details_price', 'OrderController@get_product_details_price')->name('orders.get_product_details_price');
    Route::post('ajax/validate_order_form','OrderController@validate_order_form')->name('orders.validate_order_form');


//    order items
    Route::get('order_items','OrderItemController@index')->name('order_items.index');
    Route::post('/ajax/change_item_status','OrderItemController@change_item_status')->name('order_items.change_item_status');
    Route::get('ajax/compute_quantity','OrderItemController@compute_quantity')->name('order_items.compute_quantity');


//  products
    Route::get('products/create','ProductController@create')->name('products.create');
    Route::get('products/edit', 'ProductController@edit')->name('products.edit');
    Route::post('ajax/products/change_name','ProductController@change_name')->name('products.change_name');
    Route::post('products/update','ProductController@update')->name('products.update');
    Route::post('products','ProductController@store')->name('products.store');
    Route::post('ajax/search','ProductController@search')->name('products.search');
    Route::post('ajax/validate_product_form','ProductController@validate_product_form')->name('products.validate_product_form');








});

Auth::routes();



Route::group([
    'namespace' => 'Auth',

], function () {
    Route::get('/member/password/reset/{token}', 'PasswordResetController@resetpassword');

});
