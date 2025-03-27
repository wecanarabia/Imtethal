<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use App\Models\Task;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\PriorityEnum;
use App\Enums\TaskTypeEnum;
use App\Enums\TaskStatusEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use App\Enums\DeliveryStatusEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\App\Resources\TaskResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\TaskResource\RelationManagers;
use App\Filament\Static\Form as StaticForm;
use App\Models\Department;
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
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label(__('views.DESCRIPTION'))
                    ->nullable()
                    ->columnSpanFull(),
                Forms\Components\Select::make('delivery_status')
                    ->nullable()
                    ->label(__('views.DELIVERY_STATUS'))
                    ->options(DeliveryStatusEnum::labels()),
                Forms\Components\TextInput::make('task_repetition')
                    ->required()
                    ->label(__('views.TASK_REPETITION_IN_DAYS'))
                    ->visible(fn($get) => $get('task_type') != TaskTypeEnum::NONE_RECURRING->value)
                    ->default(0)
                    ->numeric(),
                Forms\Components\HIDDEN::make('company_id')
                    ->default(Filament::getTenant()->id),
                Forms\Components\Textarea::make('note')
                    ->nullable()
                    ->columnSpanFull()
                    ->label(__('views.NOTE')),
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
                Forms\Components\Select::make('priority')
                    ->label(__('views.PRIORITY'))
                    ->options(PriorityEnum::labels())
                    ->required(),
                Forms\Components\Textarea::make('delay_puneshment')
                    ->nullable()
                    ->label(__('views.DELAY_PUNESMENT'))
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->label(__('views.STATUS'))
                    ->options(TaskStatusEnum::labels())
                    ->default(TaskStatusEnum::PENDING->value)
                    ->required(),
                Repeater::make('assignees')
                    ->relationship('assignees')
                    ->label(__('views.ASSIGNEES'))
                    ->addActionLabel(__('views.ADD_ASSIGNEE'))
                    ->required()
                    ->collapsible()
                    ->default([])
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
                            ->options(fn (callable $get) => match ($get('assigneeable_type')) {
                                User::class => User::where('company_id', Filament::getTenant()->id)->pluck('name', 'id'),
                                Department::class => Department::where('company_id', Filament::getTenant()->id)->pluck('name', 'id'),
                                default => [],
                            })
                            ->required()
                            ->searchable(),
                    ]),

                    Repeater::make('files')
                    ->relationship('files')
                    ->label(__('views.FILES'))
                    ->addActionLabel(__('views.ADD_FILE'))
                    ->required()
                    ->columnSpanFull()
                    ->collapsible()
                    ->default([])
                    ->schema([
                        Static::fileInput(config('constants.TASK_FILE_DIR'))
                            ->required(),
                    ]),
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
            'create' => Pages\CreateTask::route('/create'),
            'view' => Pages\ViewTask::route('/{record}'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
