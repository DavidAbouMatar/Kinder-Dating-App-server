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
Route::post('register', [AuthController::class, 'register'])->name('api:register');
Route::post('login', [AuthController::class, 'login'])->name('api:login');
Route::get('/highlighted', [UserController::class, 'highlighted'])->name('api:highlighted');
Route::get('admin/get_msgs', [AdminController::class, 'getNonApprovedMsgs'])->name('api:admin_get_msgs');
Route::post('admin/approve_msg', [AdminController::class, 'approveMsg'])->name('api:admin_approve_msg');
Route::post('admin/reject_msg', [AdminController::class, 'rejectMsg'])->name('api:admin_reject_msg');
Route::get('admin/get_imgs', [AdminController::class, 'getNonApprovedImages'])->name('api:get_images');
Route::post('admin/approve_img', [AdminController::class, 'approveImage'])->name('api:admin_approve_image');
Route::post('admin/reject_img', [AdminController::class, 'rejectImage'])->name('api:admin_reject_image');

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('getUsers', [UserController::class, 'getUsers'])->name('api:getUsers');
    Route::get('getHobbies', [UserController::class, 'getHobbies'])->name('api:getHobbies');
    Route::get('getUserHobbies', [UserController::class, 'getUserHobbies'])->name('api:getUserHobbies');
    Route::post('edit_profile', [UserController::class, 'edit_profile'])->name('api:edit_profile');
    Route::get('/search/{keyword}', [UserController::class, 'search'])->name('api:search');
    Route::post('/upload_image', [UserController::class, 'uploadImage'])->name('api:upload_image');
    Route::post('add_to_favorites', [UserController::class, 'addToFavorites'])->name('api:add_to_favorites');
    Route::post('send_msg', [UserController::class, 'sendMsg'])->name('api:send_msg');
    Route::get('get_msgs', [UserController::class, 'getMsgs'])->name('api:get_msgs');
    Route::post('block_user', [UserController::class, 'blockUser'])->name('api:block_user');
});
