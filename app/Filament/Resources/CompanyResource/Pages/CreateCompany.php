<?php

namespace App\Filament\Resources\CompanyResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Forms\Form;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use Spatie\Permission\PermissionRegistrar;
use App\Filament\Resources\CompanyResource;
use App\Filament\App\Resources\UserResource;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;

class CreateCompany extends CreateRecord
{
    use HasWizard;

    protected static string $resource = CompanyResource::class;


    public function form(Form $form): Form
    {
        return parent::form($form)
            ->schema([
                Wizard::make($this->getSteps())
                    ->startOnStep($this->getStartStep())
                    ->cancelAction($this->getCancelFormAction())
                    ->submitAction($this->getSubmitFormAction())
                    ->skippable($this->hasSkippableSteps())
                    ->contained(false),
            ])
            ->columns(null);
    }

    protected function getSteps(): array
    {
        return [
            Step::make('company Details')
                ->label(__('views.COMPANY_DETAILS'))
                ->schema([
                    Section::make()->schema(Static::$resource::getCompanyFormSchema())->columns(),
                ]),

            Step::make('amin Details')
                ->label(__('views.ADMIN_DETAILS'))
                ->schema([
                    Section::make()
                        ->schema(UserResource::getFormSchema('admin')),
                ]),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return Arr::only($data, ['name', 'notification_type']);
    }

    protected function afterCreate(): void
    {
        $user = Arr::except($this->data, ['name', 'notification_type']);
        $user['name'] = $this->data['user_name'];
        $user['company_id'] = $this->record->id;
        $user = User::create($user);
        $roleModel = Utils::getRoleModel();
        $permissionModel = Utils::getPermissionModel();
        $role = $roleModel::firstOrCreate([
            'name' => 'super_admin',
            'team_id' => $user['company_id'],
            'guard_name' => 'web',
        ]);
        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_role","view_any_role","create_role","update_role","delete_role","delete_any_role"]}]';
               if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
        app(PermissionRegistrar::class)->setPermissionsTeamId($user['company_id']);
        $user->assignRole($role);
    }
}
