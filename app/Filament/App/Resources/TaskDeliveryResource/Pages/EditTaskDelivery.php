<?php

namespace App\Filament\App\Resources\TaskDeliveryResource\Pages;

use App\Filament\App\Resources\TaskDeliveryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTaskDelivery extends EditRecord
{
    protected static string $resource = TaskDeliveryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
