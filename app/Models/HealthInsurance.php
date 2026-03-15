<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthInsurance extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'resident_id',
        'code',
        'healthcare_facility',
        'issued_date',
        'expiry_date',
    ];

    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }
}
