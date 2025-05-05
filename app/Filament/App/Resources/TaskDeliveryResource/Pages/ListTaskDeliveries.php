<?php

namespace App\Filament\App\Resources\TaskDeliveryResource\Pages;

use App\Filament\App\Resources\TaskDeliveryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTaskDeliveries extends ListRecords
{
    protected static string $resource = TaskDeliveryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
