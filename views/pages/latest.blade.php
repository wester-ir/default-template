@template_extends('views.layouts.default')

@title(['جدیدترین محصولات ', 'صفحه '. $products->currentPage()], false)

@section('content')
    <div class="container">
        <h1 class="h2">جدیدترین محصولات</h1>

        @template_include('views.pages.partials.products-frame')

        {{ $products->links() }}
    </div>
@endsection
