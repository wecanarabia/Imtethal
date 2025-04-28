<div>
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

            <p class="text-sm text-gray-600 dark:text-gray-400">
               {{ \App\Enums\DeliveryStatusEnum::tryFrom($record->delivery_status)?->getLabel() }}
            </p>
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
