<?php

namespace App\Filament\App\Resources\TaskResource\Pages;

use Carbon\Carbon;
use Filament\Actions;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use App\Filament\App\Resources\TaskResource;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class ListTaskActivities extends ListActivities
{
    protected static string $resource = TaskResource::class;

}
