<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JWTController;

Route::group(['middleware'=>'api'],function($router){
Route::post('/register',[JWTController::class,'register']);
Route::get('/index',[JWTController::class,'index']);
Route::post('/login',[JWTController::class,'login']);
Route::post('/logout',[JWTController::class,'logout']);
Route::post('/refresh',[JWTController::class,'refresh']);
Route::post('/profile',[JWTController::class,'profile']);
Route::post('/contact',[JWTController::class,'contact']);
});

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */
