<?php

namespace App\Http\Controllers;

use App\Http\DTO\StatisticsResponse;
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
}
