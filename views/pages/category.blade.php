@extends('templates.default.views.layouts.default')

@title([$category->page_title ?: $category->full_name_or_name, 'صفحه '. $products->currentPage()], false)
@description($category->meta_description)

@section('content')
    <div class="container">
        <h1 class="h2">{{ $category->full_name_or_name }}</h1>

        @template_include('views.pages.partials.products-frame')

        {{ $products->links() }}

        @if ($category->description)
            <div class="mt-10 text-sm leading-7">
                {!! $category->description !!}
            </div>
        @endif
    </div>
@endsection
