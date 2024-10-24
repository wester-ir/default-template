@template_extends('views.layouts.basic')

@title('یافت نشد', false)

@section('content')
    <div class="container flex flex-col items-center justify-center my-auto">
        <img src="{{ template_asset('/assets/img/404.svg') }}" class=" w-[550px]">
        <h1 class="h2 text-center">صفحه مورد نظر پیدا نشد!</h1>
    </div>
@endsection
