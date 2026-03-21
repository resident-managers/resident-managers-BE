<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\HealthInsurance;

use App\Models\HealthInsurance;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final readonly class Update
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

            $record->fill(collect($args)->except('id')->toArray());
            $record->save();

            DB::commit();
            return $record;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
