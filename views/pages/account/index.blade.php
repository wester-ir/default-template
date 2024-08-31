@extends('templates.default.views.layouts.default')

@title('حساب کاربری')

@section('content')
    <div class="container">
        <div class="flex flex-col md:flex-row space-y-5 md:space-y-0">
            <!-- Sidebar -->
            @include('templates.default.views.pages.account.partials.sidebar')

            <!-- Content -->
            <div class="flex-1 md:mr-5">
                <div class="border border-neutral-200 rounded-lg p-5">
                    <div class="flex items-center justify-between">
                        <h3>سفارش ها</h3>

                        <a href="{{ route('client.account.orders.index') }}" class="font-light text-sm">همه سفارش ها</a>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-x-5 gap-y-8 mt-8">
                        <a href="{{ route('client.account.orders.index', ['type' => 'pending']) }}" class="flex flex-col items-center justify-center">
                            <img src="{{ template_asset('/assets/img/icons/invoice.svg') }}" width="50" height="50">
                            <div class="mt-3">در انتظار پرداخت</div>
                            <span class="badge badge-light mt-3">{{ $orderStats->pending }}</span>
                        </a>

                        <a href="{{ route('client.account.orders.index', ['type' => 'paid']) }}" class="flex flex-col items-center justify-center">
                            <img src="{{ template_asset('/assets/img/icons/cart-colorful.svg') }}" width="50" height="50">
                            <div class="mt-3">در حال پردازش</div>
                            <span class="badge badge-warning mt-3">{{ $orderStats->paid }}</span>
                        </a>

                        <a href="{{ route('client.account.orders.index', ['type' => 'shipped']) }}" class="flex flex-col items-center justify-center">
                            <img src="{{ template_asset('/assets/img/icons/order-shipped.svg') }}" width="50" height="50">
                            <div class="mt-3">ارسال شده</div>
                            <span class="badge badge-success mt-3">{{ $orderStats->shipped }}</span>
                        </a>

                        <a href="{{ route('client.account.orders.index', ['type' => 'returned']) }}" class="flex flex-col items-center justify-center">
                            <img src="{{ template_asset('/assets/img/icons/order-returned.svg') }}" width="50" height="50">
                            <div class="mt-3">مرجوع شده</div>
                            <span class="badge badge-light mt-3">{{ $orderStats->returned }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
