@if ($product->is_shipping_free)
    <div class="absolute top-2 left-2 bg-red-400 text-white rounded-md px-2 py-2 md:py-1 text-xs shadow">
        <i class="fi fi-rr-shipping-fast flex md:hidden"></i>
        <span class="hidden md:flex">ارسال رایگان</span>
    </div>
@endif

@if ($product->discount > 0)
    <div class="absolute top-2 right-2 bg-green-400 text-white rounded-md px-2 py-2 md:py-1 text-xs shadow">
        <i class="fi fi-rr-percentage flex md:hidden"></i>
        <span class="hidden md:flex font-medium">%{{ $product->discount_percentage }}</span>
    </div>
@endif
