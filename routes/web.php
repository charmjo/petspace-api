<?php

use App\Http\Controllers\Account\MemberController;
use App\Http\Controllers\Account\UserController;
use App\Http\Controllers\Pet\PetController;
use App\Http\Controllers\Pet\PetDocuRecordsController;
use Illuminate\Support\Facades\Route;

// protected routes

// account management
Route::prefix('web/account')
    ->controller(UserController::class)
    ->middleware('auth:sanctum')
    ->group(
        function () {
            Route::get('/user', 'getUser');
            Route::delete('/delete/{id}', 'deleteUser');
            Route::post('/update', 'updateUser');
            Route::post('/change-avatar', 'changeAvatar');
        });

// member management
Route::prefix('web/account/member')
    ->controller(MemberController::class)
    ->middleware('auth:sanctum')
    ->group(
        function () {
            Route::get('/member-list', 'getAllMembers');
            Route::post('/add', 'addMember');
            Route::delete('/delete/{id}', 'removeMember');
        });

// pet management
Route::prefix('web/pet')
    ->middleware('auth:sanctum')
    ->group(
        function () {
            Route::post('/create', [PetController::class, 'create']);
            Route::delete('/delete/{id}', [PetController::class, 'delete']);
            Route::post('/update', [PetController::class, 'update']);
            Route::post('/change-avatar', [PetController::class, 'changeAvatar']);
            Route::get('/pet-list', [PetController::class,'getList']);
            Route::get('/pet-detail/{id}',[PetController::class,'getDetail']);
        });

// pet record management
Route::prefix('web/pet-record')->middleware('auth:sanctum')
    ->controller(PetDocuRecordsController::class)
    ->group(
        function () {
            Route::post('/upload', 'create');
            Route::get('/list/{id}', 'getList');
        });

//WILL IMPLEMENT ONCE FUNCTIONALITIES ARE DONE - email verification
 //Route::get('api/email/verify/{id}/{hash}', [EmailVerificationController::class,'verify'] )->middleware(['signed'])->name('verification.verify-web');
