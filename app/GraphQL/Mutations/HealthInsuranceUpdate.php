<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\HealthInsurance;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final readonly class HealthInsuranceUpdate
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): HealthInsurance
    {
        DB::beginTransaction();
        try {
            $record = HealthInsurance::find($args['id']);
            if (!$record) {
                throw ValidationException::withMessages([
                    'id' => ['Thông tin bảo hiểm y tế không tồn tại.'],
                ]);
            }

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
