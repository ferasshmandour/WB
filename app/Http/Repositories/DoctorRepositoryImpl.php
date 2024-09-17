<?php

namespace App\Http\Repositories;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Collection;

class DoctorRepositoryImpl implements DoctorRepository
{

    public function getAllDoctors(): Collection
    {
        return Doctor::all();
    }

    public function getDoctorById($id)
    {
        return Doctor::where('id', $id)->first();
    }

    public function getDoctorByName($name)
    {
        return Doctor::where('name', $name)->first();
    }

    public function getDoctorByLocation($location)
    {
        return Doctor::where('location', $location)->get();
    }

    public function getDoctorsBySpecialty($specialty)
    {
        return Doctor::where('specialty', $specialty)->get();
    }

    public function getDoctorsBySpecialtyAndLocation($specialty, $location)
    {
        return Doctor::where(['specialty' => $specialty, 'location' => $location])->get();
    }

    public function createDoctor(array $data)
    {
        return Doctor::create($data);
    }
}
