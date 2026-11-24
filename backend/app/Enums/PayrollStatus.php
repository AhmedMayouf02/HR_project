<?php

namespace App\Enums;

enum PayrollStatus: string
{
    case Draft = 'draft';
    case Approved = 'approved';
    case Paid = 'paid';

    /** @return list<string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
