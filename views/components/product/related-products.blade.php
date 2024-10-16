@php
    $relatedProducts = $productService->getRelated($product);
@endphp
@if (! $relatedProducts->isEmpty())
    <div class="mt-10">
        <h3>محصولات مرتبط</h3>

        <div class="swiper select-none mt-3" id="related-products">
            <div class="swiper-wrapper">
                @foreach ($relatedProducts as $relatedProduct)
                    <div class="swiper-slide w-[230px] h-auto">
                        @template_include('views.components.product.items.grid-item', [
                            'product' => $relatedProduct,
                        ])
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
