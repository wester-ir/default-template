@extends('templates.default.views.layouts.default')

@title(['پرفروش ترین محصولات ', 'صفحه '. $products->currentPage()], false)

@section('content')
    <div class="container">
        <h1 class="h2">پرفروش ترین محصولات</h1>

        @template_include('views.pages.partials.products-frame')

        {{ $products->links() }}
    </div>
@endsection
