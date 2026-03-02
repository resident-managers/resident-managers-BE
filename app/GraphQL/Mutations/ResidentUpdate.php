<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Resident;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final readonly class ResidentUpdate
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
	    DB::beginTransaction();
	    try {
		    $resident = Resident::query()->findOrFail($args['id']);

		    if (array_key_exists('national_id', $args)) {
			    $nationalId = trim((string) $args['national_id']);
			    $args['national_id'] = $nationalId === '' ? null : $nationalId;

			    if ($args['national_id'] !== null) {
				    $exists = Resident::query()
					    ->where('national_id', $args['national_id'])
					    ->where('id', '!=', $resident->id)
					    ->exists();

				    if ($exists) {
					    throw ValidationException::withMessages([
						    'national_id' => ['Số CCCD/CMND đã tồn tại.'],
					    ]);
				    }
			    }
		    }

		    $resident->fill($args);
		    $resident->save();
		    DB::commit();
		    return $resident;
	    } catch (\Exception $e) {
		    DB::rollBack();
		    throw $e;
	    }
    }
}
