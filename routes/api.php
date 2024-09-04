<?php

use Illuminate\Support\Facades\Route;

Route::post('login', [\App\Http\Controllers\Api\LoginController::class, 'authenticate'])->name('login');

Route::prefix('domain')->middleware(['auth:sanctum'])->group(function () {

    Route::post('store', [\App\Http\Controllers\Api\DomainController::class, 'store']);

});
