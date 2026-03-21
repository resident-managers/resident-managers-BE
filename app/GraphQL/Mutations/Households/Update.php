<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\Households;

use App\Enums\HouseholdRelationship;
use App\Models\Household;
use App\Support\HouseholdResidentGuard;
use Illuminate\Support\Facades\DB;

final readonly class Update
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        DB::beginTransaction();
	    try {
		    $household = Household::query()->findOrFail($args['id']);

		    $residentIds = [];
		    foreach ($args['members'] ?? [] as $member) {
			    $residentIds[] = $member['resident_id'];
		    }
		    HouseholdResidentGuard::assertNotInOtherHouseholds($residentIds, $household->id);

		    if (isset($args['code']))    $household->code    = $args['code'];
		    if (isset($args['address'])) $household->address = $args['address'];

		    $household->save();

		    if (isset($args['members'])) {
			    $members = [];

			    $members[$household->resident_id] = ['relationship' => HouseholdRelationship::HEAD];

			    foreach ($args['members'] as $member) {
				    if ($member['resident_id'] === $household->resident_id) continue;
				    if ($member['relationship'] === HouseholdRelationship::HEAD) continue;

				    $members[$member['resident_id']] = [
					    'relationship' => $member['relationship']
				    ];
			    }

			    $household->members()->sync($members);
		    }

		    DB::commit();
		    return $household;
	    } catch (\Exception $e) {
			DB::rollBack();
			throw $e;
	    }
    }
}
