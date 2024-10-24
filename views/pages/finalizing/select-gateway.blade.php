@template_extends('views.layouts.basic')

@title('پرداخت سفارش #'. $order->id, false)

@section('content')
    <div class="flex flex-col w-screen h-screen">
        <div class="md:border border-neutral-200 w-full md:w-[420px] rounded-xl m-auto">
            <div class="p-8">
                <div class="flex justify-center mb-5">
                    <a href="{{ route('client.index') }}" class="flex items-center justify-center">
                        <div class="flex items-center justify-center font-medium text-white w-10 h-10 rounded-lg bg-green-400">L</div>
                        <span class="font-medium ms-3 hidden md:block">{{ settingService('general')['name'] }}</span>
                    </a>
                </div>
    
                <div class="mt-9">
                    <h1 class="h2 text-center">پرداخت سفارش #{{ $order->id }}</h1>
                    <p class="text-center text-sm mt-2">لطفاً درگاه مورد نظر را انتخاب کنید.</p>
                </div>

                <div class="grid grid-cols-3 gap-3 mt-10">
                    @foreach ($gateways as $gateway)
                        <div data-id="{{ $gateway->id }}" data-role="gateway" data-is-active="{{ var_export($gateway->is_default) }}" class="flex items-center justify-center p-[2px] bg-white border border-neutral-200 rounded-lg overflow-hidden cursor-pointer data-[is-active=true]:border-green-400">
                            <img src="{{ $gateway->logo_url }}" class="w-full">
                        </div>
                    @endforeach
                </div>

                <hr class="my-5">

                <div class="text-center">
                    {{ number_format($order->invoice->total_amount) }} {{ productCurrency()->label() }}
                </div>

                <form action="{{ route('client.cart.finalizing.order.purchase', $order) }}" method="POST" class="form">
                    @csrf

                    <input type="hidden" name="gateway" value="{{ $gateways->isEmpty() ? null : $gateways[0]->id }}">
                    <button class="btn btn-success btn-lg w-full">ورود به درگاه پرداخت</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('bottom-scripts')
    <script>
        $('[data-role="gateway"]').click(function () {
            $('[data-role="gateway"]').attr('data-is-active', false);
            $(this).attr('data-is-active', true);
            $('input[name="gateway"]').val(
                $(this).data('id')
            );
        });
    </script>
@endpush
