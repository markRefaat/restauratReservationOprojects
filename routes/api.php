<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('checkAvailability', [App\Http\Controllers\ManageReservationController::class, 'checkAvailability']);
Route::post('reserveTable', [App\Http\Controllers\ManageReservationController::class, 'reserveTable']);
Route::get('menu', [App\Http\Controllers\MenuController::class, 'menu']);
Route::post('placeOrder', [App\Http\Controllers\OrderController::class, 'placeOrder']);
Route::post('checkout', [App\Http\Controllers\CheckoutController::class, 'checkout']);
