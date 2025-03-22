<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum DepartmentRoleEnum:string implements HasLabel
{
    case HEAD_OF_DEPARTMENT = 'head_of_department';
    case ADMIN = 'admin';
    case EMPLOYEE = 'employee';

    public function getLabel(): string
    {
        return match ($this) {
            self::HEAD_OF_DEPARTMENT => __('views.HEAD_OF_DEPARTMENT'),
            self::ADMIN => __('views.AN_ADMIN'),
            self::EMPLOYEE => __('views.EMPLOYEE'),
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
