@if ($product->is_shipping_free)
    <div class="absolute flex items-center justify-center top-2 right-2 bg-red-400 text-white rounded-md px-2 md:px-1 h-7 md:h-6 text-xs shadow">
        <i class="fi fi-rr-shipping-fast flex md:hidden"></i>
        <span class="hidden md:flex">ارسال رایگان</span>
    </div>
@endif

@if ($product->discount > 0)
    <div class="absolute flex items-center justify-center top-2 left-2 bg-green-400 text-white rounded-md px-2 h-7 md:h-6 text-xs shadow">
        <span class="font-medium text-lg pt-[2px]">٪{{ $product->discount_percentage }}</span>
    </div>
@endif
