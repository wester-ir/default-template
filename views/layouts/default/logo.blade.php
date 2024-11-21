@php
    $withName ??= true;
@endphp

<a href="{{ route('client.index') }}" class="flex items-center">
    @if ($logo = site_logo())
        <img src="{{ $logo }}" class="w-10 h-10 min-w-[40px] min-h-[40px]">
    @else
        <div class="flex items-center justify-center font-medium text-white w-10 h-10 min-w-[40px] min-h-[40px] rounded-lg bg-green-400">L</div>
    @endif

    @if ($withName)
        <span class="font-medium ms-3 hidden md:block">{{ settingService('general')['name'] }}</span>
    @endif
</a>
