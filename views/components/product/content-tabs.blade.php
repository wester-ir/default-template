<div class="content-tabs">
    <ul>
        @if (! $product->attributes->isEmpty())
            <li data-id="attributes" data-active="true">مشخصات</li>
        @endif

        @if ($product->content->full_description)
            <li data-id="full_description" data-active="{{ ! $product->attributes ? 'true' : 'false' }}">توضیحات تکمیلی</li>
        @endif

        @if ($product->content->sizing)
            <li data-id="sizing" data-active="{{ ! $product->attributes && ! $product->content->full_description ? 'true' : 'false' }}">سایز بندی</li>
        @endif
    </ul>

    <!-- Attributes -->
    @if ($product->attributes)
        <div class="tab-section" data-id="attributes" data-active="true">
            <ul class="divide-y">
                @foreach ($product->merged_attributes as $attribute)
                    <li class="flex">
                        <div class="text-neutral-500 w-52 px-3 py-2">{{ is_string($attribute['attribute']) ? $attribute['attribute'] : $attribute['attribute']->name }}</div>
                        <div class="px-3 py-2 space-y-2">
                            @foreach ($attribute['items'] as $item)
                                <div>{{ is_string($item) ? $item : $item->name }}</div>
                            @endforeach
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Full Description -->
    @if ($product->content->full_description)
        <div class="tab-section" data-id="full_description" data-active="{{ ! $product->attributes ? 'true' : 'false' }}">
            {!! $product->content->full_description !!}
        </div>
    @endif

    <!-- Sizing -->
    @if ($product->content->sizing)
        <div class="tab-section" data-id="sizing" data-active="{{ ! $product->attributes && ! $product->content->full_description ? 'true' : 'false' }}">
            <table class="sizing-table">
                <thead>
                    <tr>
                        @foreach ($product->content->sizing[0] as $th)
                            <th>{{ $th }}</th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    @for ($i = 1; $i < count($product->content->sizing); $i++)
                        <tr>
                            @foreach ($product->content->sizing[$i] as $index => $td)
                                @if ($index === 0)
                                    <th>{{ $td }}</th>
                                @else
                                    <td>{{ $td }}</td>
                                @endif
                            @endforeach
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    @endif
</div>

@push('bottom-scripts')
    <script>
        $('.content-tabs > ul > li').click(function () {
            const id = $(this).data('id');

            $('.content-tabs > ul > li').attr('data-active', 'false');
            $(this).attr('data-active', 'true');

            $('.content-tabs .tab-section').attr('data-active', 'false');
            $('.content-tabs .tab-section[data-id="'+ id +'"]').attr('data-active', 'true');
        });
    </script>
@endpush
