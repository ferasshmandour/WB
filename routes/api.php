<?php

use App\Http\Controllers\DoctorController;
use Illuminate\Support\Facades\Route;

Route::post('/createDoctor', [DoctorController::class, 'createDoctor']);
Route::get('/getAllDoctors', [DoctorController::class, 'getAllDoctors']);
Route::put('/updateDoctor/{id}', [DoctorController::class, 'updateDoctor']);
Route::delete('/deleteDoctor/{id}', [DoctorController::class, 'deleteDoctor']);


