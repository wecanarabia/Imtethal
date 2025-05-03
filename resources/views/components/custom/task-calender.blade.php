<div>
    @php
        $delivery_class = \App\Helpers\Helper::getDeliveryClass($record->delivery_status);
    @endphp
    <style>
        .on-time {
            background-color: #04BF8A;
        }

        .within-grace-period{
            background-color: oklch(62.3% 0.214 259.815);
        }

        .delayed{
            background-color: oklch(63.7% 0.237 25.331);
            color: oklch(87.2% 0.01 258.338) !important;
        }
    </style>
    <x-filament::card>
        <div class="space-y-4">
        <div class="space-y-1">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                {{__('views.NAME')}}
            </h2>

            <p class="text-sm text-gray-600 dark:text-gray-400">
               {{ $record->name }}
            </p>
        </div>
        <div class="space-y-1">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                {{__('views.TASK_TYPE')}}
            </h2>

            <p class="text-sm text-gray-600 dark:text-gray-400">
               {{ $record->task_type }}
            </p>
        </div>
        <div class="space-y-1">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                {{__('views.TASK_EVALUATION')}}
            </h2>

            <p class="text-sm text-gray-600 dark:text-gray-400">
               {{ $record->task_evaluation }}
            </p>
        </div>
        <div class="space-y-1">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                {{__('views.STATUS')}}
            </h2>

            <p class="text-sm text-gray-600 dark:text-gray-400">
               {{ \App\Enums\TaskStatusEnum::tryFrom($record->status)?->getLabel() }}
            </p>
        </div>
        <div class="space-y-1">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                {{__('views.DELIVERY_STATUS')}}
            </h2>

            <span class="text-sm text-gray-600 dark:text-gray-600 rounded-lg p-2 {{$delivery_class}}">
               {{ \App\Enums\DeliveryStatusEnum::tryFrom($record->delivery_status)?->getLabel() }}
            </span>
        </div>
        <div class="space-y-1">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                {{__('views.DELIVERY_TIME')}} - {{__('views.GRACE_END_TIME')}}
            </h2>

            <p class="text-sm text-gray-600 dark:text-gray-400">
               {{ $record->delivery_time }} - {{ $record->grace_end_time }}
            </p>
        </div>
        </div>
    </x-filament::card>
</div>
