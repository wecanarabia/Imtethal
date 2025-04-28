<x-filament-panels::page>
        <x-filament::section wire:show="!showModal">
            <form wire:submit.prevent="create">
                {{$this->form}}
                <x-filament::button type="submit" color="success" wire:submit="create">
                    {{ __('filament-edit-profile::default.save') }}
                </x-filament::button>
            </form>
        </x-filament::section>
        <x-filament::section wire:show="showModal">
            @lang('views.THANK_YOU_JUSTIFICATION_FILLED')
        </x-filament::section>
</x-filament-panels::page>
