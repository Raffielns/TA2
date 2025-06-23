<?php

use App\Http\Controllers\AdminNotificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\MidtransWebhookController;

// 🔐 Auth Routes
Auth::routes();

// 🌐 Public Pages
Route::get('/', function () {
    $products = Product::latest()->get();
    $allCategories = Category::all();
    return view('welcome', compact('products', 'allCategories'));
})->name('landing');

Route::get('/aboutus', fn() => view('aboutus'))->name('aboutus');

// 🛍 Catalog for all (not inside /admin!)
Route::get('/catalog', [ProductController::class, 'catalogView'])->name('catalog');
Route::get('/catalog/produk/{slug}', [ProductController::class, 'catalogByCategory'])->name('catalog.byCategory');
Route::get('/catalog/{id}', [ProductController::class, 'show'])->name('catalog.detail');


// 👤 Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// 👤 Profile Routes (jika digunakan)
Route::middleware('auth')->group(function () {
    Route::get('/profile', 'ProfileController@index')->name('profile');
    Route::put('/profile', 'ProfileController@update')->name('profile.update');
});

// 🛠 ADMIN Routes (role = 1)
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/', fn() => view('adminMenu.dashboard'))->name('admin.dashboard');
    Route::get('/home', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/user', fn() => view('adminMenu.user'))->name('user');

    // Master Data
    Route::resource('products', ProductController::class);

    Route::resource('category', CategoryController::class);
    Route::get('/admin/category/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('/admin/category/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/admin/category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

    Route::resource('transaction', TransaksiController::class);
    Route::resource('materials', MaterialController::class)->except(['show']);

    // Order & Payment Management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('/orders/{order}/update-status', [AdminOrderController::class, 'update'])->name('admin.orders.update');

    // Route::get('/payments', [AdminPaymentController::class, 'index'])->name('admin.payments.index');
    // Route::patch('/payments/{payment}/verify', [AdminPaymentController::class, 'verify'])->name('admin.payments.verify');

    // Ulasan
    Route::resource('reviewAdmin', ReviewController::class);
});

// 🧾 CUSTOMER Routes (role = 0)
Route::middleware(['auth', 'role:0'])->group(function () {
    Route::get('/dashboard', fn() => view('userMenu.index'))->name('user.dashboard');

    // 🛒 Order
    Route::prefix('user')->group(function () {
        Route::get('/orderSaya', [OrderController::class, 'index'])->name('customer.order');
        Route::post('/orderSaya', [OrderController::class, 'store'])->name('customer.order.store');

        Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
        Route::get('/checkout/success', [OrderController::class, 'success'])->name('checkout.success');

        Route::post('/order/submit', [OrderController::class, 'submit'])->name('order.submit');
        Route::get('/order/success/{order}', [OrderController::class, 'success'])->name('order.success');

        Route::get('/statusOrder', [OrderController::class, 'status'])->name('order.status');
        Route::get('/statusOrder/detail/{order}', [OrderController::class, 'showDetail'])->name('order.detail');

        Route::get('/historyOrder', [OrderController::class, 'history'])->name('order.history');

        Route::get('/reviewSaya', [OrderController::class, 'review'])->name('order.review');

        Route::get('/orders/{order}/review/{product}', [OrderController::class, 'showReviewForm'])
            ->name('user.reviews.create');
        Route::post('/orders/{order}/review/{product}', [OrderController::class, 'submitReview'])
            ->name('user.reviews.submit');
        Route::get('/orders/{order}/view-review/{product}', [OrderController::class, 'showReview'])
            ->name('user.reviews.show');

        Route::get('/statusOrder/{orderId}', [OrderController::class, 'verifyPayment'])->name('order.verify');
        Route::post('/user/handlePaymentSuccess', [OrderController::class, 'handlePaymentSuccess'])
            ->name('payment.success');
    });

    // 🛒 Cart
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('customer.addToCart');
    Route::get('/cart', [CartController::class, 'viewCart'])->name('customer.order');
    Route::put('/cart/{id}', [CartController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
});
