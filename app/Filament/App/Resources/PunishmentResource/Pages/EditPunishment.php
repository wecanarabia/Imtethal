<?php

namespace App\Filament\App\Resources\PunishmentResource\Pages;

use App\Filament\App\Resources\PunishmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPunishment extends EditRecord
{
    protected static string $resource = PunishmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
