<?php

namespace App\Http\Controllers;

use App\Http\DTO\DoctorResponse;
use App\Models\Doctor;
use App\Models\Location;
use App\Models\Specialty;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function createDoctor(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'specialtyId' => 'required',
                'locationId' => 'required',
                'visitPrice' => 'required',
                'bio' => 'required',
                'workingDays' => 'required|array',
                'workingDays.*.day' => 'required|string',
                'workingDays.*.from' => 'required|date_format:H:i',
                'workingDays.*.to' => 'required|date_format:H:i|after:workingDays.*.from'
            ]);

            $doctor = Doctor::create([
                'name' => $validatedData['name'],
                'specialty_id' => $validatedData['specialtyId'],
                'location_id' => $validatedData['locationId'],
                'visit_price' => $validatedData['visitPrice'],
                'bio' => $validatedData['bio']
            ]);

            foreach ($validatedData['workingDays'] as $workingDay) {
                $doctor->workingDays()->create([
                    'day' => $workingDay['day'],
                    'from' => $workingDay['from'],
                    'to' => $workingDay['to'],
                ]);
            }

            $response = [
                'message' => 'Doctor created successfully',
                'status' => 201
            ];

            return response()->json($response, 201);
        } catch (\Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'status' => 424
            ];
            return response()->json($response, 424);
        }
    }

    public function getAllDoctors(): JsonResponse
    {
        $doctors = Doctor::all();
        return response()->json($doctors, 200);
    }

    public function updateDoctor(Request $request, $id): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'specialty_id' => 'required',
                'location_id' => 'required',
                'address' => 'required',
                'visit_price' => 'required',
                'bio' => 'required'
            ]);

            $doctor = Doctor::where('id', $id)->first();
            $doctor->update([
                'name' => $validatedData['name'],
                'specialty_id' => $validatedData['specialty_id'],
                'location_id' => $validatedData['location_id'],
                'address' => $validatedData['address'],
                'visit_price' => $validatedData['visit_price'],
                'bio' => $validatedData['bio']
            ]);

            return response()->json('Doctor updated successfully', 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 424);
        }
    }

    public function deleteDoctor($id): JsonResponse
    {
        $doctor = Doctor::where('id', $id)->first();
        $doctor->delete();
        return response()->json('Doctor deleted successfully', 200);
    }

    public function getDoctorById($id): JsonResponse
    {
        try {
            $doctor = Doctor::where('id', $id)->first();
            $doctorSpecialty = Specialty::where('id', $doctor->specialty_id)->first();
            $specialtyName = $doctorSpecialty->name;

            $doctorLocation = Location::where('id', $doctor->location_id)->first();
            $address = $doctorLocation->address;
            $locationUrl = $doctorLocation->location_url;

            $doctorResponse = new DoctorResponse($doctor->name, $specialtyName, $address, $locationUrl, $doctor->visit_price, $doctor->bio);
            return response()->json($doctorResponse, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 424);
        }
    }

}
