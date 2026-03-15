<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Household;
use App\Models\Resident;
use App\Models\TemporaryAbsence;
use App\Models\TemporaryResidence;

final readonly class Statistics
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): array
    {
        $byGender = Resident::query()
            ->selectRaw('gender, COUNT(*) as count')
            ->groupBy('gender')
            ->pluck('count', 'gender')
            ->toArray();

        $byResidenceType = Resident::query()
            ->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        $activeTemporaryResidences = TemporaryResidence::query()
            ->where('from_date', '<=', now())
            ->where(fn ($q) => $q->whereNull('to_date')->orWhere('to_date', '>=', now()))
            ->count();

        $activeTemporaryAbsences = TemporaryAbsence::query()
            ->where('from_date', '<=', now())
            ->where(fn ($q) => $q->whereNull('to_date')->orWhere('to_date', '>=', now()))
            ->count();

        return [
            'total_residents'             => Resident::count(),
            'total_households'            => Household::count(),
            'male_count'                  => $byGender['MALE']      ?? 0,
            'female_count'                => $byGender['FEMALE']    ?? 0,
            'permanent_count'             => $byResidenceType['permanent']  ?? 0,
            'temporary_count'             => $byResidenceType['temporary']  ?? 0,
            'absent_count'                => $byResidenceType['absent']     ?? 0,
            'moved_out_count'             => $byResidenceType['moved_out']  ?? 0,
            'active_temporary_residences' => $activeTemporaryResidences,
            'active_temporary_absences'   => $activeTemporaryAbsences,
        ];
    }
}
