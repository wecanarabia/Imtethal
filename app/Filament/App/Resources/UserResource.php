<?php

namespace App\Filament\App\Resources;

use Closure;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\UserTypeEnum;
use Filament\Facades\Filament;
use Illuminate\Validation\Rule;
use Filament\Resources\Resource;
use App\Enums\DepartmentRoleEnum;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;
use App\Filament\App\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\UserResource\RelationManagers;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;

class UserResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
          Forms\Components\TextInput::make('performance_evaluation')
            ->label(__('views.PERFORMANCE_EVALUATION'))
            ->visible(function ($operation)  {
                return $operation == 'view';
            }),

        Repeater::make('departments')
            ->visible(function ($operation)use($panel){
                return $panel != 'admin'||$panel == null||$operation != 'view';
            })
            ->label(__('views.DEPARTMENTS'))
            ->columns(2)
            ->collapsible()
            ->rules(function (array $state) {
                $departmentIds = array_column($state, 'department_id');
                $duplicates = array_diff_key($departmentIds, array_unique($departmentIds));
                return function (string $attribute, $value, $fail) use ($duplicates) {
                if (count($duplicates)) {
                    $fail(
                        __('views.DEPARTMENTS_ERROR'),
                    );
                }
            };
            })
            ->columnSpanFull()
            ->schema([
                Select::make('department_id')
                    ->label(__('views.DEPARTMENT'))
                    ->options(
                        \App\Models\Department::query()
                            ->where('company_id', Filament::getTenant()?->id)
                            ->pluck('name', 'id')
                            ->toArray()
                    )
                    ->required()
                    ->columnSpan(1),
                Select::make('department_role')
                    ->label(__('views.DEPARTMENT_ROLE'))
                    ->options(DepartmentRoleEnum::labels())
                    ->required()
                    ->rules([
                        fn (Forms\Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                            $departmentId = $get('department_id');
                            $departmentRole = $value;
                            $role = DB::table('department_employee')
                                ->where('department_id', $departmentId)
                                ->when($value==DepartmentRoleEnum::HEAD_OF_DEPARTMENT->value, function ($query) {
                                    return $query->where('department_role', DepartmentRoleEnum::HEAD_OF_DEPARTMENT->value);
                                })
                                ->exists();
                            if ($role&&$departmentRole==DepartmentRoleEnum::HEAD_OF_DEPARTMENT->value) {
                                $fail(__('views.HEAD_OF_DEPARTMENT_ROLE_ERROR'));
                            }
                        },
                    ])
                    ->columnSpan(1),
            ]),
        ];
    }
}
