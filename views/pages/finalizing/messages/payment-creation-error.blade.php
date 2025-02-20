@template_extends('views.layouts.default')

@title('خطای درگاه پرداخت')

@section('content')
    <div class="container flex flex-col items-center justify-center my-auto">
        <img src="{{ template_asset('assets/img/payment-failed.svg') }}" class="w-24 h-24 mb-4">
        <h2>خطای درگاه پرداخت</h2>
        <div>مشکلی در ارتباط با درگاه پرداخت رخ داد.</div>

        @if (session()->has('error'))
            <div class="mt-2">{{ session('error') }}</div>
        @endif
    </div>
@endsection
