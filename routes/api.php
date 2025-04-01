<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CryptoController;

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


Route::get('/cryptos/all', [CryptoController::class, 'allCoins']);
Route::get('/cryptos/top', [CryptoController::class, 'topCoins']); //top market cap coin

Route::prefix('cryptos')->group(function () {
    Route::get('/', [CryptoController::class, 'index']); // Get all cryptos
    Route::get('/{symbol}', [CryptoController::class, 'show']); // Get details of a specific crypto
    Route::post('/fetch', [CryptoController::class, 'fetch']); // Fetch and update crypto data
    Route::get('/search', [CryptoController::class, 'search']); // Search cryptos
    Route::get('/{symbol}/chart', [CryptoController::class, 'chart']); // Historical price chart
});