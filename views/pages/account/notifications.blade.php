@extends('templates.default.views.layouts.default')

@title('اعلان ها', false)

@section('content')
    <div class="container">
        <div class="flex flex-col md:flex-row space-y-5 md:space-y-0">
            <!-- Sidebar -->
            @include('templates.default.views.pages.account.partials.sidebar')

            <!-- Content -->
            <div class="flex-1 md:mr-5">
                <div class="border border-neutral-200 rounded-lg p-5">
                    <div class="flex items-center justify-between">
                        <h3>اعلان ها</h3>
                    </div>

                    <div class="mt-5 divide-y">
                        @foreach ($notifications as $notification)
                            @php
                                if ($notification->type_name === 'order_paid') {
                                    $icon = 'checklist.svg';
                                    $title = "سفارش <div class=\"ltr-direction mx-1\">#{$notification->order->id}</div> با موفقیت پرداخت شد.";
                                    $message = 'کد رهگیری مرسوله پس از ارسال سفارش برایتان فرستاده خواهد شد.';
                                } elseif ($notification->type_name === 'order_shipped') {
                                    $icon = 'order-shipped.svg';
                                    $title = "سفارش <div class=\"ltr-direction mx-1\">#{$notification->order->id}</div> تکمیل و ارسال شد.";
                                    if ($trackingNumber = $notification->order->address->tracking_number) {
                                        $message = 'کد رهگیری مرسوله: '. $trackingNumber;
                                    } else {
                                        $message = '';
                                    }
                                }
                            @endphp
                            <div class="flex py-5 first:pt-0 last:pb-0">
                                <div>
                                    <img src="{{ template_asset('/assets/img/icons/'. $icon) }}" class="w-6 h-6">
                                </div>
                                <div class="flex-1 mr-3">
                                    <div class="flex items-center font-medium">{!! $title !!}</div>
                                    @if ($message)
                                        <div class="text-neutral-500 mt-2 font-light text-sm">{{ $message }}</div>
                                    @endif

                                    <div class="flex items-center mt-3 space-x-3 space-x-reverse">
                                        @foreach ($notification->order->items->take(3) as $item)
                                            <div>
                                                <img src="{{ $item->combination->image->url['thumbnail'] }}" class="w-16 h-16 object-cover rounded-md">
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="flex items-center justify-between mt-3">
                                        <a href="{{ route('client.account.orders.order.index', $notification->order->id) }}" class="flex items-center text-sky-500 text-sm">
                                            مشاهده سفارش
                                            <span class="icon icon-arrow-light-left bg-sky-500 w-2 h-2 mr-2"></span>
                                        </a>
                                        <span class="font-light text-neutral-500 text-xs">{{ $notification->created_at->toJalali()->format('%d %B %Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if ($notifications->isEmpty())
                            <div class="text-center font-light">هیچ اعلانی ندارید.</div>
                        @endif
                    </div>
                </div>

                {{ $notifications->links() }}
            </div>
        </div>
    </div>
@endsection
