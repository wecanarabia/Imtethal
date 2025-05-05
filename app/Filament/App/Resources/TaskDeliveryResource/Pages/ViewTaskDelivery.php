<?php

namespace App\Filament\App\Resources\TaskDeliveryResource\Pages;

use App\Filament\App\Resources\TaskDeliveryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTaskDelivery extends ViewRecord
{
    protected static string $resource = TaskDeliveryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
