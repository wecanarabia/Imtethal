<?php

namespace App\Filament\App\Resources\JustificationResource\Pages;

use App\Filament\App\Resources\JustificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class ListJustificationsActivities extends ListActivities
{
    protected static string $resource = JustificationResource::class;
}
