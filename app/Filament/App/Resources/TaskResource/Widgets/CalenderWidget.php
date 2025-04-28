<?php

namespace App\Filament\App\Resources\TaskResource\Widgets;

use Filament\Forms;
use App\Models\User;
use App\Models\Department;
use App\Models\TaskDelivery;
use Filament\Actions\Action;
use Filament\Widgets\Widget;
use App\Enums\TaskStatusEnum;
use App\Services\SendService;
use Filament\Facades\Filament;
use App\Enums\DeliveryStatusEnum;
use App\Enums\DepartmentRoleEnum;
use App\Filament\Justification\Pages\Justification;
use App\Strategies\EmailStrategy;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Actions;
use \Guava\Calendar\Widgets\CalendarWidget;
use App\Strategies\UnformalWhatsappStrategy;
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

        ];
    }

    protected function viewAction(): Action
    {
        return Actions\ViewAction::make()
            ->form([])
            ->modalContent(fn (TaskDelivery $record): View => view(
                'components.custom.task-calender',
                ['record' => $record],
            ))
            ->modalFooterActionsAlignment('center')
            ->modalFooterActions(function (TaskDelivery $record): array {
                return [
                    \Filament\Actions\Action::make('on_time')
                        ->color('success')
                        ->label(__('views.ON_TIME'))
                        ->visible(fn () => $record->status != TaskStatusEnum::COMPLETED->value)
                        ->action(function () use ($record) {
                            $record->update([
                                'delivery_status' => DeliveryStatusEnum::ON_TIME->value,
                                'status' => TaskStatusEnum::COMPLETED->value
                            ]);
                        }),
                    \Filament\Actions\Action::make('grace_time')
                        ->color('primary')
                        ->label(__('views.WITHIN_GRACE_PERIOD'))
                        ->visible(fn () => $record->status != TaskStatusEnum::COMPLETED->value)
                        ->action(function () use ($record) {
                            $record->update([
                                'delivery_status' => DeliveryStatusEnum::WITHIN_GRACE_PERIOD->value,
                                'status' => TaskStatusEnum::COMPLETED->value
                            ]);
                        }),
                    \Filament\Actions\Action::make('delayed')
                        ->color('danger')
                        ->label(__('views.DELAYED_TIME'))
                        ->visible(fn () => $record->status != TaskStatusEnum::COMPLETED->value)
                        ->action(function () use ($record) {
                            $record->update([
                                'delivery_status' => DeliveryStatusEnum::DELAYED->value,
                                'status' => TaskStatusEnum::COMPLETED->value
                            ]);
                        }),
                    \Filament\Actions\Action::make('incomplete')
                        ->color('info')
                        ->label(__('views.INCOMPLETE'))
                        ->visible(fn () => $record->status != TaskStatusEnum::COMPLETED->value/* &&$record->justifications()->count() == 0 */)
                        ->action(function () use ($record) {
                            foreach ($record->task->assignees as $assignee) {
                                if ($assignee->assigneeable_type == User::class) {
                                    $user = $assignee->assigneeable;
                                    $justification = $this->newJustification($record, $assignee->id);
                                }elseif($assignee->assigneeable_type == Department::class){
                                    $user = $assignee->assigneeable->employees()->where('department_role', DepartmentRoleEnum::HEAD_OF_DEPARTMENT->value)->first();
                                    $justification = $this->newJustification($record, $assignee->id);
                                }
                            }
                            $data['name'] = $user->name;
                            $data['subject'] = __("views.INCOMPLETE_TASK_SUBJECT");
                            $data['email'] = $user->email;
                            $data['phone'] = $user->phone;
                            $data['msg'] = __("views.INCOMPLETE_TASK_GREETING", ['employee_name' => $user->name]) . "\n\n" .
                                __("views.INCOMPLETE_TASK_BODY_1") . "\n\n" .
                                __("views.INCOMPLETE_TASK_BODY_2") . "\n\n" .
                                __("views.INCOMPLETE_TASK_ACTION") . "\n\n" .
                                __("views.INCOMPLETE_TASK_LINK", ['justification_link' => url(Justification::getUrl(parameters: ['id' => $justification->id], panel: 'justification'))]) . "\n\n" .
                                __("views.INCOMPLETE_TASK_BODY_3") . "\n\n" .
                                __("views.INCOMPLETE_TASK_DEADLINE", ['deadline' => '48']) . "\n\n" .
                                __("views.INCOMPLETE_TASK_THANKS") . "\n" .
                                __("views.INCOMPLETE_TASK_SIGNATURE") . "\n" .
                                __("views.EIMTITHAL");
                            switch (Filament::getTenant()->notification_type) {
                                case 'email':
                                    $smsService = new SendService(new EmailStrategy());
                                    $smsService->sendMsg($data);
                                    $done = 1;
                                    break;
                                case 'whatsapp':
                                    $smsService = new SendService(new UnformalWhatsappStrategy());
                                    $smsService->sendMsg($data);
                                    $done = 1;
                                    break;
                                case 'both':
                                    $mailService = new SendService(new EmailStrategy());
                                    $mailService->sendMsg($data);
                                    // $smsService = new SendService(new UnformalWhatsappStrategy());
                                    // $smsService->sendMsg($data);
                                    $done = 1;
                                    break;
                                default:
                                    $done = 0;


                            }
                        }),
                ];
            });
    }

    public function newJustification($record, $user_id)
    {
        return $record->justifications()->create([
            'assignee_id'=> $user_id,
        ]);
    }
    /* public function config(): array
    {
        return [
            'initialView' => 'timeGridWeek',
            'headerToolbar' => [
                'left' => 'dayGridWeek,dayGridDay',
                'center' => 'title',
                'right' => 'prev,next today',
            ],
        ];
    } */


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
