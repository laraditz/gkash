<?php

use Illuminate\Support\Facades\Route;
use Laraditz\Gkash\Http\Controllers\GkashController;

Route::get('/pay/{payment:code}', [GkashController::class, 'pay'])->name('gkash.pay');
Route::match(['get', 'post'], '/complete', [GkashController::class, 'complete'])->name('gkash.complete');
Route::post('/backend', [GkashController::class, 'backend'])->name('gkash.backend');
