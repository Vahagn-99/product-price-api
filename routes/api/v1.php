<?php

use App\Http\Controllers\PriceController;
use Illuminate\Support\Facades\Route;

// API v1
Route::prefix('v1')->group(function () {
    Route::get('/prices', PriceController::class);
});
