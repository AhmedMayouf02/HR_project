<?php

namespace App\Enums;

enum EmploymentStatus: string
{
    case Active = 'active';
    case OnLeave = 'on_leave';
    case Suspended = 'suspended';
    case Terminated = 'terminated';

    /** @return list<string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
