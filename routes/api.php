<?php

use App\Http\Controllers\Account\MemberController;
use App\Http\Controllers\Account\UserController;
use App\Http\Controllers\Appointment\AppointmentController;
use App\Http\Controllers\Auth\UserMobileController;
use App\Http\Controllers\Pet\PetController;
use App\Http\Controllers\Pet\PetDocuRecordsController;
use App\Http\Controllers\Pet\PetHealthController;
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

// pet record management
Route::prefix('pet-record')->middleware('auth:sanctum')
    ->controller(PetDocuRecordsController::class)
    ->group(
    function () {
        Route::post('/upload', 'create');
        Route::get('/list/{id}', 'getList');
        Route::delete('/delete/{id}', 'delete');
    });

// pet health
Route::prefix('pet')->middleware('auth:sanctum')
    ->controller(PetHealthController::class)
    ->group(
        function () {
            // pet allergy
            Route::get('/allergen-dictionary', 'getAllergenDictionary');
            Route::post('/{petId}/allergy/add/{allergenId}', 'addPetAllergen');
            Route::get('/{id}/allergies', 'getPetAllergenList');
            Route::delete('/{petId}/allergy/remove/{allergenId}', 'removePetAllergen');

            // pet weight
            Route::post('/{petId}/weight/update/{weight}', 'updateWeight');
            Route::get('/{petId}/weight/latest', 'getLatestWeight');
            Route::get('/{petId}/weight/history-list', 'getWeightHistory');

            // special conditions
            Route::post('/{petId}/special-cond/update/{conditionId}', 'updateSpecialCondition');
            Route::post('/{petId}/special-cond/add', 'addSpecialCondition');
            Route::get('/{petId}/special-cond/latest', 'getSpecialConditionLatest');
            Route::get('/{petId}/special-cond/list', 'getSpecialConditionList');
            Route::delete('/{petId}/special-cond/{conditionId}', 'removeSpecialCondition');
        });
//WILL IMPLEMENT ONCE FUNCTIONALITIES ARE DONE - email verification
// Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class,'verify'] )
//     ->middleware(['signed'])
//     ->name('verification.verify');
Route::prefix('pet/{petId}/appointment')->middleware('auth:sanctum')
    ->controller(AppointmentController::class)
    ->group(
        function () {
            // pet allergy
            Route::get('/veterinarians', 'getAvailableVeterinarian');
            Route::get('/vet-schedule/{vetId}', 'getAvailableSchedule');
            Route::post('/create-appointment', 'createAppointment');
            Route::get('/list', 'getAppointmentList');
            Route::get('/detail/{appointmentId}', 'getAppointment');
            Route::post('/reschedule/{id}', 'rescheduleAppointment');
            Route::post('/cancel/{appointmentId}', 'cancelAppointment');
        });

Route::get('/storage/{path}', function ($path) {
    return response()->file(storage_path('app/public/' . $path));
})->where('path', '.*');
