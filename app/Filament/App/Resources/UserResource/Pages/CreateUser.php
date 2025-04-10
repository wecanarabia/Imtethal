<?php

namespace App\Filament\App\Resources\UserResource\Pages;

use Filament\Actions;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\App\Resources\UserResource;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    public Collection $departments;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->departments = collect($this->form->getRawState()['departments']);
        return Arr::except($data, ['departments']);
    }

  protected function afterCreate(): void
{
    $departments = $this->departments ?? [];

    $pivotData = [];

    foreach ($departments as $item) {
        if (isset($item['department_id'], $item['department_role'])) {
            $pivotData[$item['department_id']] = [
                'department_role' => $item['department_role'],
            ];
        }
    }

    $this->record->departments()->attach($pivotData);
}
}
