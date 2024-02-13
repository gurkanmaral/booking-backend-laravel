<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HouseController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/register', [AuthController::class, 'register']);

Route::apiResource('/houses', HouseController::class)->only([ 'store','show']);

Route::apiResource('/bookings',BookingController::class)->only([ 'store']);;

Route::get('/houses', [HouseController::class, 'index']);

Route::get('/search',[HouseController::class, 'search']);

Route::apiResource('/user-details',UserController::class)->only(['show']);

Route::get('/chats', [ChatController::class,'index'])->middleware('auth:sanctum');

Route::get('/chats/{user}', [ChatController::class, 'show'])->middleware('auth:sanctum');

Route::post('/chats', [ChatController::class, 'store'])->middleware('auth:sanctum');

Route::get('/user/{id}', [UserController::class, 'getOneUser']);








