<?php

namespace App\Http\Repositories;

use App\Models\Doctor;

interface DoctorRepository
{
    public function getAllDoctors();

    public function getDoctorById($id);

    public function getDoctorByName($name);

    public function getDoctorByLocation($location);

    public function getDoctorsBySpecialty($specialty);

    public function getDoctorsBySpecialtyAndLocation($specialty, $location);

    public function createDoctor(array $data);
}
