<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Moniepoint POS Integration Routes
Route::post('/moniepoint/webhook', [App\Http\Controllers\MoniepointController::class, 'handleWebhook']);
Route::get('/moniepoint/check-transaction/{reference}', [App\Http\Controllers\MoniepointController::class, 'checkTransaction']);
Route::get('/moniepoint/poll-active-payment', [App\Http\Controllers\MoniepointController::class, 'pollActivePayment']);

