<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\MarkerController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MyFavoriteSubjectController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/', function () {
    return redirect()->route('weather.index');
});

// Blog routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');

// Protected blog routes
Route::middleware('auth')->group(function () {
    Route::get('/blog/create', [BlogController::class, 'create'])->name('blog.create');
    Route::post('/blog', [BlogController::class, 'store'])->name('blog.store');
    Route::get('/blog/{post}/edit', [BlogController::class, 'edit'])->name('blog.edit');
    Route::put('/blog/{post}', [BlogController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{post}', [BlogController::class, 'destroy'])->name('blog.delete');
    
    // Comment routes
    Route::post('/blog/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

// Public blog routes
Route::get('/blog/{post}', [BlogController::class, 'show'])->name('blog.show');

// Weather routes
Route::get('/weather', [WeatherController::class, 'index'])->name('weather.index');
Route::get('/weather/{location}', [WeatherController::class, 'show'])->name('weather.show');

// Map markers routes
Route::resource('markers', MarkerController::class);

// E-shop routes
Route::resource('products', ProductController::class);
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

// Favorite Subjects routes
Route::get('/favorite-subjects', [MyFavoriteSubjectController::class, 'indexView'])->name('favorite-subjects.index');
Route::get('/favorite-subjects/create', [MyFavoriteSubjectController::class, 'createView'])->name('favorite-subjects.create');
Route::get('/favorite-subjects/{myFavoriteSubject}/edit', [MyFavoriteSubjectController::class, 'editView'])->name('favorite-subjects.edit');
// API routes for favorite subjects
Route::prefix('api')->group(function () {
    Route::resource('favorite-subjects', MyFavoriteSubjectController::class);
});
