@extends('templates.default.views.layouts.default')
@inject('productService', 'App\Services\ProductService')

@title('پاسخ ناقض')

@section('content')
    <div class="container flex flex-col items-center justify-center my-auto">
        <img src="{{ template_asset('assets/img/payment-failed.svg') }}" class="w-24 h-24 mb-4">
        <h2>پاسخ ناقض</h2>
        <div>پارامتر های ارسالی توسط درگاه ناقض می باشند.</div>
        <div class="mt-2">کد سفارش: {{ $order->id }}</div>
    </div>
@endsection
