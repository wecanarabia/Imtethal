<?php

namespace App\Filament\App\Resources\PunishmentResource\Pages;

use App\Filament\App\Resources\PunishmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPunishment extends ViewRecord
{
    protected static string $resource = PunishmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
