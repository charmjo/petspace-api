<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PetController;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth:web')->get('/users', function () {
    return User::all();
});

// protected route
Route::middleware('auth:sanctum')->group(function () {
    Route::get('api/user', function (Request $request) {
        return $request->user();
    });
});

// account management
Route::prefix('web/account')->middleware('auth:sanctum')->group(function () {
    Route::delete('/delete/{id}', [UserController::class, 'deleteUser']);
    Route::put('/update/{id}', [UserController::class, 'updateUser']);

    // member management
    Route::get('/member-list', [UserController::class, 'getAllMembers']);
    Route::post('/member/add', [UserController::class, 'addMember']);
    Route::delete('/member/delete/{id}', [UserController::class, 'removeMember']);
});

// pet management
Route::prefix('web/pet')->middleware('auth:sanctum')->group(function () {
    Route::post('/create', [PetController::class, 'create']);
    Route::delete('/delete/{id}', [PetController::class, 'delete']);
    Route::put('/update/{id}', [PetController::class, 'update']);
 
    Route::get('/pet-detail');
    Route::post('/pet-list', [PetController::class,'getList']);
 
    Route::get('/pet-detail/{id}',[PetController::class,'getDetail']);
 
 });

//WILL IMPLEMENT ONCE FUNCTIONALITIES ARE DONE - email verification
 //Route::get('api/email/verify/{id}/{hash}', [EmailVerificationController::class,'verify'] )->middleware(['signed'])->name('verification.verify-web');