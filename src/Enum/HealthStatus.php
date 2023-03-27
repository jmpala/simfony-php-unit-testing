<?php

declare(strict_types=1);

namespace App\Enum;

enum HealthStatus: string
{
    case HEALTHY = 'Healthy';
    case SICK = 'Sick';
    case HUNGRY = 'Hungry';
}
