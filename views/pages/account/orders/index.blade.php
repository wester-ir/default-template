@extends('templates.default.views.layouts.default')

@title('سفارش ها')

@section('content')
    <div class="container">
        <div class="flex flex-col md:flex-row space-y-5 md:space-y-0">
            <!-- Sidebar -->
            @template_include('views.pages.account.partials.sidebar')

            <!-- Content -->
            <div class="flex-1 w-full md:w-0 md:mr-5">
                <div class="border border-neutral-200 rounded-lg p-5">
                    <div class="flex items-center justify-between">
                        <h3>سفارش ها</h3>
                    </div>

                    <div class="mt-5 -mx-5 -mb-5">
                        <div class="orders-tabs flex items-center border-b border-neutral-200 px-5 font-light text-sm md:text-base overflow-x-auto whitespace-nowrap hide-scrollbar">
                            <a id="tab-pending" href="{{ route('client.account.orders.index', ['type' => 'pending']) }}" data-active="{{ as_string($type === 'pending') }}" class="py-2 px-3">
                                <span>در انتظار پرداخت</span>
                                @if ($orderStats->pending !== 0)
                                    <span class="badge badge-secondary py-px mr-1">{{ $orderStats->pending }}</span>
                                @endif
                            </a>
                            <a id="tab-paid" href="{{ route('client.account.orders.index', ['type' => 'paid']) }}" data-active="{{ as_string($type === 'paid') }}" class="py-2 px-3">
                                <span>در حال پردازش</span>
                                @if ($orderStats->paid !== 0)
                                    <span class="badge badge-secondary py-px mr-1">{{ $orderStats->paid }}</span>
                                @endif
                            </a>
                            <a id="tab-shipped" href="{{ route('client.account.orders.index', ['type' => 'shipped']) }}" data-active="{{ as_string($type === 'shipped') }}" class="py-2 px-3">
                                <span>ارسال شده</span>
                                @if ($orderStats->shipped !== 0)
                                    <span class="badge badge-secondary py-px mr-1">{{ $orderStats->shipped }}</span>
                                @endif
                            </a>
                            <a id="tab-cancelled" href="{{ route('client.account.orders.index', ['type' => 'cancelled']) }}" data-active="{{ as_string($type === 'cancelled') }}" class="py-2 px-3">
                                <span>لغو شده</span>
                                @if ($orderStats->cancelled !== 0)
                                    <span class="badge badge-secondary py-px mr-1">{{ $orderStats->cancelled }}</span>
                                @endif
                            </a>

                            <a id="tab-returned" href="{{ route('client.account.orders.index', ['type' => 'returned']) }}" data-active="{{ as_string($type === 'returned') }}" class="py-2 px-3">
                                <span>مرجوع شده</span>
                                @if ($orderStats->returned !== 0)
                                    <span class="badge badge-secondary py-px mr-1">{{ $orderStats->returned }}</span>
                                @endif
                            </a>
                        </div>

                        <div class="divide-y divide-neutral-200">
                            @foreach ($orders as $order)
                                <a href="{{ route('client.account.orders.order.index', $order) }}" class="block">
                                    <!-- Head -->
                                    <div class="p-5 border-b border-neutral-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                @php
                                                    $orderIcon = match ($order->status->name) {
                                                        'Pending' => 'clock.svg',
                                                        'Paid' => 'checklist.svg',
                                                        'Shipped' => 'order-shipped.svg',
                                                        'Cancelled', 'Expired' => 'cancel.svg',
                                                        'Returned' => 'order-returned.svg',
                                                    };
                                                @endphp
                                                <img src="{{ template_asset('/assets/img/icons/'. $orderIcon) }}" class="w-6 h-6">
                                                <div class="font-medium mr-3">{{ $order->status->label() }}</div>
                                            </div>

                                            <span class="icon icon-arrow-light-left w-3 h-3"></span>
                                        </div>

                                        <div class="flex flex-col md:flex-row md:items-center md:justify-between text-sm mt-3">
                                            <div class="flex flex-col md:flex-row md:items-center space-y-3 md:space-y-0 md:space-x-6 md:space-x-reverse">
                                                <div class="flex items-center justify-between md:justify-start">
                                                    <span class="font-light text-neutral-400">کد سفارش</span>
                                                    <span class="font-medium mr-2">{{ $order->id }}</span>
                                                </div>
                                                @if ($order->is_paid || $order->is_shipped || $order->is_returned)
                                                    <div class="flex items-center justify-between md:justify-start">
                                                        <span class="font-light text-neutral-400">پرداخت شده در</span>
                                                        <span class="font-medium mr-2">{{ $order->paid_at->toJalali()->format('%d %B %Y') }}</span>
                                                    </div>
                                                @endif
                                                @if ($order->is_shipped)
                                                    <div class="flex items-center justify-between md:justify-start">
                                                        <span class="font-light text-neutral-400">ارسال شده در</span>
                                                        <span class="font-medium mr-2">{{ $order->shipped_at->toJalali()->format('%d %B %Y') }}</span>
                                                    </div>
                                                @endif
                                                @if ($order->is_cancelled)
                                                    <div class="flex items-center justify-between md:justify-start">
                                                        <span class="font-light text-neutral-400">لغو شده در</span>
                                                        <span class="font-medium mr-2">{{ $order->cancelled_at->toJalali()->format('%d %B %Y') }}</span>
                                                    </div>
                                                @endif
                                                @if ($order->is_returned)
                                                    <div class="flex items-center justify-between md:justify-start">
                                                        <span class="font-light text-neutral-400">مرجوع شده در</span>
                                                        <span class="font-medium mr-2">{{ $order->returned_at->toJalali()->format('%d %B %Y') }}</span>
                                                    </div>
                                                @endif
                                                <div class="flex items-center justify-between md:justify-start">
                                                    <span class="font-light text-neutral-400">مبلغ</span>
                                                    <span class="font-medium mr-2">{{ number_format($order->invoice->total_amount) }} {{ productCurrency()->label() }}</span>
                                                </div>
                                            </div>

                                            @if ($order->is_pending)
                                                <div class="flex items-center">
                                                    <div class="flex items-center font-light text-sm text-red-500">
                                                        زمان باقی مانده: <div class="flex items-center font-medium w-12 mx-1" style="direction: ltr;" data-role="countdown" data-seconds="{{ $order->expires_at->timestamp - now()->timestamp }}"></div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Items -->
                                    <div class="flex space-x-3 space-x-reverse p-5">
                                        @foreach ($order->items->take(5) as $item)
                                            <div>
                                                @if ($item->relationLoaded('combination') && $item->combination->relationLoaded('image') && $item->combination->image)
                                                    <img src="{{ $item->combination->image['url']['thumbnail'] }}" class="w-24 h-24 object-cover rounded-md" title="{{ $item->meta['title'] }}" alt="{{ $item->meta['title'] }}">
                                                @else
                                                    <img src="{{ template_asset('assets/img/no-image.jpg') }}" class="w-24 h-24 object-cover rounded-md">
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </a>
                            @endforeach

                            @if ($orders->count() === 0)
                                <div class="p-5 text-center font-light text-neutral-500">سفارشی موجود نیست.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('bottom-scripts')
    <script>
        scrollToTab('tab-{{ $type }}');
    </script>
@endpush
