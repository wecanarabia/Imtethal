<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum UserTypeEnum:string implements HasLabel
{
    case USER = 'user';
    case ADMIN = 'admin';

    public function getLabel(): string
    {
        return match ($this) {
            self::USER => __('views.USER'),
            self::ADMIN => __('views.ADMIN'),
        };
    }

    public static function labels(): array
    {
        return array_combine(
            array_column(self::cases(), 'value'),
            array_map(fn($case) => $case->getLabel(), self::cases())
        );
    }

}


