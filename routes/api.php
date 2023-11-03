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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

// Auth Routing

Route::post('/login', 'App\Http\Controllers\Api\LoginController@login');
Route::post('/logout', 'App\Http\Controllers\Api\LoginController@logout');
Route::get('/user', 'App\Http\Controllers\Api\LoginController@user');
// REST Routing dengan Laravel Passport
Route::middleware(['auth:api'])->group(function() {
    Route::apiResource('products', \App\Http\Controllers\Api\ProductController::class);
});