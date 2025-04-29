<x-mail-layout.mail-app>
    <div class="max-w-2xl mx-auto my-8 bg-white rounded-lg shadow-sm">
        <div class="px-6 py-6">
            <p class="text-gray-700 mb-4">
                {{$data['greeting']}}
            </p>
            <p class="text-gray-700 mb-4">
                {{$data['body1']}}
            </p>
            <p class="text-gray-700 mb-4">
                {{$data['body2']}}
            </p>
            <p class="text-gray-700 mb-6">
                {{$data['action']}}
            </p>


            <a href="{{$data['link']}}"
               class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
               style="text-decoration: none;">
                @lang('views.CLICK_HERE')
            </a>
            <p class="text-gray-700 mb-4">
                {{$data['body3']}}
            </p>
            <p class="text-gray-700 mb-4">
                {{$data['deadline']}}
            </p>
            <p class="text-gray-700 mb-4">
                {{$data['thanks']}}<br>
                {{$data['signature']}}<br>
                {{$data['eimtithal']}}
            </p>
        </div>
    </div>
</x-mail-layout.mail-app>
