<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class InsuranceType extends Enum
{
    const COMPULSORY = 'compulsory'; // Bắt buộc
    const VOLUNTARY  = 'voluntary';  // Tự nguyện
}
