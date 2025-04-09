<?php

namespace App\Filament\App\Resources;

use Carbon\Carbon;
use Filament\Forms;
use App\Models\Task;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Department;
use Filament\Tables\Table;
use App\Enums\PriorityEnum;
use App\Enums\TaskTypeEnum;
use App\Enums\TaskStatusEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use App\Enums\DeliveryStatusEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Static\Form as StaticForm;
use Filament\Forms\Components\Actions\Action;
use App\Filament\App\Resources\TaskResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\TaskResource\RelationManagers;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;

class TaskResource extends Resource implements HasShieldPermissions
{
    use StaticForm;
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getPluralLabel(): string
    {
        return trans('views.TASKS');
    }

    public static function getLabel(): string
    {
        return trans('views.TASK');
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }

    public static function form(Form $form): Form
    {
        $record = $form->getRecord();
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->maxLength(255)
                    ->label(__('views.NAME'))
                    ->required(),
                Forms\Components\Select::make('task_type')
                    ->label(__('views.TASK_TYPE'))
                    ->options(TaskTypeEnum::labels())
                    ->live()
                    ->disabled(fn($operation, $record) => $operation == 'edit' && $record->task_type == TaskTypeEnum::RECURRING->value)
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label(__('views.DESCRIPTION'))
                    ->nullable()
                    ->columnSpanFull(),
                Forms\Components\Hidden::make('company_id')
                    ->default(Filament::getTenant()->id),
                Forms\Components\Textarea::make('note')
                    ->nullable()
                    ->columnSpanFull()
                    ->label(__('views.NOTE')),
               Forms\Components\TextInput::make('task_repetition')
                    ->required()
                    ->label(__('views.TASK_REPETITION_IN_DAYS'))
                    ->visible(fn(Forms\Get $get) => $get('task_type') != TaskTypeEnum::NONE_RECURRING->value)
                    ->default(0)
                    ->numeric(),
                Forms\Components\Select::make('priority')
                    ->label(__('views.PRIORITY'))
                    ->options(PriorityEnum::labels())
                    ->required(),
                Forms\Components\Textarea::make('delay_puneshment')
                    ->label(__('views.DELAY_PUNESMENT'))
                    ->nullable()
                    ->columnSpanFull(),
                Repeater::make('assignees')
                    ->relationship('assignees')
                    ->label(__('views.ASSIGNEES'))
                    ->addActionLabel(__('views.ADD_ASSIGNEE'))
                    ->required()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Select::make('assigneeable_type')
                            ->label(__('views.ASSIGNEE_TYPE'))
                            ->options([
                                User::class => __('views.USER'),
                                Department::class => __('views.DEPARTMENT'),
                            ])
                            ->required()
                            ->reactive(),

                        Select::make('assigneeable_id')
                            ->label(__('views.ASSIGNEE'))
                            ->preload()
                            ->options(function(Forms\Get $get) {
                                $data = match ($get('assigneeable_type')) {
                                    User::class => User::where('company_id', Filament::getTenant()->id)->pluck('name', 'id'),
                                    Department::class => Department::where('company_id', Filament::getTenant()->id)->pluck('name', 'id'),
                                    default => [],
                                };
                                return $data;
                            })
                            ->required()
                            ->searchable(),
                    ]),

                    Repeater::make('files')
                        ->relationship('files')
                        ->label(__('views.FILES'))
                        ->addActionLabel(__('views.ADD_FILE'))
                        ->nullable()
                        ->columnSpanFull()
                        ->collapsible()
                        ->default([])
                        ->schema([
                            Static::fileInput(config('constants.TASK_FILE_DIR'))
                                ->required(),
                        ]),
                 Repeater::make('deliveries')
                    ->relationship('deliveries')
                    ->label(__('views.NEXT_DELIVERY'))
                    ->required()
                    ->addable(false)
                    ->deletable(false)
                    ->visible(function($operation, Forms\Get $get, $record) {
                        return ($operation == 'create'&& $get('task_type') == TaskTypeEnum::RECURRING->value);
                    })
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Forms\Components\DateTimePicker::make('delivery_time')
                            ->required()
                            ->label(__('views.DELIVERY_TIME')),
                        Forms\Components\DateTimePicker::make('grace_end_time')
                            ->required()
                            ->label(__('views.GRACE_END_TIME')),
                    ]),
                Repeater::make('deliveries')
                    ->relationship('deliveries')
                    ->label(__('views.NEXT_DELIVERY'))
                    ->required()
                    ->addable(false)
                    ->deletable(false)
                    ->visible(function($operation, Forms\Get $get, $record) {
                        return ($get('task_type') == TaskTypeEnum::NONE_RECURRING->value);
                    })
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Forms\Components\DateTimePicker::make('delivery_time')
                            ->required()
                            ->label(__('views.DELIVERY_TIME')),
                        Forms\Components\DateTimePicker::make('grace_end_time')
                            ->required()
                            ->label(__('views.GRACE_END_TIME')),
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
                    ]),
                Repeater::make('incompletedDeliveries')
                    ->relationship('incompletedDeliveries')
                    ->label(__('views.NEXT_DELIVERY'))
                    ->required()
                    ->deletable(false)
                    ->addable(false)
                    ->visible(function($operation, Forms\Get $get, $record) {
                        return $operation == "edit"&& $get('task_type') == TaskTypeEnum::RECURRING->value;
                    })
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Forms\Components\DateTimePicker::make('delivery_time')
                            ->required()
                            ->label(__('views.DELIVERY_TIME')),
                        Forms\Components\DateTimePicker::make('grace_end_time')
                            ->required()
                            ->label(__('views.GRACE_END_TIME')),
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
                    ]),

                    Actions::make([
                        Action::make('add_delivery')
                            ->icon('heroicon-m-plus')
                            ->visible(function($operation, Forms\Get $get, $record) {
                                if ($operation == "edit"&&$get('task_type') == TaskTypeEnum::RECURRING->value) {
                                    return true;
                                }
                            })
                            ->extraAttributes(['class' => 'mx-auto'])
                            ->label(__('views.ADD_DELIVERY'))
                            ->action(function (Forms\Set $set, $livewire) use ($record,$form) {
                                $latestDelivery = $record->deliveries()->latest()->first();

                                $record->deliveries()->create([
                                    'delivery_time' => Carbon::parse($latestDelivery?->delivery_time)->addDays($record->task_repetition),
                                    'grace_end_time' => Carbon::parse($latestDelivery?->grace_end_time)->addDays($record->task_repetition),
                                ]);
                                $incompletedDeliveries = $record->incompletedDeliveries->map(function ($delivery) {
                                    return [
                                        'delivery_time' => Carbon::parse($delivery->delivery_time)->format('Y-m-d H:i:s'),
                                        'grace_end_time' => Carbon::parse($delivery->grace_end_time)->format('Y-m-d H:i:s'),
                                        'task_evaluation' => $delivery->task_evaluation,
                                        'status' => $delivery->status,
                                        'delivery_status' => $delivery->delivery_status,
                                    ];
                                })
                                ->toArray();
                                $set('incompletedDeliveries',$incompletedDeliveries);
                            }),
                    ])->columnSpanFull(),
            ]);


    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('views.NAME'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('task_type')
                    ->label(__('views.TASK_TYPE'))
                    ->formatStateUsing(fn ($state) => TaskTypeEnum::tryFrom($state)?->getLabel() ?? $state)
                    ->searchable(),
                Tables\Columns\TextColumn::make('delivery_status')
                    ->label(__('views.DELIVERY_STATUS'))
                    ->formatStateUsing(fn ($state) => DeliveryStatusEnum::tryFrom($state)?->getLabel() ?? $state)
                    ->searchable(),



                Tables\Columns\TextColumn::make('priority')
                    ->formatStateUsing(fn ($state) => PriorityEnum::tryFrom($state)?->getLabel() ?? $state)
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn ($state) => TaskStatusEnum::tryFrom($state)?->getLabel() ?? $state)
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'calender' => Pages\TaskCalender::route('/calender'),
            'create' => Pages\CreateTask::route('/create'),
            'view' => Pages\ViewTask::route('/{record}'),
            'edit' => Pages\EditTask::route('/{record}/edit'),

        ];
    }
}
