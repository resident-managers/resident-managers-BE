<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemporaryResidence extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'resident_id',
        'address',
        'host_name',
        'from_date',
        'to_date',
        'reason',
    ];

    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }
}
