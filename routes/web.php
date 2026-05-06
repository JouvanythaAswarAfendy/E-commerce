<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MidtransController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/api/search', [ProductController::class, 'searchApi'])->name('api.search');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// Semua route yang butuh login
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Checkout routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/direct', [CheckoutController::class, 'direct'])->name('checkout.direct');
    Route::get('/checkout/direct', function() { return redirect()->route('products.index')->with('error', 'Silakan pilih produk terlebih dahulu.'); });
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');


    // Pesanan
    Route::get('/orders/{orderId}', [OrderController::class, 'show'])
        ->name('orders.show');
    Route::get('/orders/{orderId}/invoice', [OrderController::class, 'invoice'])
        ->name('orders.invoice');
    Route::get('/orders/{orderId}/pay', [OrderController::class, 'pay'])
        ->name('orders.pay');
    Route::post('/orders/{orderId}/cancel', [OrderController::class, 'cancel'])
        ->name('orders.cancel');
    Route::post('/orders/{orderId}/confirm-received', [OrderController::class, 'confirmReceived'])
        ->name('orders.confirm_received');



    // Profile (bawaan Breeze, jangan dihapus)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifications
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::get('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');

});

// Midtrans Callback (Outside Auth & CSRF)
Route::post('/midtrans/callback', [MidtransController::class, 'notificationHandler']);
Route::get('/midtrans/finish', [MidtransController::class, 'finish'])->name('midtrans.finish');


require __DIR__.'/auth.php';