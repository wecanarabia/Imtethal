<x-mail-layout.mail-app>
    <div class="max-w-2xl mx-auto my-8 bg-white rounded-lg shadow-sm">
        <div class="px-6 py-6">
            <p class="text-gray-700 mb-1">
                {{$data['greeting']}}
            </p>
            <div class="text-gray-700 mb-1 space-y-1">
                <p>@lang('views.INCOMPLETE_TASK_NOTICE')</p>
            </div>

            <a href="{{$data['link']}}"
               class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
               style="text-decoration: none;">
                @lang('views.CLICK_HERE')
            </a> 
           <div class="text-gray-700 mb-4 space-y-1">
                {{ $data['signature'] }}<br/>
                {{ $data['eimtithal'] }}
            </div> 
        </div>
    </div>
</x-mail-layout.mail-app>
