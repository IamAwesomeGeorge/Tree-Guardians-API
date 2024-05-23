<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ChangeLogController;
use App\Http\Controllers\api\LandingPageController;
use App\Http\Controllers\api\SpeciesController;
use App\Http\Controllers\api\TreeController;
use App\Http\Controllers\api\TreeImageController;
use App\Http\Controllers\api\UserController;
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

# Change Log Controller
Route::get('changelog', [ChangeLogController::class, 'index']);
Route::post('changelog', [ChangeLogController::class, 'store']);
Route::put('changelog', [ChangeLogController::class, 'update']);

# Tree Controller
Route::get('tree', [TreeController::class, 'index']);
Route::post('tree', [TreeController::class, 'store']);
Route::put('tree', [TreeController::class, 'update']);
Route::delete('tree', [TreeController::class, 'delete']);

Route::get('tree/image', [TreeImageController::class, 'index']);
Route::post('tree/image', [TreeImageController::class, 'store']);

# User Controller
Route::post('user/newUser', [UserController::class, 'newUser']);
Route::post('user/logIn', [UserController::class, 'logIn']);

Route::get('user/image', [UserImageController::class, 'index']);
Route::post('user/image', [UserImageController::class, 'store']);

# Species Controller
Route::get('species', [SpeciesController::class, 'index']);
Route::post('species', [SpeciesController::class, 'store']);

#Fails catch
Route::get('{any}', [LandingPageController::class, 'index'])->where('any', '.*');
Route::get('user', [LandingPageController::class, 'index'])->where('any', '.*');
Route::get('', [LandingPageController::class, 'index'])->where('any', '.*');
