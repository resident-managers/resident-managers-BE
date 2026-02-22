<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\HouseholdRelationship;
use App\Models\Household;
use Illuminate\Support\Facades\DB;

final readonly class HouseholdCreate
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args): Household
    {
        DB::beginTransaction();
	    try {
		    $household = new Household();
		    $household->code        = $args['code'] ?? null;
		    $household->resident_id = $args['resident_id']; // chủ hộ
		    $household->address     = $args['address'];
		    $household->save();

		    // Gán chủ hộ vào household_residents với relationship HEAD
		    $members = [
			    $args['resident_id'] => ['relationship' => HouseholdRelationship::HEAD]
		    ];

		    // Gán thêm các thành viên khác nếu có
		    foreach ($args['members'] ?? [] as $member) {
			    // Bỏ qua nếu trùng chủ hộ
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
