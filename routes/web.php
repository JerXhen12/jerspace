<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\DistributorController;
use App\Http\Controllers\Admin\FlashSaleController;

// Guest Route 
Route::group(['middleware' => 'guest'], function() { 
    Route::get('/', function () { 
        return view('welcome'); 
    });

    Route::get('/register', [AuthController::class, 'register'])->name('register'); 
    Route::post('/post-register', [AuthController::class, 'post_register'])->name('post.register');

    Route::post('/post-login', [AuthController::class, 'login']);
    });

// Admin Route 
Route::group(['middleware' => 'admin'], function() { 
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Route untuk Produk
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('admin/product/detail/{id}', [ProductController::class, 'detail'])->name('product.detail');
    Route::get('/product.edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('product/update/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/product/delete/{id}', [ProductController::class, 'delete'])->name('product.delete');
    Route::get('/product', [ProductController::class, 'index'])->name('admin.product');

    // Route untuk Distributor
    Route::get('/distributors', [DistributorController::class, 'index'])->name('admin.distributor.index');
    Route::resource('admin/distributor', DistributorController::class);
    Route::get('/admin-logout', [AuthController::class, 'admin_logout'])->name('admin.logout');
    Route::get('/admin/distributor/{id}', [DistributorController::class, 'show'])->name('admin.distributor.show');
    

    // Route untuk Flash Sale
    Route::get('/flash-sale', [FlashSaleController::class, 'index'])->name('admin.flash_sale.index');
    Route::get('/flash-sale/create', [FlashSaleController::class, 'create'])->name('admin.flash_sale.create');
    Route::post('/flash-sale/store', [FlashSaleController::class, 'store'])->name('admin.flash_sale.store');
    Route::get('/flash-sale/{id}', [FlashSaleController::class, 'show'])->name('admin.flash_sale.show');
    Route::get('/flash-sale/{id}/edit', [FlashSaleController::class, 'edit'])->name('admin.flash_sale.edit');
    Route::put('/flash-sale/update/{id}', [FlashSaleController::class, 'update'])->name('admin.flash_sale.update');
    Route::delete('/flash-sale/{id}', [FlashSaleController::class, 'destroy'])->name('admin.flash_sale.delete');

    });

// User Route 
Route::group(['middleware' => ['web', 'auth']], function() { // Tambahkan 'auth' di sini
    Route::get('/user', [UserController::class, 'index'])->name('user.dashboard');
    Route::get('/user-logout', [AuthController::class, 'user_logout'])->name('user.logout');

    // Product route untuk User
    Route::get('/user.product/detail/{id}', [UserController::class, 'detail_product'])->middleware('auth')->name('user.detail.product');
    Route::get('/user-flash', [ProductController::class, 'flashSale'])->name('user.flash');

    // Route untuk pembelian produk
    Route::get('/product/purchase/{productId}/{userId}', [UserController::class, 'purchaseProduct'])->name('user.purchase.product');
});



