<?php

namespace App\Http\DTO;

class DoctorResponse implements \JsonSerializable
{
    private ?int $id;
    private ?string $name;
    private ?string $specialty;
    private ?string $address;
    private ?string $locationUrl;
    private ?string $visitPrice;
    private ?string $bio;
    private array $workingDays;
    private array $devices;
    private ?string $photo;
    private array $media;

    public function __construct($id, $name, $specialty, $address, $locationUrl, $visitPrice, $bio, $workingDays, $devices, $photo, $media)
    {
        $this->id = $id;
        $this->name = $name;
        $this->specialty = $specialty;
        $this->address = $address;
        $this->locationUrl = $locationUrl;
        $this->visitPrice = $visitPrice;
        $this->bio = $bio;
        $this->workingDays = $workingDays;
        $this->devices = $devices;
        $this->photo = $photo;
        $this->media = $media;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'specialty' => $this->specialty,
            'address' => $this->address,
            'locationUrl' => $this->locationUrl,
            'visitPrice' => $this->visitPrice,
            'bio' => $this->bio,
            'workingDays' => $this->workingDays,
            'devices' => $this->devices,
            'photo' => $this->photo,
            'media' => $this->media
        ];
    }
}
