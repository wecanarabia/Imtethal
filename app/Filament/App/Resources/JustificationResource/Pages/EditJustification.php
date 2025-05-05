<?php

namespace App\Filament\App\Resources\JustificationResource\Pages;

use App\Filament\App\Resources\JustificationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJustification extends EditRecord
{
    protected static string $resource = JustificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
