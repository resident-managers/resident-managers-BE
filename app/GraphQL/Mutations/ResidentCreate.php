<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Resident;
use Illuminate\Support\Facades\DB;

final readonly class ResidentCreate
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args): Resident
    {
        DB::beginTransaction();
	    try {
			$resident = new Resident();
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
