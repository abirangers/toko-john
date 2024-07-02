<?php

use App\Http\Controllers\API\MediaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/media/bulk-store', [MediaController::class, 'bulkStore'])->name('api.media.bulkStore');
Route::apiResource('media', MediaController::class);