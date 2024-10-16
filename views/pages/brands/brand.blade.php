@extends('templates.default.views.layouts.default')

@php
    $title = 'برند '. $brand->name . ' — '. $brand->latin_name;
@endphp
@title([$brand->page_title ?: $title, 'صفحه '. $products->currentPage()], false)
@description($brand->meta_description)

@section('content')
    <div class="container">
        <h1 class="h2">{{ $title }}</h1>

        @if (! $products->isEmpty())
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 mt-5">
                @foreach ($products as $product)
                    @template_include('views.components.product.items.grid-item')
                @endforeach
            </div>
        @else
            <div class="flex items-center justify-center font-light pt-16">
                محصولی برای نمایش وجود ندارد.
            </div>
        @endif

        {{ $products->links() }}

        @if ($brand->description)
            <div class="mt-10 text-sm leading-7">
                {!! $brand->description !!}
            </div>
        @endif
    </div>
@endsection
