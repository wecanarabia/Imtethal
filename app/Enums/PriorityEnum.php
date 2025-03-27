<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PriorityEnum:string implements HasLabel
{
    case HIGH = 'high';
    case MEDIUM = 'medium';
    case LOW = 'low';

    public function getLabel(): string
    {
        return match ($this) {
            self::HIGH => __('views.HIGH'),
            self::MEDIUM => __('views.MEDIUM'),
            self::LOW => __('views.LOW'),
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
