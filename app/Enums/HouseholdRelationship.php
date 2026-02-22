<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class HouseholdRelationship extends Enum
{
	const HEAD        = 'head';
	const SPOUSE      = 'spouse';
	const CHILD       = 'child';
}