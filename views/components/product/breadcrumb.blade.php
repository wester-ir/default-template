<div class="border-b border-neutral-100 shadow-sm">
    <div class="container px-0">
        <ul class="flex items-center px-5 py-3 overflow-x-auto whitespace-nowrap select-none font-light hide-scrollbar md:show-scrollbar">
            @foreach ($product->categories as $category)
                <li>
                    <a href="{{ route('client.category', $category) }}">{{ $category->name }}</a>
                </li>
                <li>
                    <span class="mx-4 text-neutral-300">/</span>
                </li>
            @endforeach

            <li class="font-normal">
                <a href="{{ $product->url }}">
                    {{ $product->title }}
                </a>
            </li>
        </ul>
    </div>
</div>
