<?php

namespace App\Filament\App\Resources\TaskDeliveryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\App\Resources\TaskDeliveryResource;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class ListTaskDeliveriesActivities extends ListActivities
{
    protected static string $resource = TaskDeliveryResource::class;
}
