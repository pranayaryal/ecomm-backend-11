<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/products', [ProductController::class, 'getAllProducts']);
Route::get('/product/{id}', [ProductController::class, 'getProduct']);

//Route::post('/add-to-cart', [CartController::class, 'addToCart']);
Route::post('/increase-cart', [CartController::class, 'increaseCart']);
Route::post('/set-cart-quantity', [CartController::class, 'setQuantity']);
Route::post('/decrease-cart', [CartController::class, 'decreaseCart']);
Route::get('/cart', [CartController::class, 'getCartItems']);
Route::get('/forget-cart', [CartController::class, 'forgetCart']);
Route::get('/token', function (Request $request) {
    $token = $request->session()->token();
    return response()->json(['token' => $token]);
});

Route::post('remove-item', [CartController::class,'removeCartItem']);
Route::post('personal', [CartController::class,'personal']);
Route::get('personal', [CartController::class,'getPersonal']);
Route::post('address', [CartController::class,'saveAddress']);
Route::get('address', [CartController::class,'getAddress']);
Route::get('session-flush', function (Request $request) {
    Session::flush();
    return response()->json(['message' => 'Session was flushed']);
});
