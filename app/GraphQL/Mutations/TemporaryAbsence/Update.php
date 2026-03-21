<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\TemporaryAbsence;

use App\Models\TemporaryAbsence;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final readonly class Update
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): TemporaryAbsence
    {
        DB::beginTransaction();
        try {
            $record = TemporaryAbsence::find($args['id']);
            if (!$record) {
                throw ValidationException::withMessages([
                    'id' => ['Bản ghi tạm vắng không tồn tại.'],
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
