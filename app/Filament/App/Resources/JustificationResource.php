<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Justification;
use Filament\Resources\Resource;
use App\Enums\JustificationStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\JustificationResource\Pages;
use App\Filament\App\Resources\JustificationResource\RelationManagers;

class JustificationResource extends Resource
{
    protected static ?string $model = Justification::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getPluralLabel(): string
    {
        return trans('views.JUSTIFICATIONS');
    }

    public static function getLabel(): string
    {
        return trans('views.JUSTIFICATION');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('assignee.name')
                    ->disabled()
                    ->required(),
                Forms\Components\Textarea::make('note')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('reply')
                    ->disabled()
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->label(__('views.STATUS'))
                    ->options(JustificationStatusEnum::labels())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('task_delivery.task.name')
                    ->label(__('views.TASK'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assignee.name')
                    ->label(__('views.ASSIGNEE'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn ($state) => JustificationStatusEnum::tryFrom($state)?->getLabel() ?? $state)
                    ->label(__('views.STATUS')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label(__('views.CREATED_AT'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListJustifications::route('/'),
            'create' => Pages\CreateJustification::route('/create'),
            'view' => Pages\ViewJustification::route('/{record}'),
            'edit' => Pages\EditJustification::route('/{record}/edit'),
        ];
    }
}
