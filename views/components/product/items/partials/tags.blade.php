@if ($product->is_shipping_free || $product->discount > 0)
    <div class="absolute top-2 left-2 space-x-1">
        @if ($product->is_shipping_free)
            <span class="bg-red-400 text-white rounded-md px-2 py-1 shadow text-xs">ارسال رایگان</span>
        @endif

        @if ($product->discount > 0)
            <span class="bg-green-400 text-white rounded-md px-2 py-1 shadow text-xs">تخفیف دار</span>
        @endif
    </div>
@endif
