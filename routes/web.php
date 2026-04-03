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
Route::post('login', [AuthController::class, 'loginProcess'])->name('loginProcess');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('home_api', ApiHomeController::class)->name('home_api');
Route::get('/', HomeController::class)->name('home')->middleware(['auth']);

Route::middleware(['auth'])->group(function () {

    /**
     * =========================
     * ORDER
     * =========================
     */
    Route::get('order', [OrderController::class, 'index'])
        ->name('order.index')
        ->middleware('role:Owner,Admin');

    Route::get('order/create', [OrderController::class, 'create'])
        ->name('order.create')
        ->middleware('role:Owner,Admin');

    Route::post('order', [OrderController::class, 'store'])
        ->name('order.store')
        ->middleware('role:Owner,Admin');

    Route::get('order/{order}', [OrderController::class, 'show'])
        ->name('order.show')
        ->middleware('role:Owner,Admin');

    Route::get('order/{order}/edit', [OrderController::class, 'edit'])
        ->name('order.edit')
        ->middleware('role:Owner');

    Route::put('order/{order}', [OrderController::class, 'update'])
        ->name('order.update')
        ->middleware('role:Owner');

    Route::delete('order/{order}', [OrderController::class, 'destroy'])
        ->name('order.destroy')
        ->middleware('role:Owner');


    /**
     * =========================
     * PAYMENT
     * =========================
     */
    Route::get('payment', [PaymentController::class, 'index'])
        ->name('payment.index')
        ->middleware('role:Owner,Admin');

    Route::get('payment/create', [PaymentController::class, 'create'])
        ->name('payment.create')
        ->middleware('role:Owner,Admin');

    Route::post('payment', [PaymentController::class, 'store'])
        ->name('payment.store')
        ->middleware('role:Owner,Admin');

    Route::get('payment/{payment}', [PaymentController::class, 'show'])
        ->name('payment.show')
        ->middleware('role:Owner,Admin');

    Route::get('payment/{payment}/edit', [PaymentController::class, 'edit'])
        ->name('payment.edit')
        ->middleware('role:Owner');

    Route::put('payment/{payment}', [PaymentController::class, 'update'])
        ->name('payment.update')
        ->middleware('role:Owner');

    Route::delete('payment/{payment}', [PaymentController::class, 'destroy'])
        ->name('payment.destroy')
        ->middleware('role:Owner');


    /**
     * =========================
     * CUSTOMER
     * =========================
     */
    Route::get('customer', [CustomerController::class, 'index'])
        ->name('customer.index')
        ->middleware('role:Owner,Admin');

    Route::get('customer/create', [CustomerController::class, 'create'])
        ->name('customer.create')
        ->middleware('role:Owner,Admin');

    Route::post('customer', [CustomerController::class, 'store'])
        ->name('customer.store')
        ->middleware('role:Owner,Admin');

    Route::get('customer/{customer}/edit', [CustomerController::class, 'edit'])
        ->name('customer.edit')
        ->middleware('role:Owner');

    Route::put('customer/{customer}', [CustomerController::class, 'update'])
        ->name('customer.update')
        ->middleware('role:Owner');

    Route::delete('customer/{customer}', [CustomerController::class, 'destroy'])
        ->name('customer.destroy')
        ->middleware('role:Owner');


    /**
     * =========================
     * PRODUCT CATEGORY
     * =========================
     */
    Route::get('product_category', [ProductCategoryController::class, 'index'])
        ->name('product_category.index')
        ->middleware('role:Owner,Admin');

    Route::get('product_category/create', [ProductCategoryController::class, 'create'])
        ->name('product_category.create')
        ->middleware('role:Owner,Admin');

    Route::post('product_category', [ProductCategoryController::class, 'store'])
        ->name('product_category.store')
        ->middleware('role:Owner,Admin');

    Route::get('product_category/{id}/edit', [ProductCategoryController::class, 'edit'])
        ->name('product_category.edit')
        ->middleware('role:Owner');

    Route::put('product_category/{id}', [ProductCategoryController::class, 'update'])
        ->name('product_category.update')
        ->middleware('role:Owner');

    Route::delete('product_category/{id}', [ProductCategoryController::class, 'destroy'])
        ->name('product_category.destroy')
        ->middleware('role:Owner');


    /**
     * =========================
     * PACKAGING
     * =========================
     */
    Route::get('packaging', [PackagingController::class, 'index'])
        ->name('packaging.index')
        ->middleware('role:Owner,Admin');

    Route::get('packaging/create', [PackagingController::class, 'create'])
        ->name('packaging.create')
        ->middleware('role:Owner,Admin');

    Route::post('packaging', [PackagingController::class, 'store'])
        ->name('packaging.store')
        ->middleware('role:Owner,Admin');

    Route::get('packaging/{id}/edit', [PackagingController::class, 'edit'])
        ->name('packaging.edit')
        ->middleware('role:Owner');

    Route::put('packaging/{id}', [PackagingController::class, 'update'])
        ->name('packaging.update')
        ->middleware('role:Owner');

    Route::delete('packaging/{id}', [PackagingController::class, 'destroy'])
        ->name('packaging.destroy')
        ->middleware('role:Owner');


    /**
     * =========================
     * PRODUCT
     * =========================
     */
    Route::get('product', [ProductController::class, 'index'])
        ->name('product.index')
        ->middleware('role:Owner,Admin');

    Route::get('product/create', [ProductController::class, 'create'])
        ->name('product.create')
        ->middleware('role:Owner,Admin');

    Route::post('product', [ProductController::class, 'store'])
        ->name('product.store')
        ->middleware('role:Owner,Admin');

    Route::get('product/{product}/edit', [ProductController::class, 'edit'])
        ->name('product.edit')
        ->middleware('role:Owner');

    Route::put('product/{product}', [ProductController::class, 'update'])
        ->name('product.update')
        ->middleware('role:Owner');

    Route::delete('product/{product}', [ProductController::class, 'destroy'])
        ->name('product.destroy')
        ->middleware('role:Owner');


    /**
     * =========================
     * USER (OWNER ONLY)
     * =========================
     */
    Route::get('user', [UserController::class, 'index'])
        ->name('user.index')
        ->middleware('role:Owner');

    Route::get('user/create', [UserController::class, 'create'])
        ->name('user.create')
        ->middleware('role:Owner');

    Route::post('user', [UserController::class, 'store'])
        ->name('user.store')
        ->middleware('role:Owner');

    Route::get('user/{user}/edit', [UserController::class, 'edit'])
        ->name('user.edit')
        ->middleware('role:Owner');

    Route::put('user/{user}', [UserController::class, 'update'])
        ->name('user.update')
        ->middleware('role:Owner');

    Route::delete('user/{user}', [UserController::class, 'destroy'])
        ->name('user.destroy')
        ->middleware('role:Owner');


    /**
     * =========================
     * TERMIN (OWNER ONLY)
     * =========================
     */
    Route::get('termin', [TerminController::class, 'index'])
        ->name('termin.index')
        ->middleware('role:Owner');

    Route::get('termin/create', [TerminController::class, 'create'])
        ->name('termin.create')
        ->middleware('role:Owner');

    Route::post('termin', [TerminController::class, 'store'])
        ->name('termin.store')
        ->middleware('role:Owner');

    Route::get('termin/{termin}/edit', [TerminController::class, 'edit'])
        ->name('termin.edit')
        ->middleware('role:Owner');

    Route::put('termin/{termin}', [TerminController::class, 'update'])
        ->name('termin.update')
        ->middleware('role:Owner');

    Route::delete('termin/{termin}', [TerminController::class, 'destroy'])
        ->name('termin.destroy')
        ->middleware('role:Owner');

});
