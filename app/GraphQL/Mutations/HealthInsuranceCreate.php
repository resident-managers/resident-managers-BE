<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\HealthInsurance;
use App\Models\Resident;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final readonly class HealthInsuranceCreate
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): HealthInsurance
    {
        DB::beginTransaction();
        try {
            if (!Resident::find($args['resident_id'])) {
                throw ValidationException::withMessages([
                    'resident_id' => ['Dân cư không tồn tại.'],
                ]);
            }

            if (HealthInsurance::where('resident_id', $args['resident_id'])->exists()) {
                throw ValidationException::withMessages([
                    'resident_id' => ['Dân cư đã có thông tin bảo hiểm y tế.'],
                ]);
            }

            $record = new HealthInsurance();
            $record->fill($args);
            $record->save();

            DB::commit();
            return $record;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
