@template_extends('views.layouts.default')

@title([$keyword, 'صفحه '. $products->currentPage()], false)

@section('content')
    <div class="container">
        <h1 class="h2">{{ $keyword }}</h1>

        @template_include('views.pages.partials.products-frame')

        {{ $products->links() }}
    </div>
@endsection
