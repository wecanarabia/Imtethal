<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum DeliveryStatusEnum:string implements HasLabel
{
    case ON_TIME = 'on_time';
    case WITHIN_GRACE_PERIOD = 'within_grace_period';
    case DELAYED = 'delayed';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ON_TIME => __('views.ON_TIME'),
            self::WITHIN_GRACE_PERIOD => __('views.WITHIN_GRACE_PERIOD'),
            self::DELAYED => __('views.DELAYED_TIME'),
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
