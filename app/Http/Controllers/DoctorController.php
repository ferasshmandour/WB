<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function createDoctor(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'specialty_id' => 'required',
            'location_id' => 'required',
            'address' => 'required',
            'visit_price' => 'required',
            'bio' => 'required'
        ]);

        Doctor::create([
            'name' => $validatedData['name'],
            'specialty_id' => $validatedData['specialty_id'],
            'location_id' => $validatedData['location_id'],
            'address' => $validatedData['address'],
            'visit_price' => $validatedData['visit_price'],
            'bio' => $validatedData['bio']
        ]);

        return response()->json('Doctor created successfully!', 201);
    }

    public function getAllDoctors()
    {
        $doctors = Doctor::all();
        return response()->json($doctors, 200);
    }

    public function updateDoctor(Request $request, $id)
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

    public function deleteDoctor($id)
    {
        $doctor = Doctor::where('id', $id)->first();
        $doctor->delete();
        return response()->json('Doctor deleted successfully', 200);
    }

}
