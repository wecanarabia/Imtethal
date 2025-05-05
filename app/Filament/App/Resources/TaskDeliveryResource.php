<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\TaskDelivery;
use App\Enums\TaskStatusEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use App\Enums\DeliveryStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\TaskDeliveryResource\Pages;
use App\Filament\App\Resources\TaskDeliveryResource\RelationManagers;

class TaskDeliveryResource extends Resource
{
    protected static ?string $model = TaskDelivery::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $isScopedToTenant = false;

    public static function getPluralLabel(): string
    {
        return trans('views.TASK_DELIVERIES');
    }

    public static function getLabel(): string
    {
        return trans('views.TASK_DELIVERY');
    }

    public static function form(Form $form): Form
    {
        return $form
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
            ]);
    }

    public static function table(Table $table): Table
    {
        $tasks = \App\Models\Task::query()->where('company_id', Filament::getTenant()->id)->pluck('id')->toArray();
        return $table
            ->query(TaskDelivery::query()->whereIn('task_id', $tasks))
            ->columns([
                Tables\Columns\TextColumn::make('task.name')
                    ->label(__('views.TASK'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('delivery_status')
                    ->label(__('views.DELIVERY_STATUS'))
                    ->formatStateUsing(fn ($state) => DeliveryStatusEnum::tryFrom($state)?->getLabel() ?? $state)
                    ->searchable(),
                Tables\Columns\TextColumn::make('delivery_time')
                    ->label(__('views.DELIVERY_TIME'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('grace_end_time')
                    ->label(__('views.GRACE_END_TIME'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('task_evaluation')
                    ->label(__('views.TASK_EVALUATION'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('views.STATUS'))
                    ->formatStateUsing(fn ($state) => TaskStatusEnum::tryFrom($state)?->getLabel() ?? $state)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('views.CREATED_AT'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('activities')
                    ->label(__('views.LOGS'))
                    ->icon('heroicon-o-list-bullet')
                    ->url(fn ($record) => Static::getUrl('activities', ['record' => $record]))

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
            'index' => Pages\ListTaskDeliveries::route('/'),
            // 'create' => Pages\CreateTaskDelivery::route('/create'),
            'view' => Pages\ViewTaskDelivery::route('/{record}'),
            'activities' => Pages\ListTaskDeliveriesActivities::route('/{record}/activities'),
            'edit' => Pages\EditTaskDelivery::route('/{record}/edit'),
        ];
    }
}
