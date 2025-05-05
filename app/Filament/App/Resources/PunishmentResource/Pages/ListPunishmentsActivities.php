<?php

namespace App\Filament\App\Resources\PunishmentResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\App\Resources\PunishmentResource;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class ListPunishmentsActivities extends ListActivities
{
    protected static string $resource = PunishmentResource::class;
}
