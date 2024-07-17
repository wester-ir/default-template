@extends('templates.default.views.layouts.basic')

@section('title', 'تایید شماره موبایل')

@section('content')
    <div class="flex flex-col w-screen h-screen">
        <div class="md:border border-neutral-200 w-full md:w-[420px] rounded-xl m-auto">
            <div class="p-8">
                <div class="flex justify-center mb-5">
                    <a href="{{ route('client.index') }}" class="flex items-center justify-center">
                        <div class="flex items-center justify-center font-medium text-white w-10 h-10 rounded-lg bg-green-400">L</div>
                        <span class="font-medium ms-3 hidden md:block">{{ settingService('general')['name'] }}</span>
                    </a>
                </div>

                <div class="mt-9">
                    <h1 class="h2 text-center">تایید شماره موبایل</h1>
                    <p class="text-center text-sm mt-2">یک کد تایید به شماره <b>{{ $verification['number'] }}</b> ارسال شد.</p>

                    <div class="flex justify-center text-sm mt-3 mb-2 divide-x-2 divide-x-reverse font-light">
                        <a href="{{ route('auth.login') }}" class="px-3 link">تغییر شماره</a>

                        <form class="px-3 select-none" action="{{ route('auth.login') }}" method="POST">
                            @csrf

                            <input type="hidden" name="number" value="{{ $verification['number'] }}">
                            <input type="hidden" name="force_create" value="1">
                            <button class="@if (! $canResend) pointer-events-none @endif link" id="resend">
                                <span class="@if (! $canResend) hidden @endif" id="resend-text">ارسال مجدد</span>
                                <span id="resend-timer" class="flex items-center @if ($canResend) hidden @endif">ارسال مجدد پس از <div class="text-center w-8" data-countdown>{{ $seconds }}</div> ثانیه</span>
                            </button>
                        </form>
                    </div>
                </div>

                <form action="{{ route('auth.login.verify-number') }}" method="POST" class="mt-5 form">
                    @csrf

                    <div class="form-control">
                        <input type="text" id="code" maxlength="5" class="default ltr-direction text-center px-4 h-[42px]" name="code" value="" placeholder="×××××">
                        @include('templates.default.views.components.input-error', [
                            'messages' => $errors->get('code'),
                        ])
                    </div>

                    <button class="btn btn-success btn-lg w-full">ادامه</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('bottom-scripts')
    <script>
        var countdownContainer = document.querySelector('[data-countdown]');

        function countdown() {
            var secs = Number(countdownContainer.innerText) - 1;

            countdownContainer.innerText = secs;

            if (secs > 0) {
                setTimeout(countdown, 1000);
            } else {
                document.getElementById('resend').classList.remove('pointer-events-none');
                document.getElementById('resend-text').classList.remove('hidden');
                document.getElementById('resend-timer').classList.add('hidden');
            }
        }

        @if (! $canResend)
            setTimeout(countdown, 1000);
        @endif
    </script>
@endpush
