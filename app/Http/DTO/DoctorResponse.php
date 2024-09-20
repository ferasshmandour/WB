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

    public function __construct($name, $specialty, $address, $locationUrl, $visitPrice, $bio)
    {
        $this->name = $name;
        $this->specialty = $specialty;
        $this->address = $address;
        $this->locationUrl = $locationUrl;
        $this->visitPrice = $visitPrice;
        $this->bio = $bio;
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
        ];
    }
}
