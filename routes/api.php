<?php

use App\Http\Controllers\DoctorController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::post('/createDoctor', [DoctorController::class, 'createDoctor']);
Route::get('/getAllDoctors', [DoctorController::class, 'getAllDoctors']);
Route::get('/getDoctorById/{id}', [DoctorController::class, 'getDoctorById']);
Route::put('/updateDoctor/{id}', [DoctorController::class, 'updateDoctor']);
Route::delete('/deleteDoctor/{id}', [DoctorController::class, 'deleteDoctor']);
Route::get('/getMediaByDoctorId/{doctorId}', [DoctorController::class, 'getMediaByDoctorId']);

Route::get('/statistics', [HomeController::class, 'statistics']);
Route::get('/search', [HomeController::class, 'search']);
Route::get('/getAllSpecialtiesAndLocations', [HomeController::class, 'getAllSpecialtiesAndLocations']);
