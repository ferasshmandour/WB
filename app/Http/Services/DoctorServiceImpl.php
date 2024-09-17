<?php

namespace App\Http\Services;

use App\Http\Repositories\DoctorRepository;
use App\Http\Requests\AddDoctorRequest;

class DoctorServiceImpl implements DoctorService
{
    public function __construct(protected DoctorRepository $doctorRepository)
    {
    }

    public function getAllDoctors()
    {
        return $this->doctorRepository->getAllDoctors();
    }

    public function createDoctor(AddDoctorRequest $addDoctorRequest)
    {
        $addDoctorRequest = $addDoctorRequest->validated();
        return $this->doctorRepository->createDoctor((array)$addDoctorRequest);
    }
}
