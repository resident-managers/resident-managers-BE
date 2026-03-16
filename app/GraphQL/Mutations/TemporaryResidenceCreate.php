<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Resident;
use App\Models\TemporaryResidence;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final readonly class TemporaryResidenceCreate
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): TemporaryResidence
    {
        DB::beginTransaction();
        try {
            $args['resident_id'] = $args['residentId'];
            unset($args['residentId']);

            $resident = Resident::find($args['resident_id']);
            if (!$resident) {
                throw ValidationException::withMessages([
                    'resident_id' => ['Dân cư không tồn tại.'],
                ]);
            }

            $record = new TemporaryResidence();
            $record->fill($args);
            $record->save();

            $resident->type = 'temporary';
            $resident->save();

            DB::commit();
            return $record;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
