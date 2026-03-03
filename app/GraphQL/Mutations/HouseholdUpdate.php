<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\HouseholdRelationship;
use App\Models\Household;
use App\Support\HouseholdResidentGuard;
use Illuminate\Support\Facades\DB;

final readonly class HouseholdUpdate
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

		    // Cập nhật thông tin hộ
		    if (isset($args['code']))    $household->code    = $args['code'];
		    if (isset($args['address'])) $household->address = $args['address'];

		    $household->save();

		    // Nếu có truyền members thì sync lại toàn bộ
		    if (isset($args['members'])) {
			    $members = [];

			    // Giữ lại chủ hộ hiện tại, không cho thay đổi
			    $members[$household->resident_id] = ['relationship' => HouseholdRelationship::HEAD];

			    foreach ($args['members'] as $member) {
				    // Bỏ qua nếu trùng với chủ hộ
				    if ($member['resident_id'] === $household->resident_id) continue;
				    // Bỏ qua nếu cố tình gán relationship HEAD cho thành viên khác
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
