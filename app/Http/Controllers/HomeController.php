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

        $sql = null;
        if (!$location && !$specialty) {
            $sql = DB::table('doctors')->get();
        }

        if ($location && !$specialty) {
            $sql = DB::table('doctors')
                ->select('doctors.*')
                ->join('locations', 'doctors.location_id', '=', 'locations.id')
                ->where('locations.address', 'like', '%' . $location . '%')
                ->get();
        }

        if (!$location && $specialty) {
            $sql = DB::table('doctors')
                ->select('doctors.*')
                ->join('specialties', 'doctors.specialty_id', '=', 'specialties.id')
                ->where('specialties.name', 'like', '%' . $specialty . '%')
                ->get();
        }

        if ($location && $specialty) {
            $sql = DB::table('doctors')
                ->select('doctors.*')
                ->join('specialties', 'doctors.specialty_id', '=', 'specialties.id')
                ->join('locations', 'doctors.location_id', '=', 'locations.id')
                ->where('specialties.name', 'like', '%' . $specialty . '%')
                ->where('locations.address', 'like', '%' . $location . '%')
                ->get();
        }

        return response()->json($sql, 200);
    }
}
