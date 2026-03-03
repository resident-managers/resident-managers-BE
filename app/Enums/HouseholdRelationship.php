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
	const SON = 'son';

	// Anh chị em
	const OLDER_BROTHER = 'older_brother'; // anh trai
	const OLDER_SISTER  = 'older_sister';  // chị gái
	const YOUNGER_SIBLING = 'younger_sibling'; // em (không phân giới tính)
}