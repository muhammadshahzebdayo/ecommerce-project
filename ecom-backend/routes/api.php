<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ProductController;

Route::get('/categories', [ProductController::class, 'categories']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);
Route::post('/checkout', [ProductController::class, 'checkout']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
