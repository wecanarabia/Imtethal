<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum NotificationEnum:string implements HasLabel
{
    case EMAIL = 'email';
    case WHATSAPP = 'whatsapp';
    case BOTH = 'both';

    public function getLabel(): string
    {
        return match ($this) {
            self::EMAIL => __('views.EMAIL'),
            self::WHATSAPP => __('views.WHATSAPP'),
            self::BOTH => __('views.BOTH'),
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
