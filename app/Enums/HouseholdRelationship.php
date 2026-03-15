<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class HouseholdRelationship extends Enum
{
	// Chủ hộ
	const HEAD = 'head';

	// Vợ chồng
	const HUSBAND = 'husband';
	const WIFE = 'wife';

	// Cha mẹ
	const FATHER = 'father';
	const MOTHER = 'mother';

	// Con
	const CHILD = 'child';

	// Anh chị em
	const OLDER_BROTHER   = 'older_brother';   // anh trai
	const OLDER_SISTER    = 'older_sister';    // chị gái
	const YOUNGER_SIBLING = 'younger_sibling'; // em (không phân giới tính)

	// Ông bà
	const GRANDFATHER = 'grandfather'; // ông
	const GRANDMOTHER = 'grandmother'; // bà

	// Cháu
	const GRANDCHILD = 'grandchild'; // cháu (nội/ngoại)

	// Chú bác cô dì
	const UNCLE = 'uncle'; // chú/bác/cậu
	const AUNT  = 'aunt';  // cô/dì/thím

	// Cháu gọi bằng chú/bác/cô/dì
	const NEPHEW_NIECE = 'nephew_niece';

	// Khác
	const OTHER = 'other';
}
