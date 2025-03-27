<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Facades\Filament;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['ar','en']); // also accepts a closure
        });
        if (Filament::getPanel()->getId() == 'app') {
            Gate::policy(\App\Models\Role::class, \App\Policies\RolePolicy::class);
            Gate::policy(\App\Models\User::class, \App\Policies\UserPolicy::class);
            Gate::policy(\App\Models\Department::class, \App\Policies\DepartmentPolicy::class);
            Gate::policy(\App\Models\Task::class, \App\Policies\TaskPolicy::class);
        }
    }
}
