<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

	private function buildSearchQuery(Builder $query,?string $search): Builder
	{
		if (!empty($search)) {
			$query->where(function ($query) use ($search) {
				$query->where('full_name', 'like', "%$search%")
					->orWhere('national_id', 'like', "%$search%")
					->orWhere('phone', 'like', "%$search%");
//					->orWhere('email', 'like', "%$search%")
//					->orWhere('id', 'like', "%$search%");
			});
		}


		return $query;
	}

	public function scopeSearch(Builder $query,?string $search): Builder
	{
		return $this->buildSearchQuery($query, $search);
	}

	public function scopeAvailableForHousehold(Builder $query, ?string $householdId = null): Builder
	{
		return $query
			->whereNotIn('residents.id', function ($subQuery) use ($householdId) {
				$subQuery->select('resident_id')
					->from('household_residents')
					->when($householdId !== null, function ($query) use ($householdId) {
						$query->where('household_id', '!=', $householdId);
					});
			})
			->whereNotIn('residents.id', function ($subQuery) use ($householdId) {
				$subQuery->select('resident_id')
					->from('households')
					->when($householdId !== null, function ($query) use ($householdId) {
						$query->where('id', '!=', $householdId);
					});
			});
	}
}
