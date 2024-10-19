@extends('templates.default.views.layouts.default')

@title([$tag->title ?: $tag->name, 'صفحه '. $products->currentPage()], false)
@description($tag->summary ?: $tag->description)

@section('content')
    <div class="container">
        <h1 class="h2">{{ $tag->name }}</h1>

        @template_include('views.pages.partials.products-frame')

        {{ $products->links() }}

        @if ($tag->description)
            <div class="mt-10 text-sm leading-7">
                {!! $tag->description !!}
            </div>
        @endif
    </div>
@endsection
