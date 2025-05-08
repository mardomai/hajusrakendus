<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\WeatherController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Monsters API endpoint
Route::get('/monsters', [ApiController::class, 'getMonsters']);

// External API endpoints
Route::get('/weather/{location}', [WeatherController::class, 'show']);

// Stripe API endpoints
Route::prefix('stripe')->group(function () {
    Route::get('/balance', [ApiController::class, 'getStripeBalance']);
    Route::get('/transactions', [ApiController::class, 'getStripeTransactions']);
}); 