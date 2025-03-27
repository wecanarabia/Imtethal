<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum TaskStatusEnum:string implements HasLabel
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case OVERDUE = 'overdue';
    case REJECTED = 'rejected';
    case CANCELED = 'canceled';

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => __('views.PENDING'),
            self::IN_PROGRESS => __('views.IN_PROGRESS'),
            self::COMPLETED => __('views.COMPLETED'),
            self::OVERDUE => __('views.OVERDUE'),
            self::REJECTED => __('views.REJECTED_INCOMPLETE'),
            self::CANCELED => __('views.CANCELED'),
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
