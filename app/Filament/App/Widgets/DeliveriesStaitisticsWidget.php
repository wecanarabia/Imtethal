<?php

namespace App\Filament\App\Widgets;

use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DeliveriesStaitisticsWidget extends ChartWidget
{
    protected static ?string $maxHeight = '210px';

    public function getHeading(): string
    {
        return __('views.DELIVERIES_STATISTICS');
    }

    protected function getData(): array
    {
        $tasks = \App\Models\Task::where('company_id', Filament::getTenant()->id)
            ->pluck('id')->toArray();
        $monthlyDeliveries = DB::table('task_deliveries')
            ->whereIn('task_id', $tasks)
            ->whereYear('delivery_time', now()->year)
            ->selectRaw("CAST(strftime('%m', delivery_time) AS INTEGER) as month, COUNT(*) as count")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')->toArray();
        $deliveriesPerMonth = collect(range(0, 11))->mapWithKeys(function ($month) use ($monthlyDeliveries) {
            return [$month => $monthlyDeliveries[$month+1] ?? 0];
        }); 
        return [
            'datasets' => [
                [
                    'label' => __('views.DELIVERIES'),
                    'data' => $deliveriesPerMonth,
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => collect(range(1, 12))->map(function ($month) {
                return \Carbon\Carbon::create()->month($month)->translatedFormat('F');
            })->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
