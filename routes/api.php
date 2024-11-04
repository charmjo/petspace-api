<?php

use App\Http\Controllers\Account\MemberController;
use App\Http\Controllers\Account\UserController;
use App\Http\Controllers\Auth\UserMobileController;
use App\Http\Controllers\Pet\PetController;
use App\Models\Pet\PetDocuRecords;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\RoutePath;


// my custom controllers


// unprotected routes:
Route::post(RoutePath::for('create-user','/create-user'), [UserMobileController::class, 'createUser']);

Route::post('/get-token', [UserMobileController::class,'generateToken']);

// protected route
Route::middleware('auth:sanctum')->group(function () {
    //WILL IMPLEMENT ONCE FUNCTIONALITIES ARE DONE - email verification
    // Route::post('/email/verification-notification', [EmailVerificationController::class,'resendNotification'])
    //     ->name('verification.send');
});

// account management
Route::prefix('account')->middleware('auth:sanctum')
    ->controller(UserController::class)
    ->group(
    function () {
        Route::get('/user', 'getUser');
        Route::delete('/delete/{id}', 'deleteUser');
        Route::post('/update', 'updateUser');
        Route::post('/change-avatar', 'changeAvatar');

    });

// member management
Route::prefix('account/member')->middleware('auth:sanctum')
    ->controller(MemberController::class)
    ->group(
    function () {
        Route::post('/add', 'addMember');
        Route::delete('/delete/{id}', 'removeMember');
        Route::get('/member-list', 'getAllMembers');
    });

// pet management
Route::prefix('pet')->middleware('auth:sanctum')
    ->controller(PetController::class)
    ->group(
    function () {
       Route::post('/create', 'create');
       Route::delete('/delete/{id}', 'delete');
       Route::post('/update', 'update');
       Route::post('/change-avatar', 'changeAvatar');
       Route::get('/pet-list', 'getList');
       Route::get('/pet-detail/{id}', 'getDetail');
    });

// pet management
Route::prefix('pet/record')->middleware('auth:sanctum')
    ->controller(PetDocuRecords::class)
    ->group(
    function () {
        Route::post('/upload', 'create');
    });

//WILL IMPLEMENT ONCE FUNCTIONALITIES ARE DONE - email verification
// Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class,'verify'] )
//     ->middleware(['signed'])
//     ->name('verification.verify');

