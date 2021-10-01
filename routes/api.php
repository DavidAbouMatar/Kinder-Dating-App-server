<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
	Route::post('register', [AuthController::class, 'register'])->name('api:register');
    Route::post('login', [AuthController::class, 'login'])->name('api:login');
    Route::any('logout', [AuthController::class, 'logout'])->name('api:logout');
    Route::post('refresh', [AuthController::class, 'refresh'])->name('api:refresh');
});


Route::get('/highlighted', [UserController::class, 'highlighted'])->name('api:highlighted');

Route::group(['middleware' => 'auth.jwt'], function () {
	Route::get('/search/{keyword}', [UserController::class, 'search'])->name('api:search');
	Route::get('/test', [UserController::class, 'test'])->name('api:test');
});
