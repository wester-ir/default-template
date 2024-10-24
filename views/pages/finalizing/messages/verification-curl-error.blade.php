@template_extends('views.layouts.default')

@title('خطای ارتباطی')

@section('content')
    <div class="container flex flex-col items-center justify-center my-auto">
        <img src="{{ template_asset('assets/img/payment-failed.svg') }}" class="w-24 h-24 mb-4">
        <h2>خطای ارتباطی</h2>
        <div>مشکلی در ارتباط با درگاه پرداخت رخ داد.</div>
        <div class="mt-2">کد سفارش: {{ $order->id }}</div>
    </div>
@endsection
