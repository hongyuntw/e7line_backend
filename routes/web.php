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
    Route::get('/ajax/setPageSession','DashboardController@setPageSession')->name('dashboard.setPageSession');

    Route::post('/ajax/set_concat_status','DashboardController@set_concat_status')->name('dashboard.set_concat_status');


// 報價
    Route::get('quote','QuoteController@index')->name('quote.index');
    Route::get('quotes/create', 'QuoteController@create')->name('quote.create');
    Route::post('quotes', 'QuoteController@store')->name('quote.store');
    Route::get('quotes/ajax/getProductQuote','QuoteController@getProductQuote')->name('quote.getProductQuote');
    Route::get('quotes/ajax/initIndex','QuoteController@initIndex')->name('quote.initIndex');


    Route::post('quotes/{quote}/delete', 'QuoteController@delete')->name('quote.delete');

    Route::get('quotes/{quote}/edit', 'QuoteController@edit')->name('quote.edit');
    Route::post('quotes/{quote}', 'QuoteController@update')->name('quote.update');
    Route::get('quotes/ajax/getChartData','QuoteController@getChartData')->name('quote.getChartData');


//    for customer
    Route::get('customers/{customer}/edit{request?}', 'CustomersController@edit')->name('customers.edit');
    Route::patch('customers/{customer}', 'CustomersController@update')->name('customers.update');
//    Route::delete('customers/{customer}', 'CustomersController@destroy')->name('customers.destroy');
    Route::get('customers/create', 'CustomersController@create')->name('customers.create');
    Route::post('customers', 'CustomersController@store')->name('customers.store');
    Route::get('customers/{customer}','CustomersController@show')->name('customers.show');
    Route::post('customers/{customer}/delete_back_to_index', 'CustomersController@delete_back_to_index')->name('customers.delete_back_to_index');
    Route::post('customers/import', 'CustomersController@import')->name('customers.import');

    Route::post('customers/{customer}', 'CustomersController@delete')->name('customers.delete');
    Route::get('customers/export/downloadTemplate','CustomersController@downloadTemplate')->name('customers.downloadTemplate');


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
    Route::post('ajax/get_e7line_info','CustomersController@get_e7line_info')->name('customers.get_e7line_info');

    Route::post('ajax/showE7lineInfo','CustomersController@showE7lineInfo')->name('customers.showE7lineInfo');

//    tasks
    Route::get('tasks','TaskController@index')->name('tasks.index');
    Route::get('tasks/create', 'TaskController@create')->name('tasks.create');
    Route::post('tasks', 'TaskController@store')->name('tasks.store');
    Route::post('tasks/{task_assignment}/delete', 'TaskController@delete')->name('tasks.delete');
    Route::get('tasks/{task}/edit', 'TaskController@edit')->name('tasks.edit');
    Route::patch('tasks/{task}', 'TaskController@update')->name('tasks.update');
//    業務回覆任務完成
    Route::post('ajax/tasks/taskComplete','TaskController@taskComplete')->name('tasks.taskComplete');
//    root 檢查通過
    Route::post('ajax/tasks/taskChecked','TaskController@taskChecked')->name('tasks.taskChecked');
//    root 退回狀態
    Route::post('ajax/tasks/taskBack','TaskController@taskBack')->name('tasks.taskBack');

    Route::get('tasks/create','TaskController@create')->name('tasks.create');
    Route::post('ajax/tasks/taskBackToProcess','TaskController@taskBackToProcess')->name('tasks.taskBackToProcess');
    Route::get('tasks/ajax/getProcessPage','TaskController@getProcessPage')->name('tasks.getProcessPage');
    Route::get('tasks/ajax/setPageSession','TaskController@setPageSession')->name('tasks.setPageSession');
    Route::get('tasks/ajax/getCheckPage','TaskController@getCheckPage')->name('tasks.getCheckPage');
    Route::get('tasks/ajax/getDonePage','TaskController@getDonePage')->name('tasks.getDonePage');
    Route::post('tasks/ajax/deleteMsg','TaskController@deleteMsg')->name('tasks.deleteMsg');









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
    Route::post('orders/changeStatusBack','OrderController@changeStatusBack')->name('orders.changeStatusBack');
    Route::get('orders','OrderController@index')->name('orders.index');
    Route::get('orders/{order}/detail','OrderController@detail')->name('orders.detail');
    Route::get('orders/create','OrderController@create')->name('orders.create');
    Route::post('orders','OrderController@store')->name('orders.store');
    Route::get('orders/{order}/edit{request?}', 'OrderController@edit')->name('orders.edit');
    Route::post('orders/update/{order}', 'OrderController@update')->name('orders.update');
    Route::post('orders/{order}', 'OrderController@delete')->name('orders.delete');
    Route::get('orders/{order}/copy', 'OrderController@copy')->name('orders.copy');

    Route::post('orders/delete_backto_index/{order}', 'OrderController@delete_backto_index')->name('orders.delete_backto_index');

    Route::get('orders/{order}/export','OrderController@export')->name('orders.export');

    Route::post('ajax/exportFromIndex','OrderController@exportFromIndex')->name('orders.exportFromIndex');

    Route::get('orders/{order}/orderSuccess','OrderController@orderSuccess')->name('orders.orderSuccess');



    Route::get('orders/{order}/get_code','OrderController@get_code')->name('orders.get_code');
    Route::post('ajax/orders/get_e7line_account_info','OrderController@get_e7line_account_info')->name('orders.get_e7line_account_info');

    Route::post('ajax/index_get_code','OrderController@index_get_code')->name('orders.index_gex_code');

    Route::post('ajax/changeStatus2Success','OrderController@changeStatus2Success')->name('orders.changeStatus2Success');

    Route::get('ajax/getTaxIds','OrderController@getTaxIds')->name('orders.getTaxIds');


    Route::get('ajax/get_customer_info', 'OrderController@get_customer_info')->name('orders.get_customer_info');
    Route::get('ajax/get_concat_person_info', 'OrderController@get_concat_person_info')->name('orders.get_concat_person_info');

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
    Route::get('products/delete','ProductController@delete')->name('products.delete');


// report
    Route::get('report','ReportController@index')->name('report.index');
//    Route::get('report/admin','ReportController@indexAdmin')->name('report.indexAdmin');
//    Route::get('report/user','ReportController@indexUser')->name('report.indexUser');
    Route::get("ajax/report/createActiveCustomerChart",'ReportController@createActiveCustomerChart')->name('report.createActiveCustomerChart');
    Route::get("ajax/report/createRecordCountChart",'ReportController@createRecordCountChart')->name('report.createRecordCountChart');
    Route::get("ajax/report/createTotalAmountChart",'ReportController@createTotalAmountChart')->name('report.createTotalAmountChart');

    Route::get("ajax/report/createActiveCustomerChart_user",'ReportController@createActiveCustomerChart_user')->name('report.createActiveCustomerChart_user');
    Route::get("ajax/report/createRecordCountChart_user",'ReportController@createRecordCountChart_user')->name('report.createRecordCountChart_user');
    Route::get("ajax/report/createTotalAmountChart_user",'ReportController@createTotalAmountChart_user')->name('report.createTotalAmountChart_user');


//    record
    Route::get('concat_records','CustomersController@record_index')->name('record.index');
    Route::get('concat_records/{id}','CustomersController@show_record')->name('record.show_record');

//    Route::get("ajax/create",'ReportController@createTotalAmountChart')->name('report.createTotalAmountChart');
//    Route::get("ajax/createTotalAmountChart",'ReportController@createTotalAmountChart')->name('report.createTotalAmountChart');

    //senao orders
    Route::post('senao_orders/import', 'SenaoOrderController@import')->name('senao_orders.import');
    Route::post('senao_orders/export', 'SenaoOrderController@export')->name('senao_orders.export');
    Route::post('senao_orders/import_product','SenaoOrderController@import_product')->name('senao_orders.import_product');
    Route::get('senao_orders', 'SenaoOrderController@index')->name('senao_orders.index');
    Route::post('ajax/senao_orders/set_status_to_return', 'SenaoOrderController@set_status_to_return')->name('senao_orders.set_status_to_return');

//    senao products
    Route::get('senao_products','SenaoProductController@index')->name('senao_products.index');
    Route::get('senao_products/create','SenaoProductController@create')->name('senao_products.create');
    Route::get('senao_products/{isbn_relation}/edit','SenaoProductController@edit')->name('senao_products.edit');
    Route::post('senao_products/{isbn_relation}/update','SenaoProductController@update')->name('senao_products.update');
    Route::post('senao_products/store','SenaoProductController@store')->name('senao_products.store');
});

Auth::routes();



Route::group([
    'namespace' => 'Auth',

], function () {
    Route::get('/member/password/reset/{token}', 'PasswordResetController@resetpassword');

});
