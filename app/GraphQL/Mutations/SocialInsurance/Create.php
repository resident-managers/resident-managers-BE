<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\SocialInsurance;

use App\Models\Resident;
use App\Models\SocialInsurance;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final readonly class Create
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): SocialInsurance
    {
        DB::beginTransaction();
        try {
            if (!Resident::find($args['resident_id'])) {
                throw ValidationException::withMessages([
                    'resident_id' => ['Dân cư không tồn tại.'],
                ]);
            }

            if (SocialInsurance::where('resident_id', $args['resident_id'])->exists()) {
                throw ValidationException::withMessages([
                    'resident_id' => ['Dân cư đã có thông tin bảo hiểm xã hội.'],
                ]);
            }

            $record = new SocialInsurance();
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
