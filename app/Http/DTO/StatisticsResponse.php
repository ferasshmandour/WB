<?php

namespace App\Http\DTO;

class StatisticsResponse implements \JsonSerializable
{
    private ?int $doctors;
    private ?int $visits;
    private ?int $specialties;
    private ?int $appointments;

    public function __construct($doctors, $visits, $specialties, $appointments)
    {
        $this->doctors = $doctors;
        $this->visits = $visits;
        $this->specialties = $specialties;
        $this->appointments = $appointments;
    }

    public function jsonSerialize(): array
    {
        return [
            'doctors' => $this->doctors,
            'visits' => $this->visits,
            'specialties' => $this->specialties,
            'appointments' => $this->appointments,
        ];
    }
}
