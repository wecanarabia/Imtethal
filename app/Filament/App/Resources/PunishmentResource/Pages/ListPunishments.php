<?php

namespace App\Filament\App\Resources\PunishmentResource\Pages;

use App\Filament\App\Resources\PunishmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPunishments extends ListRecords
{
    protected static string $resource = PunishmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
