<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Punishment;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\PunishmentResource\Pages;
use App\Filament\App\Resources\PunishmentResource\RelationManagers;

class PunishmentResource extends Resource
{
    protected static ?string $model = Punishment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getPluralLabel(): string
    {
        return trans('views.PUNISHMENTS');
    }

    public static function getLabel(): string
    {
        return trans('views.PUNISHMENT');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('assignee.name')
                    ->label(__('views.ASSIGNEE'))
                    ->diasabled(),
                Forms\Components\Textarea::make('note')
                    ->columnSpanFull(),
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('taskDelivery.task.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assignee.name')
                    ->label(__('views.ASSIGNEE')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label(__('views.CREATED_AT'))
                    ->sortable(),
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
            'index' => Pages\ListPunishments::route('/'),
            'view' => Pages\ViewPunishment::route('/{record}'),
            'activities' => Pages\ListPunishmentsActivities::route('/{record}/activities'),
            'edit' => Pages\EditPunishment::route('/{record}/edit'),
        ];
    }
}
