@extends('templates.default.views.layouts.default')
@inject('productService', 'App\Services\ProductService')

@title('تراکنش ناموفق')

@section('content')
    <div class="container flex flex-col items-center justify-center my-auto">
        <img src="{{ template_asset('assets/img/payment-failed.svg') }}" class="w-24 h-24 mb-4">
        <h2>تراکنش ناموفق</h2>
        <div>پرداخت سفارش مورد نظر ناموفق بود.</div>
        <div class="mt-2">کد سفارش: {{ $order->id }}</div>
    </div>
@endsection
