<?php

namespace App\Filament\App\Widgets;

use Filament\Facades\Filament;
use App\Enums\DeliveryStatusEnum;
use Filament\Widgets\ChartWidget;

class DeliveriesWidget extends ChartWidget
{
protected static ?string $maxHeight = '210px';
    public function getHeading(): string
    {
        return __('views.TASK_DELIVERIES');
    }

    protected function getData(): array
    {

        $tasks = \App\Models\Task::where('company_id', Filament::getTenant()->id)
            ->pluck('id')->toArray();
        foreach (DeliveryStatusEnum::labels() as $key => $priority) {       
            $data[] = \App\Models\TaskDelivery::whereIn('task_id', $tasks)
                ->where('delivery_status', $key)
                ->count();
            $labels[] = $priority;
        }
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => ['#10B981', '#F59E0B', '#EF4444'], 
                ]
            ]
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
