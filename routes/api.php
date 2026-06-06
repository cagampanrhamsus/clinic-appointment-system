<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DoctorController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'role.doctor'])->group(function () {

    Route::get('/patients', [UserController::class, 'patients']);
    Route::get('/patients/{id}', [UserController::class, 'showPatient']);
    Route::post('/patients', [UserController::class, 'storePatient']);
    Route::put('/patients/{id}', [UserController::class, 'updatePatient']);
    Route::delete('/patients/{id}', [UserController::class, 'deletePatient']);
    Route::patch('/patients/{id}', [UserController::class, 'updatePatient']);

    Route::get('/doctors', [DoctorController::class, 'index']);

});