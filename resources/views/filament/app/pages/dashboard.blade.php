<x-filament-panels::page>
    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        const button = document.getElementById('download-pdf');
        if (button) {
            button.addEventListener('click', () => {
                const element = document.getElementById('pdf-content');
                const elementWidth = element.offsetWidth;
                const elementHeight = element.scrollHeight;

                // Convert pixels to millimeters (1px ≈ 0.264583 mm)
                const mmWidth = elementWidth * 0.264583;
                const mmHeight = elementHeight * 0.264583;

                const opt = {
                    margin: [10, 10, 10, 10],
                    filename: 'filament-export.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2, scrollY: 0 },
                    jsPDF: { unit: 'mm', format: [mmWidth + 20, mmHeight + 30], orientation: 'portrait' }
                };
                html2pdf().set(opt).from(element).save();
            });
        }
    </script>
    @endpush

    <div name="footer" class="flex items-center justify-end mt-6 row">
        <x-filament::button wire:ignore color="danger" id="download-pdf" class="inline-block">
            {{ __('views.PDF_EXPORT') }}
        </x-filament::button>
    </div>
{{--     <div class="w-full my-4 risk-table">
        {{ $this->filtersForm }}
    </div> --}}
    <div id="pdf-content">
        <div class="w-full my-4 risk-table">
            @livewire(\App\Filament\App\Widgets\MainStaisticsWidget::class)
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-stretch">
            <div class="w-full h-full flex">
                <div class="w-full h-full">
                    @livewire(\App\Filament\App\Widgets\DeliveriesWidget::class)
                </div>
            </div>
            <div class="w-full h-full flex">
                <div class="w-full h-full">
                    @livewire(\App\Filament\App\Widgets\DeliveriesStaitisticsWidget::class)
                </div>
            </div>
        </div>
        <div class="w-full my-4 risk-table">
            <h5 class="mb-2">@lang('views.MOST_EIMTITHAL_EMPLOYEES')</h5>
            @livewire(\App\Filament\App\Widgets\MostEimtithalWidget::class)
        </div>
        <div class="w-full my-4 risk-table">
            <h5 class="mb-2">@lang('views.LEAST_EIMTITHAL_EMPLOYEES')</h5>
            @livewire(\App\Filament\App\Widgets\LeastEimtithalWidget::class)
        </div>
    </div>

</x-filament-panels::page>
