<?php

namespace App\Filament\App\Resources\UserResource\Pages;

use Filament\Actions;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Filament\Resources\Pages\EditRecord;
use App\Filament\App\Resources\UserResource;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public Collection $departments;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function fillForm(): void
    {
        $this->form->fill([
            'departments' => collect($this->record->departments)
                ->map(function ($department) {
                    return [
                        'department_id' => $department->id,
                        'department_role' => $department->pivot->department_role,
                    ];
                })
                ->toArray(),
                'name' => $this->record->name,
                'email' => $this->record->email,
                'phone' => $this->record->phone,
                'type' => $this->record->type,
                'performance_evaluation' => $this->record->performance_evaluation,
                'job_title' => $this->record->job_title,
                'company_id' => $this->record->company_id,
        ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['password']=='') {
            unset($data['password']);
        }
        $this->departments = collect($this->form->getRawState()['departments']);
        Arr::except($data, ['departments']);
        return $data;
    }



  protected function afterSave(): void
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

    $this->record->departments()->sync($pivotData);
}
}
