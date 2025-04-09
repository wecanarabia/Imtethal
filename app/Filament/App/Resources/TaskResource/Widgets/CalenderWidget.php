<?php

namespace App\Filament\App\Resources\TaskResource\Widgets;

use Filament\Actions;
use Filament\Actions\Action;
use App\Models\TaskDelivery;
use Filament\Widgets\Widget;
use App\Enums\TaskStatusEnum;
use Filament\Facades\Filament;
use Filament\Forms;
use App\Enums\DeliveryStatusEnum;
use Illuminate\Database\Eloquent\Model;
use \Guava\Calendar\Widgets\CalendarWidget;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalenderWidget extends FullCalendarWidget
{
    // protected static string $view  = 'filament.app.resources.task-resource.widgets.calender-widget';

    public Model | string | null $model = TaskDelivery::class;

    protected function headerActions(): array
    {
        return [
            
        ];
    }

    protected function modalActions(): array
    {
        return [
            Actions\EditAction::make()
                ->form($this->getFormSchema()),
        ];
    }


    public function fetchEvents(array $fetchInfo): array
    {
        return TaskDelivery::query()->whereHas('task',function($q){
            $q->where('company_id', Filament::getTenant()->id);
        })->get()
            ->map(function ($task) {
                return [
                    'id'    => $task->id,
                    'title' => $task->task->name,
                    'start' => $task->delivery_time,
                    'end'   => $task->grace_end_time,
                ];
            })
            ->toArray();
    }

    public static function canView(): bool
    {
        return false;
    }

    public function getFormSchema(): array
    {
        return [
             Forms\Components\TextInput::make('task_evaluation')
                            ->required()
                            ->label(__('views.TASK_EVALUATION'))
                            ->numeric()
                            ->default(0),
                        Forms\Components\Select::make('status')
                            ->label(__('views.STATUS'))
                            ->options(TaskStatusEnum::labels())
                            ->default(TaskStatusEnum::PENDING->value)
                            ->required(),
                        Forms\Components\Select::make('delivery_status')
                            ->nullable()
                            ->label(__('views.DELIVERY_STATUS'))
                            ->options(DeliveryStatusEnum::labels()),
        ];
    }
}
