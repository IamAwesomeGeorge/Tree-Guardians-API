<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\LandingPageController;
use App\Http\Controllers\api\TreeController;
use App\Http\Controllers\api\TreeImageController;
use App\Http\Controllers\api\UserImageController;


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


Route::get('tree', [TreeController::class, 'index']);
Route::post('tree', [TreeController::class, 'store']);
Route::get('tree/image', [TreeImageController::class, 'index']);
Route::post('tree/image', [TreeImageController::class, 'store']);
Route::get('user/image', [UserImageController::class, 'index']);
Route::post('user/image', [UserImageController::class, 'store']);
#Fails catch
Route::get('{any}', [LandingPageController::class, 'index'])->where('any', '.*');
Route::get('user', [LandingPageController::class, 'index'])->where('any', '.*');
Route::get('', [LandingPageController::class, 'index'])->where('any', '.*');