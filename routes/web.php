<?php

use App\Http\Controllers\ApiHomeController;
use App\Http\Controllers\AuthController;
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
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::post('login', [AuthController::class, 'loginProcess'])->name('loginProcess');

Route::get('home_api', ApiHomeController::class)->name('home_api');

Route::get('/', HomeController::class)->name('home')->middleware(['auth']);
Route::resource('order', OrderController::class)->middleware(['auth']);
Route::resource('payment', PaymentController::class)->middleware(['auth']);
Route::resource('user', UserController::class)->middleware(['auth']);
Route::resource('customer', CustomerController::class)->middleware(['auth']);
Route::resource('product_category', ProductCategoryController::class)->middleware(['auth']);
Route::resource('packaging', PackagingController::class)->middleware(['auth']);
Route::resource('termin', TerminController::class)->middleware(['auth']);
Route::resource('product', ProductController::class)->middleware(['auth']);
