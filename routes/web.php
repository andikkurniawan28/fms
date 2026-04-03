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

Route::middleware(['auth'])->group(function () {

    /**
     * =========================
     * OWNER (FULL ACCESS)
     * =========================
     */
    Route::middleware(['role:Owner'])->group(function () {
        Route::resource('order', OrderController::class);
        Route::resource('payment', PaymentController::class);
        Route::resource('user', UserController::class);
        Route::resource('customer', CustomerController::class);
        Route::resource('product_category', ProductCategoryController::class);
        Route::resource('packaging', PackagingController::class);
        Route::resource('termin', TerminController::class);
        Route::resource('product', ProductController::class);
    });

    /**
     * =========================
     * ADMIN (LIMITED ACCESS)
     * =========================
     */
    Route::middleware(['role:Admin'])->group(function () {

        // ORDER → index, create, store, show
        Route::resource('order', OrderController::class)->only([
            'index', 'create', 'store', 'show'
        ]);

        // PAYMENT → index, create, store, show
        Route::resource('payment', PaymentController::class)->only([
            'index', 'create', 'store', 'show'
        ]);

        // CUSTOMER → index, create, store
        Route::resource('customer', CustomerController::class)->only([
            'index', 'create', 'store'
        ]);

        // PRODUCT CATEGORY → index, create, store
        Route::resource('product_category', ProductCategoryController::class)->only([
            'index', 'create', 'store'
        ]);

        // PACKAGING → index, create, store
        Route::resource('packaging', PackagingController::class)->only([
            'index', 'create', 'store'
        ]);

        // PRODUCT → index, create, store
        Route::resource('product', ProductController::class)->only([
            'index', 'create', 'store'
        ]);

        // ❌ tidak boleh akses user & termin
    });
});
