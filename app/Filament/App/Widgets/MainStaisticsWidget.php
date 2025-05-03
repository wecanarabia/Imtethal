<?php

namespace App\Filament\App\Widgets;

use Carbon\Carbon;
use Filament\Facades\Filament;
use App\Enums\DeliveryStatusEnum;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class MainStaisticsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $users = \App\Models\User::where('company_id', Filament::getTenant()->id)
            ->count();
        $tasks = \App\Models\Task::where('company_id', Filament::getTenant()->id)
            ->pluck('id')->toArray();
        $deliveries = \App\Models\TaskDelivery::whereIn('task_id', $tasks)->count();
        $departments = \App\Models\Department::where('company_id', Filament::getTenant()->id)->count();
        return [
            Stat::make(__('views.TOTAL_USERS'), $users)
                ->chart([7, 2, 10, 3, 15, 4, 17])->color('success'),
            Stat::make(__('views.TOTAL_TASKS'), $deliveries)
                ->chart([17, 16, 14, 15, 14, 13, 12])->color('success'),
            Stat::make(__('views.TOTAL_DEPARTMENTS'), $departments)
                ->chart([15, 4, 10, 2, 12, 4, 12])
                ->color('success'),
        ];
    }
}
