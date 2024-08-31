@php
    $pendingOrder = get_pending_order();
@endphp

@if ($pendingOrder)
    <div class="container mb-5">
        <div class="flex flex-col md:flex-row justify-between border border-red-300 rounded-md px-5 py-3">
            <div class="space-y-1">
                <div class="text-red-500 font-medium">یک سفارش در انتظار پرداخت دارید.</div>
                <div class="text-sm">لطفاً نسبت به پرداخت سفارش خود اقدام و یا لغو کنید.</div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-center mt-4 md:mt-0">
                <div class="flex items-center">
                    <div class="text-sm px-3">{{ number_format($pendingOrder->invoice->total_amount) }} {{ productCurrency()->label() }}</div>
                    <div class="flex items-center justify-center font-light text-sm px-3 border-r border-neutral-200">
                        <div class="flex items-center justify-center font-medium text-red-500 w-12" style="direction: ltr;" data-role="countdown" data-seconds="{{ $pendingOrder->expires_at->timestamp - now()->timestamp }}"></div>
                    </div>
                    
                    @if (auth()->check())
                        <a href="{{ route('client.account.orders.order.index', $pendingOrder) }}" class="text-sm font-medium text-sky-500 px-3">مشاهده</a>
                    @endif

                    <form action="{{ route('client.cart.finalizing.order.cancel', $pendingOrder) }}" method="POST" onsubmit="return modal.defaults.confirmDanger(() => this.submit())">
                        @csrf
                        @method('PATCH')

                        <button class="text-sm font-medium text-danger px-3 border-r border-neutral-200">لغو سفارش</button>
                    </form>
                </div>

                <form class="mt-2 sm:mt-0 sm:mr-2" action="{{ route('client.cart.finalizing.order.purchase', $pendingOrder) }}" method="POST">
                    @csrf

                    <button class="btn btn-success btn-sm">پرداخت سفارش</button>
                </form>
            </div>
        </div>
    </div>
@endif
