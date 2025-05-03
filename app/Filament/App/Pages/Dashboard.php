<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;

class Dashboard extends BaseDashboard
{

    // use HasFiltersForm;

    // protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.dashboard';
    public function getHeaderActions(): array
    {
        return [

        ];
    }
   /*  public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        DatePicker::make('startDate')
                            ->label(__('views.START_DATE'))
                            ->default(null)
                            ->afterStateUpdated(fn (?string $state) => $this->dispatch('updateFromDate', from: $state)),
                        DatePicker::make('endDate')
                            ->label(__('views.END_DATE'))
                            ->default(null)
                            ->afterStateUpdated(fn (?string $state) => $this->dispatch('updateToDate', to: $state)),
                    ])
                    ->columns(2),
            ]);
    } */
}
