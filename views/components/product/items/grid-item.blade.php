@php
    $showQuantitySettings = settingService('product')['show_quantity'];
@endphp

<a href="{{ $product->url }}" class="flex flex-col h-full bg-white border border-neutral-200 hover:shadow-md hover:shadow-neutral-100 rounded-xl transition-all overflow-hidden">
    <div class="flex flex-col relative flex-1 m-2">
        @include('templates.default.views.components.product.items.partials.tags')
        <img src="{{ $product->image['url']['thumbnail'] }}" alt="{{ $product->title }}" class="w-full h-60 object-cover rounded-lg">
    
        <div class="flex flex-col flex-1 mt-3">
            <!-- Title -->
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
