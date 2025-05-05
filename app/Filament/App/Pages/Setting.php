<?php

namespace App\Filament\App\Pages;

use Filament\Forms;
use App\Models\Company;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Facades\Filament;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class Setting extends Page implements HasForms
{

    use InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $view = 'filament.app.pages.setting';

    protected static ?string $slug = 'Settings';

    public ?array $data = [];

    public function getTitle(): string
    {
        return __('views.SETTINGS');
    }

    public static function getNavigationLabel(): string
    {
        return __('views.SETTINGS');
    }

    public function mount(): void
    {
        $company = Company::find(Filament::getTenant()->id);
        $this->form->fill([
            'name' => $company->name,
            'on_time_schedule_points' => $company->on_time_schedule_points,
            'grace_period_points' => $company->grace_period_points,
            'delay_delivery_points' => $company->delay_delivery_points
        ]);
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->columnSpanFull()
                    ->label(__('views.COMPANY_NAME')),
                Forms\Components\TextInput::make('on_time_schedule_points')
                    ->required()
                    ->columnSpanFull()
                    ->numeric()
                    ->label(__('views.ON_TIME_SCHEDULE_POINTS')),
                Forms\Components\TextInput::make('grace_period_points')
                    ->required()
                    ->numeric()
                    ->columnSpanFull()
                    ->label(__('views.GRACE_PERIOD_POINTS')),
                Forms\Components\TextInput::make('delay_delivery_points')
                    ->required()
                    ->numeric()
                    ->columnSpanFull()
                    ->label(__('views.DELAY_DELIVERY_POINTS')),
            ])->statePath('data');
    }

    public function create()
    {
        $comapny = Company::find(Filament::getTenant()->id);
        $comapny->update($this->data);
        return redirect()->to(Static::getUrl(['tenant' => $comapny->slug]));
    }
}
