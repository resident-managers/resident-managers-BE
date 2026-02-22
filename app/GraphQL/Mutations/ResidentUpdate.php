<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Resident;
use Illuminate\Support\Facades\DB;

final readonly class ResidentUpdate
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
	    DB::beginTransaction();
	    try {
		    $resident = Resident::query()->findOrFail($args['id']);
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
