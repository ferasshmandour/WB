<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'doctors';

    protected $fillable = [
        'name',
        'specialty_id',
        'location_id',
        'address',
        'visit_price',
        'bio',
        'photo'
    ];

    public function workingDays(): HasMany
    {
        return $this->hasMany(WorkingDay::class);
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }
}
