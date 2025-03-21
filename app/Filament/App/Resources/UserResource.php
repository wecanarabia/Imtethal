<?php

namespace App\Filament\App\Resources;

use App\Enums\UserTypeEnum;
use App\Filament\App\Resources\UserResource\Pages;
use App\Filament\App\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getPluralLabel(): string
    {
        return trans('views.USERS');
    }

    public static function getLabel(): string
    {
        return trans('views.USER');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Static::getFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('views.NAME'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('views.EMAIL'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('views.PHONE'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('views.TYPE'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('performance_evaluation')
                    ->label(__('views.PERFORMANCE_EVALUATION'))
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('job_title')
                    ->label(__('views.JOB_TITLE'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getFormSchema($panel = null):array
    {
        return [
            $panel == 'admin'?
            Forms\Components\TextInput::make('user_name')
                ->maxLength(255)
                ->label(__('views.NAME'))
                ->required()
            :Forms\Components\TextInput::make('name')
                ->maxLength(255)
                ->label(__('views.NAME'))
                ->required(),
            Forms\Components\TextInput::make('email')
                ->email()
                ->maxLength(255)
                ->label(__('views.EMAIL'))
                ->required(),
            Forms\Components\TextInput::make('phone')
                ->tel()
                ->label(__('views.PHONE'))
                ->maxLength(255)
                ->unique(User::class, 'phone', ignoreRecord: true)
                ->required(),
            Forms\Components\TextInput::make('password')
                ->label(__('views.PASSWORD'))
                ->visible(function ($operation) {
                    return $operation != 'view';
                })
                ->password()
                ->revealable()
                ->maxLength(255)
                ->required(fn($component, $get, $model, $record, $set, $state) => $record === null),
            Forms\Components\Select::make('type')
                ->label(__('views.TYPE'))
                ->options(UserTypeEnum::labels())
                ->required(),
            Forms\Components\TextInput::make('job_title')
                ->label(__('views.JOB_TITLE'))
                ->maxLength(255)
                ->required(),
        ];
    }
}
