<?php declare(strict_types=1);

namespace App\Support;

use App\Models\Household;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class HouseholdResidentGuard
{
	public static function assertNotInOtherHouseholds(array $residentIds, ?string $exceptHouseholdId = null): void
	{
		$residentIds = array_values(
			array_unique(
				array_filter($residentIds, static fn ($residentId) => !empty($residentId))
			)
		);

		if ($residentIds === []) {
			return;
		}

		$memberConflicts = DB::table('household_residents')
			->whereIn('resident_id', $residentIds)
			->when($exceptHouseholdId !== null, function ($query) use ($exceptHouseholdId) {
				$query->where('household_id', '!=', $exceptHouseholdId);
			})
			->pluck('resident_id')
			->all();

		$headConflicts = Household::query()
			->whereIn('resident_id', $residentIds)
			->when($exceptHouseholdId !== null, function ($query) use ($exceptHouseholdId) {
				$query->where('id', '!=', $exceptHouseholdId);
			})
			->pluck('resident_id')
			->all();

		$conflictResidentIds = array_values(array_unique(array_merge($memberConflicts, $headConflicts)));

		if ($conflictResidentIds === []) {
			return;
		}

		$conflictList = implode(', ', $conflictResidentIds);

		throw ValidationException::withMessages([
			'resident_id' => ["Cư dân đã thuộc hộ khác: {$conflictList}"],
			'members' => ["Danh sách thành viên chứa cư dân đã thuộc hộ khác: {$conflictList}"],
		]);
	}
}
