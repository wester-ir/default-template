@extends('templates.default.views.layouts.default')

@title('سفارش تایید شده')

@section('content')
    <div class="container flex flex-col items-center justify-center my-auto">
        <img src="{{ template_asset('assets/img/success.svg') }}" class="w-24 h-24 mb-4">
        <h2 class="text-green-600">قبلاً ثبت شده!</h2>
        <div>سفارش مورد نظر قبلاً تایید شده است.</div>
        <div class="mt-2">کد سفارش: {{ $order->id }}</div>

        @auth
            <a href="{{ route('client.account.orders.order.index', $order) }}" class="btn btn-success mt-3">مشاهده سفارش</a>
        @endauth
    </div>
@endsection
