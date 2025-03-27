<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum TaskTypeEnum:string implements HasLabel
{
    case RECURRING = 'routine';
    case NONE_RECURRING = 'none_recurring_task';

    public function getLabel(): string
    {
        return match ($this) {
            self::RECURRING => __('views.RECURRING_TASK'),
            self::NONE_RECURRING => __('views.NONE_RECURRING_TASK'),
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
