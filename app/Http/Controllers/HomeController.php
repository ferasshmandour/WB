<?php

namespace App\Http\Controllers;

use App\Http\DTO\SpecialtyAndLocationResponse;
use App\Http\DTO\StatisticsResponse;
use App\Models\Location;
use App\Models\Specialty;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function statistics(): JsonResponse
    {
        $doctors = DB::table('doctors')->count();
        $visits = DB::table('statistics')->max('visits');
        $specialties = DB::table('statistics')->max('specialties');
        $appointments = DB::table('statistics')->max('appointments');

        $statisticsResponse = new StatisticsResponse($doctors, $visits, $specialties, $appointments);
        return response()->json($statisticsResponse, 200);
    }

    public function getAllSpecialtiesAndLocations(): JsonResponse
    {
        try {
            $specialties = Specialty::all();
            $locations = Location::all();

            $statisticsAndLocationsResponse = new SpecialtyAndLocationResponse($specialties->toArray(), $locations->toArray());
            return response()->json($statisticsAndLocationsResponse, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 424);
        }
    }

    public function search(Request $request): JsonResponse
    {
        $location = $request->query('location');
        $specialty = $request->query('specialty');

        $doctors = null;
        if (!$location && !$specialty) {
            $doctors = DB::table('doctors')
                ->join('locations', 'doctors.location_id', '=', 'locations.id')
                ->join('specialties', 'doctors.specialty_id', '=', 'specialties.id')
                ->select(['doctors.id', 'doctors.name', 'specialties.name as specialty', 'locations.address as address', 'doctors.visit_price as visitPrice', 'doctors.bio', 'doctors.photo'])
                ->get();
        }

        if ($location && !$specialty) {
            $doctors = DB::table('doctors')
                ->join('locations', 'doctors.location_id', '=', 'locations.id')
                ->join('specialties', 'doctors.specialty_id', '=', 'specialties.id')
                ->select(['doctors.id', 'doctors.name', 'specialties.name as specialty', 'locations.address as address', 'doctors.visit_price as visitPrice', 'doctors.bio', 'doctors.photo'])
                ->where('locations.address', 'like', '%' . $location . '%')
                ->get();
        }

        if (!$location && $specialty) {
            $doctors = DB::table('doctors')
                ->join('locations', 'doctors.location_id', '=', 'locations.id')
                ->join('specialties', 'doctors.specialty_id', '=', 'specialties.id')
                ->select(['doctors.id', 'doctors.name', 'specialties.name as specialty', 'locations.address as address', 'doctors.visit_price as visitPrice', 'doctors.bio', 'doctors.photo'])
                ->where('specialties.name', 'like', '%' . $specialty . '%')
                ->get();
        }

        if ($location && $specialty) {
            $doctors = DB::table('doctors')
                ->join('specialties', 'doctors.specialty_id', '=', 'specialties.id')
                ->join('locations', 'doctors.location_id', '=', 'locations.id')
                ->select(['doctors.id', 'doctors.name', 'specialties.name as specialty', 'locations.address as address', 'doctors.visit_price as visitPrice', 'doctors.bio', 'doctors.photo'])
                ->where('specialties.name', 'like', '%' . $specialty . '%')
                ->where('locations.address', 'like', '%' . $location . '%')
                ->get();
        }

        return response()->json($doctors, 200);
    }
}
