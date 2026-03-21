<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\Households;

use App\Enums\HouseholdRelationship;
use App\Models\Household;
use App\Support\HouseholdResidentGuard;
use Illuminate\Support\Facades\DB;

final readonly class Create
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args): Household
    {
        DB::beginTransaction();
	    try {
		    $residentIds = [];
		    foreach ($args['members'] ?? [] as $member) {
			    $residentIds[] = $member['resident_id'];
		    }
		    HouseholdResidentGuard::assertNotInOtherHouseholds($residentIds);

		    $household = new Household();
		    $household->code        = $args['code'] ?? null;
		    $household->resident_id = $args['resident_id'];
		    $household->address     = $args['address'];
		    $household->save();

		    $members = [
			    $args['resident_id'] => ['relationship' => HouseholdRelationship::HEAD]
		    ];

		    foreach ($args['members'] ?? [] as $member) {
			    if ($member['resident_id'] === $args['resident_id']) continue;

			    $members[$member['resident_id']] = [
				    'relationship' => $member['relationship']
			    ];
		    }

		    $household->members()->syncWithoutDetaching($members);

		    DB::commit();
		    return $household;
	    } catch (\Exception $e) {
			DB::rollBack();
			throw $e;
	    }
    }
}
