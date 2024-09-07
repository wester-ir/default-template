@extends('templates.default.views.layouts.basic')

@title('غیرفعال', false)

@section('content')
    <div class="container flex flex-col items-center justify-center my-auto">
        <h1 class="h2 text-center mb-2">سایت غیرفعال می باشد</h1>

        @if ($message = settingService('general')['inactivity_message'])
            <p>{{ $message }}</p>
        @else
            <p>بزودی باز خواهیم گشت...</p>
        @endif
    </div>
@endsection
