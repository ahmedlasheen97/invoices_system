<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\sectionsController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\IncoiceArchieveController;
use App\Http\Controllers\UserController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::resource('invoices', App\Http\Controllers\InvoicesController::class);
Route::resource('sections', App\Http\Controllers\sectionsController::class);
Route::post('sections/update/{id}', [App\Http\Controllers\sectionsController::class, 'update']);
Route::post('sections/delete/{id}', [App\Http\Controllers\sectionsController::class, 'destroy']);
Route::resource('products', App\Http\Controllers\ProductsController::class);
Route::post('products/delete/{id}', [App\Http\Controllers\ProductsController::class, 'destroy']);
Route::get('/details_invoices/{id}', 'App\Http\Controllers\InvoicesDetailsController@edit');
Route::get('View_file/{invoice_number}/{file_name}', 'App\Http\Controllers\InvoicesDetailsController@open_file');
Route::get('download/{invoice_number}/{file_name}', 'App\Http\Controllers\InvoicesDetailsController@download_file');
Route::delete('delete_file', [App\Http\Controllers\InvoicesDetailsController::class, 'destroy'])->name('delete_file');
Route::resource('InvoiceAttachments', App\Http\Controllers\InvoiceAttachmentsController::class);

Route::get('edit_invoice/{id}', [App\Http\Controllers\InvoicesController::class, 'edit']);
Route::post('invoices/update', [App\Http\Controllers\InvoicesController::class, 'update']);
Route::delete('deleteinvoice/{id}', [App\Http\Controllers\InvoicesController::class, 'destroy'])->name('delete.invoice');

Route::get('Status_show/{id}', [App\Http\Controllers\InvoicesController::class, 'show'])->name('Status_show');
Route::post('Status_update/{id}', [App\Http\Controllers\InvoicesController::class, 'Status_update'])->name('Status_Update');

Route::get('/section/{id}', [App\Http\Controllers\InvoicesController::class,'getproducts']);

Route::get('invoices_Partial', [App\Http\Controllers\InvoicesController::class, 'invoice_Partial']);
Route::get('invoices_paid', [App\Http\Controllers\InvoicesController::class, 'invoices_paid']);
Route::get('invoices_unpaid', [App\Http\Controllers\InvoicesController::class, 'invoices_unpaid']);

Route::resource('Archive', App\Http\Controllers\IncoiceArchiveController::class);

Route::get('print_invoice/{id}', [App\Http\Controllers\InvoicesController::class, 'print_invoice']);
Route::get('export_invoices', [App\Http\Controllers\InvoicesController::class, 'export']);
Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles',App\Http\Controllers\RoleController::class);
    Route::resource('users',App\Http\Controllers\UserController::class);
    });

Route::get('invoices_report', [App\Http\Controllers\InvoicesReportController::class, 'index']);
Route::post('Search_invoices', [App\Http\Controllers\InvoicesReportController::class, 'Search_invoices']);

Route::get('customer_report', [App\Http\Controllers\CusromerReportController::class, 'index']);
Route::post('Search_customers', [App\Http\Controllers\CusromerReportController::class, 'Search_customers']);




Route::get('/{page}','App\Http\Controllers\AdminController@index');


