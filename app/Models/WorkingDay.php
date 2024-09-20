<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkingDay extends Model
{
    use HasFactory;

    protected $table = 'working_days';
    protected $fillable = [
        'day',
        'from',
        'to',
        'doctor_id'
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}
