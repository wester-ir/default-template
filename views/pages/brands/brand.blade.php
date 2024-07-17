@extends('templates.default.views.layouts.default')

@php
    $titleWithoutSiteName = true;
    $title = 'برند '. $brand->name . ' — '. $brand->latin_name;
@endphp
@section('title', $brand->page_title ?: $title)
@section('description', $brand->meta_description)

@section('content')
    <div class="container">
        <h1 class="h2">{{ $title }}</h1>

        @if (! $products->isEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 mt-5">
                @foreach ($products as $product)
                    @include('templates.default.views.components.product.items.advanced-grid-item')
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
