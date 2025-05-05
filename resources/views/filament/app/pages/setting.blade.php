<x-filament-panels::page>
        <x-filament::section>
            <form wire:submit.prevent="create">
                {{$this->form}}
                <x-filament::button type="submit" color="success" wire:submit="create">
                    {{ __('filament-edit-profile::default.save') }}
                </x-filament::button>
            </form>
        </x-filament::section>
</x-filament-panels::page>
