<?php

namespace App\Http\DTO;

class SpecialtyAndLocationResponse implements \JsonSerializable
{

    private array $specialties;
    private array $locations;

    public function __construct($specialties, $locations)
    {
        $this->specialties = $specialties;
        $this->locations = $locations;
    }

    public function jsonSerialize(): array
    {
        return [
            'specialties' => $this->specialties,
            'locations' => $this->locations,
        ];
    }
}
