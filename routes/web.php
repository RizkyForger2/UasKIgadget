<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

// Public Routes (Landing Page untuk Customer)
Route::get('/', [CustomerController::class, 'index'])->name('home');
Route::get('/shop', [CustomerController::class, 'shop'])->name('shop');
Route::get('/shop/{product}', [CustomerController::class, 'show'])->name('shop.show');

// Auth Routes (sudah di-generate oleh Breeze)
require __DIR__.'/auth.php';

// Admin Routes (hanya admin yang bisa akses)
Route::middleware(['auth', 'admin'])->group(function () {
    // Dashboard Admin
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Products Management
    Route::resource('products', ProductController::class);
    
    // Categories Management
    Route::resource('categories', CategoryController::class);
    
    // Orders Management
    Route::resource('orders', OrderController::class);
    
    // Reviews Management
    Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::patch('reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Customer Routes (hanya customer yang bisa akses)
Route::middleware(['auth', 'customer'])->group(function () {
    // Customer Dashboard
    Route::get('/customer/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
    
    // Cart
    Route::get('/cart', [CustomerController::class, 'cart'])->name('cart.index');
    Route::post('/cart/add/{product}', [CustomerController::class, 'addToCart'])->name('cart.add');
    Route::patch('/cart/update/{id}', [CustomerController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CustomerController::class, 'removeFromCart'])->name('cart.remove');
    
    // Checkout
    Route::get('/checkout', [CustomerController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [CustomerController::class, 'placeOrder'])->name('checkout.store');
    
    // My Orders
    Route::get('/my-orders', [CustomerController::class, 'myOrders'])->name('customer.orders');
    Route::get('/my-orders/{order}', [CustomerController::class, 'orderDetail'])->name('customer.orders.show');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Customer Routes
Route::middleware(['auth', 'customer'])->group(function () {
    // ... routes yang sudah ada ...
    
    // Payment Routes
    Route::get('/payment/{order}', [CustomerController::class, 'payment'])->name('customer.payment');
    Route::get('/payment/{order}/success', [CustomerController::class, 'paymentSuccess'])->name('customer.payment.success');
    Route::get('/payment/{order}/pending', [CustomerController::class, 'paymentPending'])->name('customer.payment.pending');
    Route::post('/payment/notification', [CustomerController::class, 'paymentNotification'])->name('customer.payment.notification');
    Route::post('/payment/{order}/confirm', [CustomerController::class, 'confirmPayment'])->name('customer.payment.confirm');
    
});
});

// Public Review (bisa tanpa login)
Route::post('products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');