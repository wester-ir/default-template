@extends('templates.default.views.layouts.default')

@title([$tag->title ?: $tag->name, 'صفحه '. $products->currentPage()], false)
@description($tag->summary ?: $tag->description)

@section('content')
    <div class="container">
        <h1 class="h2">{{ $tag->name }}</h1>

        @if (! $products->isEmpty())
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 mt-5">
                @foreach ($products as $product)
                    @include('templates.default.views.components.product.items.grid-item')
                @endforeach
            </div>
        @else
            <div class="flex items-center justify-center font-light pt-16">
                محصولی برای نمایش وجود ندارد.
            </div>
        @endif

        {{ $products->links() }}

        @if ($tag->description)
            <div class="mt-10 text-sm leading-7">
                {!! $tag->description !!}
            </div>
        @endif
    </div>
@endsection
