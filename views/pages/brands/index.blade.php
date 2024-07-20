@extends('templates.default.views.layouts.default')

@php
    $titleWithoutSiteName = true;
    $title = 'برند ها';
@endphp
@section('title', $title)

@section('content')
    <div class="container">
        <h1 class="h2">{{ $title }}</h1>

        @if (! $brands->isEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 mt-5">
                @foreach ($brands as $brand)
                    <a href="{{ $brand->url }}" class="border border-white hover:border-neutral-200 rounded-lg p-5">
                        @if ($brand->logo)
                            <img src="{{ $brand->logo_url }}" class="w-full">
                        @else

                        @endif

                        <div class="mt-4">
                            <h3 class="text-center">{{ $brand->name }} — {{ $brand->latin_name }}<h3>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="flex items-center justify-center font-light pt-16">
                برندی برای نمایش وجود ندارد.
            </div>
        @endif

        {{ $brands->links() }}
    </div>
@endsection
