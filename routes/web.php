<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PackagingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TerminController;
use App\Http\Controllers\UserController;
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

Route::get('/', HomeController::class)->name('home');
Route::resource('order', OrderController::class);
Route::resource('payment', PaymentController::class);
Route::resource('user', UserController::class);
Route::resource('customer', CustomerController::class);
Route::resource('product_category', ProductCategoryController::class);
Route::resource('packaging', PackagingController::class);
Route::resource('termin', TerminController::class);
Route::resource('product', ProductController::class);
