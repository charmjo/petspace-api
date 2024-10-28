<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// my custom controllers
use App\Http\Controllers\Auth\UserMobileController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PetController;
use Laravel\Fortify\RoutePath;


// unprotected routes:
Route::post(RoutePath::for('create-user','/create-user'), [UserMobileController::class, 'createUser']);

// now, how do I transfer this to another controller?. Ai waitttt... I can just 
Route::post('/get-token', [UserMobileController::class,'generateToken']);

// protected route
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/email/verification-notification', [EmailVerificationController::class,'resendNotification'])
        ->name('verification.send');
});

// i'll take care of security once I finish the crud. 
// also, i'll need to do a web version of this coz I need to know how to protect this.
Route::prefix('mobile/account')->middleware('auth:sanctum')->group(function () {
    Route::delete('/delete/{id}', [UserController::class, 'deleteUser']);
    Route::put('/update/{id}', [UserController::class, 'updateUser']);
    
});

// also, i'll need to do a web version of this coz I need to know how to protect this.
Route::prefix('/pet')->middleware('auth:sanctum')->group(function () {
   Route::post('/create', [PetController::class, 'create']);
   Route::delete('/delete/{id}', [PetController::class, 'delete']);
   Route::put('/update/{id}', [PetController::class, 'update']);

   Route::get('/pet-detail');
   Route::post('/pet-list', [PetController::class,'getList']);

   Route::get('/pet-detail/{id}',[PetController::class,'getDetail']);

});

Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class,'verify'] )->middleware(['signed'])->name('verification.verify');