<?php

namespace App\Filament\App\Resources\TaskResource\Pages;

use App\Filament\App\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTasks extends ListRecords
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('calender')
                ->label(__('views.CALENDER'))
                ->color('success')
                ->url(static::$resource::getUrl('calender'))
                ->icon('heroicon-m-calendar'),
            Actions\CreateAction::make()->color('success'),
        ];
    }
}
