<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\TemporaryResidence;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final readonly class TemporaryResidenceUpdate
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): TemporaryResidence
    {
        DB::beginTransaction();
        try {
            $record = TemporaryResidence::find($args['id']);
            if (!$record) {
                throw ValidationException::withMessages([
                    'id' => ['Bản ghi tạm trú không tồn tại.'],
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
