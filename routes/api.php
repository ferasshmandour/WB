<?php

use App\Http\Controllers\DoctorController;
use Illuminate\Support\Facades\Route;

Route::get('/getAllDoctors', [DoctorController::class, 'getAllDoctors']);
Route::post('/createDoctor', [DoctorController::class, 'createDoctor']);
