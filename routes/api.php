<?php

use App\Http\Controllers\AccountTransactionsController;
use App\Http\Controllers\AccountTransferController;
use App\Http\Controllers\UserAccountsController;
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

Route::get('/user/{user:id}/accounts', [UserAccountsController::class, 'index']);
Route::get('/account/{account:id}/transactions', [AccountTransactionsController::class, 'index']);
Route::post('/transfer', AccountTransferController::class);