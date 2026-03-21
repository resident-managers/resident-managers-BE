<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\TemporaryAbsence;

use App\Models\Resident;
use App\Models\TemporaryAbsence;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final readonly class Create
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): TemporaryAbsence
    {
        DB::beginTransaction();
        try {
            $resident = Resident::find($args['resident_id']);
            if (!$resident) {
                throw ValidationException::withMessages([
                    'resident_id' => ['Dân cư không tồn tại.'],
                ]);
            }

            $record = new TemporaryAbsence();
            $record->fill($args);
            $record->save();

            $resident->type = 'absent';
            $resident->save();

            DB::commit();
            return $record;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
