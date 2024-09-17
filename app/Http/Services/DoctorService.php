<?php

namespace App\Http\Services;

use App\Http\Requests\AddDoctorRequest;

interface DoctorService
{
    public function getAllDoctors();

    public function createDoctor(AddDoctorRequest $addDoctorRequest);
}
