<?php

namespace App\Enums;

enum JustificationStatusEnum:string implements HasLabel
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => __('views.PENDING'),
            self::APPROVED => __('views.APPROVED'),
            self::REJECTED => __('views.REJECTED'),
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
