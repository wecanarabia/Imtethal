<?php

namespace App\Filament\App\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Concerns\InteractsWithTable;

class MostEimtithalWidget extends BaseWidget implements HasTable
{
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()->orderBy('performance_evaluation', 'desc')->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('views.NAME'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('performance_evaluation')
                    ->label(__('views.PERFORMANCE_EVALUATION'))
                    ->numeric(),
                Tables\Columns\TextColumn::make('job_title')
                    ->label(__('views.JOB_TITLE'))
                    ->searchable(),
            ])
            ->paginated(false);
    }
}
