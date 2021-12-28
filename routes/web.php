<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'HomeController@index')->name('home');

Route::prefix('product')->group(function () {
	Route::get('/create', 'ProductController@createView')->name('product::create-view');
	Route::get('/edit/{product_id}', 'ProductController@editView')->name('product::edit-view');
	Route::post('/create/', 'ProductController@create')->name('product::create');
	Route::post('/edit/{product_id}', 'ProductController@edit')->name('product::edit');
	Route::post('/increase-quantity/{product_id}', 'ProductController@increaseQuantity')->name('product::increase-quantity');
	Route::post('/decrease-quantity/{product_id}', 'ProductController@decreaseQuantity')->name('product::decrease-quantity');
	Route::post('/delete/{product_id}', 'ProductController@delete')->name('product::delete');
	Route::get('/list', 'ProductController@listView')->name('product::list-view');
	Route::get('/view/{product_id}', 'ProductController@view')->name('product::view');
	Route::post('/products-json', 'InvoiceController@getProductsJSON')->name('admin::products-json');
});

Route::prefix('activity')->group(function () {
	Route::get('/view', 'ActivityController@view')->name('activity::view');
	Route::post('/get-daily/{date?}', 'ActivityController@getDaily')->name('activity::get-daily');
});

Route::prefix('admin')->group(function () {

	Route::view('/', 'dashboard')
		->middleware('auth')
		->name('admin::index');
	Route::get('/create-user', 'AdminController@createUserView')->name('admin::create-user');
	Route::post('/create-user', 'AdminController@createUser');
	Route::post('/delete-user/{user_id}', 'AdminController@deleteUser')->name('admin::delete-user');
	Route::get('/edit-user/{user_id}', 'AdminController@editUserView')->name('admin::edit-user');
	Route::post('/edit-user/{user_id}', 'AdminController@editUser');
	Route::get('/list-users', 'AdminController@listUsersView')->name('admin::list-users');
});

Route::prefix('invoice')->group(function () {
	Route::get('/', 'InvoiceController@invoice');
	Route::post('/', 'InvoiceController@createInvoice')->name('admin::create-invoice');
	Route::get('/list', 'InvoiceController@invoiceList')->name('admin::invoice-list');
	Route::get('/view/{invoice_id}', 'InvoiceController@invoiceView')->name('admin::invoice-view');
	Route::post('/complete/{invoice_id}', 'InvoiceController@invoiceComplete')->name('admin::invoice-complete');
	Route::post('/complete-cancel/{invoice_id}', 'InvoiceController@invoiceCancel')->name('admin::invoice-cancel');
	Route::post('/mail/{invoice_id}', 'InvoiceController@sendToMail')->name('admin::invoice-mail');
	Route::post('/delete/{invoice_id}', 'InvoiceController@deleteInvoice')->name('admin::delete-invoice');
	Route::post('/invoice-from-proforma/{invoice_id}', 'InvoiceController@createInvoiceFromProforma')->name('admin::invoice-from-proforma');
	Route::post('/invoice-from-advance/{invoice_id}', 'InvoiceController@createInvoiceFromAdvance')->name('admin::invoice-from-advance');

	/* CLIENTS */
	Route::get('/client', 'InvoiceController@client')->name('admin::client');
	Route::post('/client', 'InvoiceController@createClient')->name('admin::create-client');
	Route::get('/client-list', 'InvoiceController@clientList')->name('admin::client-list');
	Route::post('/delete-client/{client_id}', 'InvoiceController@deleteClient')->name('admin::delete-client');
	Route::get('/edit-client/{client_id}', 'InvoiceController@editClientView')->name('admin::edit-client');
	Route::post('/edit-client/{client_id}', 'InvoiceController@editClient')->name('admin::edit-client');

	/** JSON RESPONSES */
	Route::post('/get-invoice-fields', 'InvoiceController@getInvoiceTypeFields')->name('admin::get-invoice-fields');
	Route::post('/get-client-fields', 'InvoiceController@getClientTypeFields')->name('admin::get-client-fields');
	
	Route::post('/get-invoices', 'InvoiceController@getInvoices')->name('admin::get-invoices');
	Route::post('/get-clients', 'InvoiceController@getClients')->name('admin::get-clients');

	/** PDF RESPONSES */
	Route::get('/invoice-pdf/{invoice_id}', 'InvoiceController@invoiceDownloadPDF')->name('admin::invoice-pdf');
});

Route::prefix('file')->group(function () {
	Route::get('/image', 'FileController@image')->name('file::image');
	Route::post('/upload/{folder?}', 'FileController@upload')->name('file::upload');
	Route::post('/delete', 'FileController@delete')->name('file::delete');
	Route::get('/download/{file}/{directory?}/{name?}', 'FileController@download')
		->middleware('auth')
		->name('file::download');
});

Auth::routes([
	'login'		=> true,
	'logout'	=> true,
	'register'	=> false,
	'reset'		=> true,
	'confirm'	=> false,
	'verify'	=> false,
]);

Route::get('logout', 'Auth\LoginController@logout')->name('logout');