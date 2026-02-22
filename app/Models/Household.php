<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Household extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'code',
        'resident_id', //id chủ hộ
        'address',
    ];

    /**
     * The householder (chu_ho).
     */
    public function head(): BelongsTo
    {
        return $this->belongsTo(Resident::class, 'resident_id');
    }

    /**
     * Members of this household.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Resident::class, 'household_residents', 'household_id', 'resident_id')
                    ->withPivot('relationship')
                    ->withTimestamps();
    }
}
