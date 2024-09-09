@extends('templates.default.views.layouts.default')
@inject('productService', 'App\Services\ProductService')
@inject('schemaService', 'App\Services\SchemaService')

@title($product->title, false)
@description($product->content->summary)

@push('meta-tags')
    <meta name="product_id" content="{{ $product->id }}">
    <meta name="product_name" content="{{ $product->title }}">
    @if ($product->image)<meta property="og:image" content="{{ $product->image->url['medium'] }}">@endif
    <meta name="product_price" content="{{ $product->final_price }}">
    <meta name="product_old_price" content="{{ $product->price }}">
    <meta name="availability" content="{{ $product->is_quantity_unlimited || $product->quantity > 0 ? 'instock' : 'outofstock' }}">
@endpush

@push('head-scripts')
    <script type="application/ld+json">
        <?php
            echo $schemaService->toJSON([
                '@content' => 'https://schema.org',
                '@type' => 'Product',
                'name' => $product->title,
                'description' => $product->content->summary,
                'mpn' => $product->sku,
                'sku' => $product->sku,
                'image' => $product->images->map(fn ($image) => $image->url['medium']),
                'category' => $product->categories->map(fn ($category) => $category->url),
                'offer' => [
                    '@type' => 'Offer',
                    'availability' => $product->quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                    'price' => $product->final_price,
                    'priceCurrency' => 'IRR',
                ],
            ]);
        ?>
    </script>
    <script type="application/ld+json">
        <?php
            echo $schemaService->toJSON([
                '@content' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => $product->categories->map(function ($category, $index) {
                    return [
                        '@type' => 'ListItem',
                        'position' => $index + 1,
                        'name' => $category->name,
                        'item' => $category->url,
                    ];
                })
            ]);
        ?>
    </script>
@endpush

@section('raw-content')
    @include('templates.default.views.components.product.breadcrumb')
@endsection

@section('content')
    <div class="container">
        <div class="flex flex-col lg:flex-row lg:space-x-8 rtl:space-x-reverse">
            <!-- Images -->
            <div class="w-full lg:w-[400px] space-y-3">
                @if (! $product->images->isEmpty())
                    <div class="swiper product-image-slider">
                        <div class="swiper-wrapper rounded-lg">
                            @foreach ($product->images as $image)
                                <div class="swiper-slide">
                                    <img src="{{ $image->url['medium'] }}" class="w-full" alt="{{ $product->title }}">
                                </div>
                            @endforeach
                        </div>

                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-pagination"></div>
                    </div>

                    <div class="flex border border-neutral-200 rounded-lg p-1">
                        <div thumbsSlider="" class="swiper product-image-thumb-slider">
                            <div class="swiper-wrapper">
                                @foreach ($product->images as $image)
                                    <div class="swiper-slide">
                                        <img
                                            src="{{ $image->url['thumbnail'] }}"
                                            class="object-cover rounded-md w-20 h-20" alt="{{ $product->title }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-neutral-200 flex items-center justify-center aspect-square rounded-lg">
                        بدون عکس
                    </div>
                @endif
            </div>

            <!-- Details -->
            <div class="flex-1 mt-5 lg:mt-0">
                <div class="flex flex-col lg:flex-row justify-center lg:justify-between lg:items-center">
                    <h1 class="text-2xl font-medium">{{ $product->title }}</h1>

                    <div class="flex items-center justify-end space-x-4 space-x-reverse mt-3 lg:mt-0 mr-5">
                        @if ($product->is_shipping_free)
                            <span class="badge bg-red-400 text-white">ارسال رایگان</span>
                        @endif

                        <!-- Like Button -->
                        @include('templates.default.views.components.product.like-button', [
                            'id' => $product->id,
                            'status' => $product->is_liked,
                        ])
                    </div>
                </div>
                <div class="text-neutral-400 text-lg mt-2">
                    <span>کد محصول:</span>
                    <span class="text-neutral-700">{{ $product->sku }}</span>
                </div>
                
                <!-- Brands -->
                @if (! $product->brands->isEmpty())
                    <div class="text-neutral-400 text-lg mt-2">
                        <span>برند:</span>
                        <span>
                            @foreach ($product->brands as $index => $brand)
                                <a class="text-neutral-700" href="{{ route('client.brands.brand', $brand) }}">{{ $brand->name }}</a>@if ($index + 1 < $product->brands->count())<span>،</span>@endif
                            @endforeach
                        </span>
                    </div>
                @endif

                <!-- Description -->
                @if ($product->content->description)
                    <section class="font-light mt-5">
                        <div class="text-neutral-600 [&>ul]:ps-5 [&>ul>li]:list-disc">
                            {!! $product->content->description !!}
                        </div>
                    </section>
                @endif

                <!-- Combinations -->
                @include('templates.default.views.components.product.combinations')

                <!-- Price -->
                <div data-role="price-container" class="mt-8 hidden">
                    <span data-role="final-price" class="h2 text-green-500">{{ number_format($product->final_price) }} {{ productCurrency()->label() }}</span>

                    @if ($product->discount > 0)
                        <span data-role="raw-price" class="h3 line-through text-neutral-400 ms-2">{{ number_format($product->price) }} {{ productCurrency()->label() }}</span>
                    @endif
                </div>

                <div class="mt-7">
                    <!-- Add to cart -->
                    @include('templates.default.views.components.product.add-to-cart')

                    @if ($product->points > 0)
                        <div data-role="points" class="font-light text-sm text-green-700 mt-3 hidden">
                            با خرید این محصول <span class="font-bold">{{ number_format($product->points) }} امتیاز</span> دریافت کنید.
                        </div>
                    @endif

                    @if (! $product->is_quantity_unlimited && settingService('product')['show_quantity']['status'])
                        <div data-role="quantity-container" class="text-danger mt-5 hidden">
                            تنها <span data-role="quantity">{{ $product->quantity }}</span> عدد باقی مانده است.
                        </div>
                    @endif

                    <div data-role="unavailable" class="font-medium text-danger mt-8 hidden">
                        متاسفانه محصول مورد نظر موجود نمی باشد.
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-5">

        <div class="space-y-10">
            <!-- Content Tabs -->
            @include('templates.default.views.components.product.content-tabs')

            <!-- Comments -->
            @include('templates.default.views.components.product.comments')
        </div>

        <!-- Related Products -->
        @include('templates.default.views.components.product.related-products')
    </div>
@endsection

@push('bottom-scripts')
    <link rel="stylesheet" href="{{ template_asset('/assets/js/libs/swiper/swiper-bundle.min.css') }}" />
    <script src="{{ template_asset('/assets/js/libs/swiper/swiper-bundle.min.js') }}"></script>
    @if ($product->image)
        <script>
            var thumbSwiper = new Swiper(".product-image-thumb-slider", {
                spaceBetween: 4,
                slidesPerView: 4,
                watchSlidesProgress: true,
            });

            var swiper = new Swiper(".product-image-slider", {
                cssMode: true,
                pagination: {
                    el: ".swiper-pagination",
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                thumbs: {
                    swiper: thumbSwiper,
                },
            });
        </script>
    @endif
    <script>
        ready(function () {
            selectCombination('{{ $product->combinations[0]->uid }}');
        });

        var swiper = new Swiper("#related-products", {
            slidesPerView: "auto",
            spaceBetween: 20,
            freeMode: true,
        });
    </script>
@endpush
