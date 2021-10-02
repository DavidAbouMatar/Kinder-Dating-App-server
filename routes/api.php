<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AdminController;

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
    Route::get('getUsers', [UserController::class, 'getUsers'])->name('api:getUsers');
    Route::get('getHobbies', [UserController::class, 'getHobbies'])->name('api:getHobbies');
    Route::get('getUserHobbies', [UserController::class, 'getUserHobbies'])->name('api:getUserHobbies');
    Route::post('edit_profile', [UserController::class, 'edit_profile'])->name('api:edit_profile');
	Route::post('register', [AuthController::class, 'register'])->name('api:register');
    Route::post('login', [AuthController::class, 'login'])->name('api:login');
    Route::any('logout', [AuthController::class, 'logout'])->name('api:logout');
    Route::post('refresh', [AuthController::class, 'refresh'])->name('api:refresh');


    Route::get('/search/{keyword}', [UserController::class, 'search'])->name('api:search');
    Route::post('/upload_image', [UserController::class, 'uploadImage'])->name('api:upload_image');
    Route::get('admin/images', [AdminController::class, 'getNoneApprovedImages'])->name('api:get_images');
    Route::post('admin/approve_images', [AdminController::class, 'approveImages'])->name('api:approve_images');


});

Route::group(['middleware' => 'auth.jwt'], function () {
	// Route::get('/search/{keyword}', [UserController::class, 'search'])->name('api:search');
	Route::get('/test', [UserController::class, 'test'])->name('api:test');
});
