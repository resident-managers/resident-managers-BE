<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

	private function buildSearchQuery(Builder $query,?string $search): Builder
	{
		if (!empty($search)) {
			$query->where(function ($query) use ($search) {
				$query->where('code', 'like', "%$search%")
					->orWhere('address', 'like', "%$search%")
					->orWhereHas('head', function (Builder $headQuery) use ($search) {
						$headQuery->where('full_name', 'like', "%$search%");
					});
			});
		}


		return $query;
	}

	public function scopeSearch(Builder $query,?string $search): Builder
	{
		return $this->buildSearchQuery($query, $search);
	}
}
