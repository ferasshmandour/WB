<?php

namespace App\Http\Controllers;

use App\Http\DTO\DoctorResponse;
use App\Models\Doctor;
use App\Models\Location;
use App\Models\Specialty;
use Carbon\Carbon;
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
                'locationId' => 'required|exists:locations,id',
                'visitPrice' => 'required',
                'bio' => 'required',
                'workingDays' => 'required|array',
                'workingDays.*.day' => 'required|string',
                'workingDays.*.from' => 'required|date_format:h:i A',
                'workingDays.*.to' => 'required|date_format:h:i A|after:workingDays.*.from'
            ]);

            $doctor = Doctor::create([
                'name' => $validatedData['name'],
                'specialty_id' => $validatedData['specialtyId'],
                'location_id' => $validatedData['locationId'],
                'visit_price' => $validatedData['visitPrice'],
                'bio' => $validatedData['bio']
            ]);

            foreach ($validatedData['workingDays'] as $workingDay) {
                $fromTime = Carbon::createFromFormat('h:i A', $workingDay['from'])->format('H:i:s');
                $toTime = Carbon::createFromFormat('h:i A', $workingDay['to'])->format('H:i:s');

                $doctor->workingDays()->create([
                    'day' => $workingDay['day'],
                    'from' => $fromTime,
                    'to' => $toTime,
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

        $responseList = [];

        foreach ($doctors as $doctor) {
            $specialty = Specialty::where('id', $doctor->specialty_id)->first();
            $location = Location::where('id', $doctor->location_id)->first();
            $workingDays[] = $doctor->workingDays()->get();
            $doctorResponse = new DoctorResponse($doctor->name, $specialty->name, $location->address, $location->location_url, $doctor->visit_price, $doctor->bio, $workingDays);
            $responseList[] = $doctorResponse;
        }

        return response()->json($responseList, 200);
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

            if (!$doctor) {
                throw new \Exception('Doctor not found');
            }

            $specialty = Specialty::where('id', $doctor->specialty_id)->first();
            $location = Location::where('id', $doctor->location_id)->first();
            $workingDays[] = $doctor->workingDays()->get();

            $doctorResponse = new DoctorResponse($doctor->name, $specialty->name, $location->address, $location->location_url, $doctor->visit_price, $doctor->bio, $workingDays);
            return response()->json($doctorResponse, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 424);
        }
    }

}
