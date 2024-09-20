<?php

namespace App\Http\DTO;

class DoctorResponse implements \JsonSerializable
{
    private ?string $name;
    private ?string $specialty;
    private ?string $address;
    private ?string $locationUrl;
    private ?string $visitPrice;
    private ?string $bio;
    private array $workingDays;

    public function __construct($name, $specialty, $address, $locationUrl, $visitPrice, $bio, $workingDays)
    {
        $this->name = $name;
        $this->specialty = $specialty;
        $this->address = $address;
        $this->locationUrl = $locationUrl;
        $this->visitPrice = $visitPrice;
        $this->bio = $bio;
        $this->workingDays = $workingDays;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'specialty' => $this->specialty,
            'address' => $this->address,
            'locationUrl' => $this->locationUrl,
            'visitPrice' => $this->visitPrice,
            'bio' => $this->bio,
            'workingDays' => $this->workingDays,
        ];
    }
}
