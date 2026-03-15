<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class SocialInsuranceStatus extends Enum
{
    const ACTIVE   = 'active';   // Đang đóng
    const RESERVED = 'reserved'; // Bảo lưu
    const PENSION  = 'pension';  // Hưởng lương hưu
    const STOPPED  = 'stopped';  // Dừng đóng
}
