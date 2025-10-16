<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super-admin';
    case ADMIN = 'admin';
    case USER = 'user';

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }

}