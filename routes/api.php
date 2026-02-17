<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;

// Public: Anyone can see products
Route::get('/products', [ProductController::class, 'index']);

// Protected: Must be logged in
Route::middleware('auth:sanctum')->group(function () {
    
    // Regular User: Can place orders
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/my-orders', [OrderController::class, 'index']);

    // Admin Only: Protect Product Management
    Route::middleware('admin')->group(function () {
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);
        
        // Admin can also see ALL orders
        Route::get('/admin/orders', [OrderController::class, 'allOrders']);
    });
});