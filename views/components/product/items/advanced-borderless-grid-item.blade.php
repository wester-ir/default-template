@php
    $imageHeight ??= 240;
    $showQuantitySettings = settingService('product')['show_quantity'];
@endphp

<a href="{{ $product->url }}" class="flex flex-col h-full bg-white rounded-lg transition-all overflow-hidden">
    <div class="flex flex-col relative flex-1">
        @include('templates.default.views.components.product.items.partials.tags')

        <img src="{{ $product->image['url']['thumbnail'] }}" alt="{{ $product->title }}" class="w-full object-cover rounded-md" style="height: {{ $imageHeight }}px;">

        <div class="flex flex-col flex-1 mt-3">
            <div class="text-sm">{{ $product->title }}</div>

            <div class="mt-auto">
                <div class="text-left font-medium mt-4">
                    @if ($product->is_quantity_unlimited || $product->quantity > 0)
                        <!-- Price -->
                        <div class="text-green-500">
                            {{ number_format($product->final_price) }}
                            {{ productCurrency()->label() }}
                        </div>
                    @else
                        <!-- Unavailable -->
                        <div class="text-red-500">
                            ناموجود
                        </div>
                    @endif
                </div>

                <!-- Colors -->
                @if ($product->advanced->hasVariantType('color'))
                    <div class="flex flex-row-reverse items-center mt-3">
                        @foreach ($product->advanced->colors() as $color)
                            <div class="w-3 h-3 border border-black/20 shadow rounded-full mr-2 last:mr-0" style="background-color: {{ $color->value }}"></div>
                        @endforeach
                    </div>
                @endif

                <!-- Show Quantity -->
                @if (! $product->is_quantity_unlimited && $showQuantitySettings['status'] && $showQuantitySettings['limit'] >= $product->quantity && $product->quantity > 0)
                    <div class="text-sm text-red-500 mt-3">
                        تنها {{ $product->quantity}} عدد باقی مانده است.
                    </div>
                @endif
            </div>
        </div>
    </div>
</a>
