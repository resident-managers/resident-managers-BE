<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\SocialInsurance;

use App\Models\SocialInsurance;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final readonly class Update
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): SocialInsurance
    {
        DB::beginTransaction();
        try {
            $record = SocialInsurance::find($args['id']);
            if (!$record) {
                throw ValidationException::withMessages([
                    'id' => ['Thông tin bảo hiểm xã hội không tồn tại.'],
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
