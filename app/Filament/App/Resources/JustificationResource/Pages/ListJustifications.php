<?php

namespace App\Filament\App\Resources\JustificationResource\Pages;

use App\Filament\App\Resources\JustificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJustifications extends ListRecords
{
    protected static string $resource = JustificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
