<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddDoctorRequest;
use App\Http\Services\DoctorService;
use Illuminate\Http\JsonResponse;


class DoctorController extends Controller
{
    public function __construct(protected DoctorService $doctorService)
    {
    }

    public function getAllDoctors(): JsonResponse
    {
        try {
            $doctors = $this->doctorService->getAllDoctors();
            return response()->json($doctors, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch doctors',
                'message' => $e->getMessage(),
            ], 424);
        }

    }

    public function createDoctor(AddDoctorRequest $addDoctorRequest): JsonResponse
    {
        try {
            $this->doctorService->createDoctor($addDoctorRequest);
            return response()->json("Doctor added successfully", 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to add doctor',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

}
