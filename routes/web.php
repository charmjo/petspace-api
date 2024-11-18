<?php

use App\Http\Controllers\Account\MemberController;
use App\Http\Controllers\Account\UserController;
use App\Http\Controllers\Appointment\AppointmentController;
use App\Http\Controllers\Pet\PetController;
use App\Http\Controllers\Pet\PetDocuRecordsController;
use App\Http\Controllers\Pet\PetHealthController;
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

Route::prefix('web/pet')->middleware('auth:sanctum')
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

//WILL IMPLEMENT ONCE FUNCTIONALITIES ARE DONE - email verification
 //Route::get('api/email/verify/{id}/{hash}', [EmailVerificationController::class,'verify'] )->middleware(['signed'])->name('verification.verify-web');
