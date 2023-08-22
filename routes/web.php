<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CutomerReportsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoicesArchiveController;
use App\Http\Controllers\InvoicesAttachmentsController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\InvoicesReportsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    return view('auth.login');
});

//Auth::routes(['register'=>false]);

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::resource('invoices_all' ,InvoicesController::class)->middleware('auth');
Route::resource('sections',SectionController::class)->middleware('auth');
Route::resource('products',ProductController::class)->middleware('auth');
Route::resource('invoicesDetails',InvoicesDetailsController::class)->middleware('auth');
Route::resource('invoicesAttachment' , InvoicesAttachmentsController::class)->middleware('auth');
Route::get('section/{id}',[InvoicesController::class , 'get_product'])->name('sections.get_product');
Route::get('view_file/{invoice_id}/{file_name}',[InvoicesDetailsController::class ,'view_file'])->name('view_file');
Route::get('download_file/{invoice_id}/{file_name}',[InvoicesDetailsController::class ,'download_file'])->name('download');
Route::get('status_show/{id}' ,[InvoicesController::class ,'status_show'])->middleware('auth')->name('status_show');
Route::Post('status_update/',[InvoicesController::class ,'status_update'])->name('status_update');
Route::Post('delete_file/',[InvoicesDetailsController::class ,'delete_file'])->name('delete_file');
Route::get('invoices_paid/' ,[InvoicesController::class ,'invoices_paid'])->middleware('auth')->name('invoices_paid');
Route::get('invoices_unpaid/' ,[InvoicesController::class ,'invoices_unpaid'])->middleware('auth')->name('invoices_unpaid');
Route::get('invoices_partial/' ,[InvoicesController::class ,'invoices_partial'])->middleware('auth')->name('invoices_partial');
Route::resource('archive' , InvoicesArchiveController::class)->middleware('auth');
Route::get('print_invoice/{id}',[InvoicesController::class ,'print_invoice'])->name('print_invoice')->middleware('auth');
Route::get('export_invoice/',[InvoicesController::class ,'export'])->name('export_invoices')->middleware('auth');
Route::group(['middleware' => ['auth']], function() {

    Route::resource('roles',RoleController::class);

    Route::resource('users',UserController::class);

});

Route::get('invoices_reports/' ,[InvoicesReportsController::class ,'index'])->name('invoices_reports')->middleware('auth');
Route::Post('invoices_search/' ,[InvoicesReportsController::class ,'search_invoices'])->name('search_invoices')->middleware('auth');
Route::get('customers_reports/' ,[CutomerReportsController::class ,'index'])->name('customers_reports')->middleware('auth');
Route::Post('search_customers/' ,[CutomerReportsController::class ,'search_customers'])->name('search_customers')->middleware('auth');

Route::get('/{page}', [AdminController::class ,'index']);

