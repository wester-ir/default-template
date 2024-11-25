@template_extends('views.layouts.default')

@title($page->title, false)

@section('content')
    <div class="container">
        <h1 class="h2 mb-3">{{ $page->title }}</h1>

        {!! $page->content !!}
    </div>
@endsection
