@template_extends('views.layouts.default')

@title('محصولات مورد علاقه', false)

@section('content')
    <div class="container">
        <div class="flex flex-col md:flex-row space-y-5 md:space-y-0">
            <!-- Sidebar -->
            @template_include('views.pages.account.partials.sidebar')

            <!-- Content -->
            <div class="flex-1 w-full md:mr-5">
                <div class="border border-neutral-200 rounded-lg p-5">
                    <div class="flex items-center justify-between">
                        <h3>محصولات مورد علاقه</h3>
                    </div>

                    @if (! $products->isEmpty())
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 mt-5 gap-3">
                            @foreach ($products as $product)
                                <a href="{{ route('client.product.index', $product) }}" class="block">
                                    <div>
                                        <img src="{{ $product->image->url['thumbnail'] ?? template_asset('assets/img/no-image.jpg') }}" class="rounded-lg w-full">
                                    </div>
                                    <div class="text-sm text-center mt-2">{{ $product->title }}</div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center font-light mt-5">هیچ محصول مورد علاقه ای ندارید.</div>
                    @endif
                </div>

                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection
