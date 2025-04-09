<?php

namespace App\Filament\App\Resources\TaskResource\Pages;

use App\Filament\App\Resources\TaskResource;
use Filament\Resources\Pages\Page;

class TaskCalender extends Page
{
    protected static string $resource = TaskResource::class;

    protected static string $view = 'filament.app.resources.task-resource.pages.task-calender';

    public function getTitle() :string
    {
        return __('views.TASK_CALENDER');
    }
}
