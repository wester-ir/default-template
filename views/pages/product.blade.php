@template_extends('views.layouts.default')
@inject('productService', 'App\Services\ProductService')

@title($product->title, false)
@description($product->content->summary ?: $product->content->description)

@section('raw-content')
    @template_include('views.components.product.breadcrumb')
@endsection

@push('meta-tags')
    @if ($product->image)
        <meta property="og:image" content="{{ $product->image->url['medium'] }}">
        <meta property="twitter:image" content="{{ $product->image->url['medium'] }}">
    @endif
@endpush

@section('content')
    <div class="container">
        <div class="flex flex-col lg:flex-row lg:space-x-8 rtl:space-x-reverse">
            <!-- Images -->
            <div class="w-full lg:w-[400px] space-y-3">
                @if (! $product->images->isEmpty())
                    <div class="swiper product-image-slider rounded-lg">
                        <div class="swiper-wrapper">
                            @foreach ($product->images as $image)
                                <div class="swiper-slide h-auto">
                                    <div class="swiper-zoom-container">
                                        <img src="{{ $image->url['medium'] }}" class="w-full object-cover h-full" alt="{{ $product->title }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-pagination"></div>
                    </div>

                    <div class="border border-neutral-200 rounded-lg p-1">
                        <div thumbsSlider="" class="swiper product-image-thumb-slider">
                            <div class="swiper-wrapper">
                                @foreach ($product->images as $image)
                                    <div class="swiper-slide" data-image-id="{{ $image->id }}">
                                        <img
                                            src="{{ $image->url['thumbnail'] }}"
                                            class="object-cover rounded-md w-full h-20 cursor-pointer" alt="{{ $product->title }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <img src="{{ $image->url['medium'] ?? template_asset('assets/img/no-image.jpg') }}" class="w-full" alt="{{ $product->title }}">
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
                        @template_include('views.components.product.like-button', [
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
                    <section class="font-normal mt-5">
                        <div class="[&>ul]:ps-5 [&>ul>li]:text-black [&>ul>li>strong]:font-light [&>ul>li>strong]:text-neutral-600 [&>ul>li]:list-disc marker:text-neutral-400 leading-[30px]">
                            {!! $product->content->description !!}
                        </div>
                    </section>
                @endif

                <!-- Combinations -->
                @template_include('views.components.product.combinations')

                <!-- Price -->
                <div data-role="price-container" class="mt-8 hidden">
                    <span data-role="final-price" class="h2 text-green-500">{{ number_format($product->final_price) }} {{ productCurrency()->label() }}</span>

                    @if ($product->discount > 0)
                        <span data-role="raw-price" class="h3 line-through text-neutral-400 ms-2">{{ number_format($product->price) }} {{ productCurrency()->label() }}</span>
                    @endif
                </div>

                <div class="mt-7">
                    <!-- Add to cart -->
                    @template_include('views.components.product.add-to-cart')

                    @if ($product->points > 0)
                        <div data-role="points" class="font-light text-sm text-green-700 mt-3 hidden">
                            با خرید این محصول <span class="font-bold">{{ number_format($product->points) }} امتیاز</span> دریافت کنید.
                        </div>
                    @endif

                    <template id="template-max-available-quantity-message">
                        <div data-role="max-available-quantity" class="text-danger mt-5">
                            تنها <span data-role="quantity"></span> عدد باقی مانده است.
                        </div>
                    </template>

                    <template id="template-unavailable-message">
                        <div data-role="unavailable" class="font-medium text-danger mt-8">
                            متاسفانه محصول مورد نظر موجود نمی باشد.
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <hr class="my-5">

        <div class="space-y-10">
            <!-- Content Tabs -->
            @template_include('views.components.product.content-tabs')

            <!-- Comments -->
            @template_include('views.components.product.comments')
        </div>

        <!-- Related Products -->
        @template_include('views.components.product.related-products')
    </div>
@endsection

@push('bottom-scripts')
    <link rel="stylesheet" href="{{ template_versioned_asset('/assets/js/libs/swiper/swiper-bundle.min.css') }}" />
    <script src="{{ template_versioned_asset('/assets/js/libs/swiper/swiper-bundle.min.js') }}"></script>
    @if ($product->image)
        <script>
            var thumbSwiper = new Swiper(".product-image-thumb-slider", {
                spaceBetween: 4,
                slidesPerView: 4,
                freeMode: true,
                watchSlidesProgress: true,
            });

            var swiper = new Swiper(".product-image-slider", {
                zoom: true,
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

        new Swiper("#related-products", {
            slidesPerView: "auto",
            spaceBetween: 20,
            freeMode: true,
        });
    </script>
@endpush
