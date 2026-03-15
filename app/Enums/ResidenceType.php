<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ResidenceType extends Enum
{
    const PERMANENT = 'permanent'; // Thường trú
    const TEMPORARY = 'temporary'; // Tạm trú
    const ABSENT    = 'absent';    // Tạm vắng
    const MOVED_OUT = 'moved_out'; // Chuyển đi
}
