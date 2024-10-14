@extends('templates.default.views.layouts.default')

@title('خطای درگاه پرداخت')

@section('content')
    <div class="container flex flex-col items-center justify-center my-auto">
        <img src="{{ template_asset('assets/img/payment-failed.svg') }}" class="w-24 h-24 mb-4">
        <h2>خطای درگاه پرداخت</h2>
        <div>درگاه پرداخت مورد نظر یافت نشد.</div>
        <div class="mt-2">کد سفارش: {{ $order->id }}</div>
    </div>
@endsection
