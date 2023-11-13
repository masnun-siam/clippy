<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClipController;
use App\Http\Controllers\LoginController;

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


Route::post('/login', [LoginController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('/user', [LoginController::class, 'user']);
    Route::get('/clips', [ClipController::class, 'index']);
    Route::post('/clip', [ClipController::class, 'saveClip']);
    Route::put('/clip/{id}', [ClipController::class, 'editClip']);
    Route::get('/clip/{slug}', [ClipController::class, 'getClip']);
    Route::delete('/clip/{id}', [ClipController::class, 'deleteClip']);
});
