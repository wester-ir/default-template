@extends('templates.default.views.layouts.default')

@title('خطای ناشناخته')

@section('content')
    <div class="container flex flex-col items-center justify-center my-auto">
        <img src="{{ template_asset('assets/img/payment-failed.svg') }}" class="w-24 h-24 mb-4">
        <h2>خطای ناشناخته</h2>
        <div>مشکلی ناشناخته در حین تایید پرداخت رخ داد.</div>
    </div>
@endsection
