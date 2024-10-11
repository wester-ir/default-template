@extends('templates.default.views.layouts.default')

@section('content')
    <div class="container">
        <div class="space-y-8">
            <!-- Latest Products -->
            <section class="section">
                <div class="heading">
                    <div class="title">
                        <h2>جدیدترین ها</h2>
                    </div>

                    <a href="{{ route('client.latest', ['page' => $latestProducts->hasMorePages() ? $latestProducts->currentPage() + 1 : null]) }}" class="flex items-center border border-green-500 text-green-500 hover:text-white hover:bg-green-500 py-[6px] px-4 rounded-full transition-colors">
                        <span>بیشتر</span>
                        <i class="fi fi-rr-angle-small-left text-lg flex -ml-[6px] mr-1"></i>
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 mt-5">
                    @foreach ($latestProducts as $product)
                        @include('templates.default.views.components.product.items.grid-item')
                    @endforeach
                </div>

                <div class="flex justify-center mt-10">
                    <a href="{{ route('client.latest', ['page' => $latestProducts->hasMorePages() ? $latestProducts->currentPage() + 1 : null]) }}" class="btn btn-success btn-lg rounded-full">نمایش همه محصولات</a>
                </div>
            </section>

            <!-- Best-Selling Products -->
            <section class="section">
                <div class="heading">
                    <div class="title">
                        <h2>پرفروش ترین ها</h2>
                    </div>

                    <a href="{{ route('client.best-selling', ['page' => $bestSellingProducts->hasMorePages() ? $bestSellingProducts->currentPage() + 1 : null]) }}" class="flex items-center border border-green-500 text-green-500 hover:text-white hover:bg-green-500 py-[6px] px-4 rounded-full transition-colors">
                        <span>بیشتر</span>
                        <i class="fi fi-rr-angle-small-left text-lg flex -ml-[6px] mr-1"></i>
                    </a>
                </div>
                <div class="swiper select-none mt-5" id="best-selling-products">
                    <div class="swiper-wrapper">
                        @foreach ($bestSellingProducts as $product)
                            <div class="swiper-slide w-[260px] h-auto">
                                @include('templates.default.views.components.product.items.advanced-borderless-grid-item', [
                                    'imageHeight' => 300,
                                ])
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('bottom-scripts')
    <link rel="stylesheet" href="{{ template_asset('/assets/js/libs/swiper/swiper-bundle.min.css') }}" />
    <script src="{{ template_asset('/assets/js/libs/swiper/swiper-bundle.min.js') }}"></script>
    <script>
        var swiper = new Swiper("#best-selling-products", {
            slidesPerView: "auto",
            spaceBetween: 20,
            freeMode: true,
        });
    </script>
@endpush
