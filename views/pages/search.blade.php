@extends('templates.default.views.layouts.default')

@section('title', $keyword)

@section('content')
    <div class="container">
        <h1 class="h2">{{ $keyword }}</h1>

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
    </div>
@endsection
