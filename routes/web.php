<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// Главная страница
Route::get('/', [ProductController::class, 'index'])->name('home');

// Товары
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Категории
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');


// Корзина
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart/remove/{variationId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout')->middleware('auth');

// Аутентификация
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Профиль
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::post('/', [ProfileController::class, 'update'])->name('update');
    Route::get('/orders', [ProfileController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [ProfileController::class, 'showOrder'])->name('order');
});

// Отзывы
Route::post('/products/{productId}/reviews', [ReviewController::class, 'store'])
    ->name('reviews.store')
    ->middleware('auth');

// О нас
Route::get('/about', function () {
    return view('about');
})->name('about');
