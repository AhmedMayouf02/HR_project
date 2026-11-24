<?php

namespace App\Enums;

enum DocumentType: string
{
    case NationalId = 'national_id';
    case Passport = 'passport';
    case Contract = 'contract';
    case Certificate = 'certificate';
    case Other = 'other';

    /** @return list<string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
