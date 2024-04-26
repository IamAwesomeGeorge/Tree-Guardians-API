<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ImageController;
use App\Http\Controllers\api\LandingPageController;
use App\Http\Controllers\api\TreeController;

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
Route::get('tree/image', [ImageController::class, 'getTreeImages']);
Route::post('tree/image', [ImageController::class, 'storeTreeImages']);
Route::get('user/image', [ImageController::class, 'getUserImage']);
Route::post('user/image', [ImageController::class, 'storeUserImage']);
#Fails catch
Route::get('{any}', [LandingPageController::class, 'index'])->where('any', '.*');
Route::get('user', [LandingPageController::class, 'index'])->where('any', '.*');
Route::get('', [LandingPageController::class, 'index'])->where('any', '.*');