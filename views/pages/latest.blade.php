@extends('templates.default.views.layouts.default')

@title(['جدیدترین محصولات ', 'صفحه '. $products->currentPage()], false)

@section('content')
    <div class="container">
        <h1 class="h2">جدیدترین محصولات</h1>

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
    </div>
@endsection
