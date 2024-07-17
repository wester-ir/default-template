<a href="{{ route('client.index') }}" class="flex items-center">
    {{--                    <img src="{{ template_asset('assets/img/logo.svg') }}" width="40" height="40">--}}
    <div class="flex items-center justify-center font-medium text-white w-10 h-10 rounded-lg bg-green-400">L</div>
    <span class="font-medium ms-3 hidden md:block">{{ settingService('general')['name'] }}</span>
</a>
