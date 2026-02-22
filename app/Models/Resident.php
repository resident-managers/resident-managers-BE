<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resident extends Model
{
    use HasFactory, HasUuids;

	//'full_name', 'gender', 'ngay_sinh', 'so_dien_thoai', 'cccd', 'dia_chi', 'nghe_nghiep', 'dan_toc', 'ton_giao', 'trinh_do_hv', 'ghi_chu',
	protected $fillable = [
		'full_name',
		'gender',
		'date_of_birth',
		'phone',
		'national_id',
		'address',
		'occupation',
		'ethnicity',
		'religion',
		'education_level',
		'note',
	];


    /**
     * Households where this person is the householder (chu_ho).
     */
    public function isHead(): HasMany
    {
        return $this->hasMany(Household::class, 'resident_id');
    }

    /**
     * Alias for GraphQL field `householdAsHead`.
     */
    public function householdAsHead(): HasMany
    {
        return $this->isHead();
    }
	public function getRelationshipAttribute(): ?string
	{
		return $this->pivot?->relationship ?? null;
	}

    /**
     * Households where this person is a member.
     */
    public function household(): BelongsToMany
    {
        return $this->belongsToMany(Household::class, 'household_residents', 'resident_id', 'household_id')
                    ->withPivot('relationship')
                    ->withTimestamps();
    }

//    /**
//     * Alias for GraphQL field `household`.
//     */
//    public function household(): BelongsToMany
//    {
//        return $this->houseHold();
//    }
}
