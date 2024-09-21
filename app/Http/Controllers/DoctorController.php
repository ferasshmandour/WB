<?php

namespace App\Http\Controllers;

use App\Http\DTO\DoctorResponse;
use App\Models\Doctor;
use App\Models\Location;
use App\Models\Media;
use App\Models\Specialty;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DoctorController extends Controller
{
    public function createDoctor(Request $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'specialtyId' => 'required|exists:specialties,id',
                'locationId' => 'required|exists:locations,id',
                'visitPrice' => 'required|numeric',
                'bio' => 'required|string',
                'workingDays' => 'required|array',
                'workingDays.*.day' => 'required|string',
                'workingDays.*.from' => 'required|date_format:h:i A',
                'workingDays.*.to' => 'required|date_format:h:i A|after:workingDays.*.from',
                'devices' => 'required|array',
                'devices.*.name' => 'required|string',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'media' => 'nullable|array',
                'media.*' => 'file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:10240'
            ]);

            $profilePhotoPath = null;
            if ($request->hasFile('photo')) {
                $profilePhoto = $request->file('photo');
                $profilePhotoPath = $profilePhoto->store('photo', 'public');
            }

            $doctor = Doctor::create([
                'name' => $validatedData['name'],
                'specialty_id' => $validatedData['specialtyId'],
                'location_id' => $validatedData['locationId'],
                'visit_price' => $validatedData['visitPrice'],
                'bio' => $validatedData['bio'],
                'photo' => $profilePhotoPath
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

            foreach ($validatedData['devices'] as $device) {
                $doctor->devices()->create([
                    'name' => $device['name'],
                    'doctor_id' => $doctor->id
                ]);
            }

            if ($request->hasFile('media')) {
                foreach ($request->file('media') as $mediaFile) {
                    $mediaFilePath = $mediaFile->store('media', 'public');

                    Media::create([
                        'doctor_id' => $doctor->id,
                        'path' => $mediaFilePath,
                        'type' => $mediaFile->getClientMimeType()
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Doctor created successfully',
                'status' => 201
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error creating doctor: ' . $e->getMessage());

            return response()->json([
                'message' => 'There was an error creating the doctor. Please try again later.',
                'status' => 424
            ], 424);
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
            $devices[] = $doctor->devices()->get();
            $media[] = $doctor->media()->get();

            $doctorResponse = new DoctorResponse($doctor->id, $doctor->name, $specialty->name, $location->address, $location->location_url, $doctor->visit_price, $doctor->bio, $workingDays, $devices, $doctor->photo, $media);
            $responseList[] = $doctorResponse;
        }

        return response()->json($responseList, 200);
    }

    public function updateDoctor(Request $request, $id): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'specialtyId' => 'required',
                'locationId' => 'required|exists:locations,id',
                'visitPrice' => 'required',
                'bio' => 'required',
                'workingDays' => 'sometimes|array',
                'workingDays.*.day' => 'required_with:workingDays|string',
                'workingDays.*.from' => 'required_with:workingDays|date_format:h:i A',
                'workingDays.*.to' => 'required_with:workingDays|date_format:h:i A|after:workingDays.*.from',
                'devices' => 'sometimes|array',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'media' => 'nullable|array',
                'media.*' => 'file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:10240'
            ]);

            $doctor = Doctor::findOrFail($id);

            if ($request->hasFile('photo')) {
                // Delete old photo if it exists
                if ($doctor->photo) {
                    Storage::disk('public')->delete($doctor->photo);
                }

                $profilePhoto = $request->file('photo');
                $profilePhotoPath = $profilePhoto->store('photo', 'public');
                $doctor->photo = $profilePhotoPath;
            }

            $doctor->update([
                'name' => $validatedData['name'],
                'specialty_id' => $validatedData['specialtyId'],
                'location_id' => $validatedData['locationId'],
                'visit_price' => $validatedData['visitPrice'],
                'bio' => $validatedData['bio']
            ]);

            if (isset($validatedData['workingDays'])) {
                $doctor->workingDays()->delete();

                foreach ($validatedData['workingDays'] as $workingDay) {
                    $fromTime = Carbon::createFromFormat('h:i A', $workingDay['from'])->format('H:i:s');
                    $toTime = Carbon::createFromFormat('h:i A', $workingDay['to'])->format('H:i:s');

                    $doctor->workingDays()->create([
                        'day' => $workingDay['day'],
                        'from' => $fromTime,
                        'to' => $toTime,
                    ]);
                }
            }

            if (isset($validatedData['devices'])) {
                $doctor->devices()->delete();

                foreach ($validatedData['devices'] as $device) {
                    $doctor->devices()->create([
                        'name' => $device['name'],
                        'doctor_id' => $doctor->id
                    ]);
                }
            }

            if ($request->hasFile('media')) {
                $doctor->media()->delete();

                foreach ($request->file('media') as $mediaFile) {
                    $mediaFilePath = $mediaFile->store('media', 'public');

                    Media::create([
                        'doctor_id' => $doctor->id,
                        'path' => $mediaFilePath,
                        'type' => $mediaFile->getClientMimeType()
                    ]);
                }
            }

            $response = [
                'message' => 'Doctor updated successfully',
                'status' => 200
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'status' => 424
            ];
            return response()->json($response, 424);
        }
    }

    public function deleteDoctor($id): JsonResponse
    {
        try {
            $doctor = Doctor::where('id', $id)->firstOrFail();

            $mediaFiles = Media::where('doctor_id', $doctor->id)->get();

            foreach ($mediaFiles as $media) {
                if (Storage::disk('public')->exists($media->path)) {
                    Storage::disk('public')->delete($media->path);
                }

                $media->delete();
            }

            if ($doctor->photo && Storage::disk('public')->exists($doctor->photo)) {
                Storage::disk('public')->delete($doctor->photo);
            }

            $doctor->delete();

            return response()->json('Doctor deleted successfully', 200);
        } catch (\Exception $e) {
            Log::error('Error deleting doctor: ' . $e->getMessage());

            return response()->json([
                'message' => 'There was an error deleting the doctor. Please try again later.',
                'status' => 424
            ], 424);
        }
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
            $devices[] = $doctor->devices()->get();
            $media[] = $doctor->media()->get();

            $doctorResponse = new DoctorResponse($doctor->id, $doctor->name, $specialty->name, $location->address, $location->location_url, $doctor->visit_price, $doctor->bio, $workingDays, $devices, $doctor->photo, $media);
            return response()->json($doctorResponse, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 424);
        }
    }

}
