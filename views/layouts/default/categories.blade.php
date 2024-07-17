@inject('categoryService', 'App\Services\CategoryService')
@php
    // Category
    $categories = $categoryService->tree();

    if (! function_exists('renderCategories')) {
        function renderCategories($category, bool $first = true) {
            if ($category->children):
                echo "<ul class=\"". ($first ? 'main-ul' : 'sub-ul') ."\">";
                    foreach ($category->children as $child):
                        echo "<li>
                            <a href=\"{$child->url}\" class=\"flex items-center\">
                                <span class=\"". ($first ? 'font-medium' : '') ."\">{$child->name}</span>
                                ". ($first ? "<span class=\"fi fi-rr-angle-small-left flex mr-1\"></span>" : '') ."
                            </a>";

                            echo renderCategories($child, false);
                        echo "</li>";

                    endforeach;
                echo "</ul>";
            endif;
        }
    }
@endphp

<div id="categories-menu" data-is-hovering="false" class="flex flex-col justify-center h-12 navbar-indicator-trigger">
    <button type="button" class="flex items-center h-12 px-3 md:px-0">
        <i class="fi fi-rr-menu-burger text-xl flex"></i>
        <span class="font-bold text-[15px] mr-3 hidden md:block">دسته بندی کالاها</span>
    </button>

    <div id="categories-dropdown" class="hidden absolute z-[1000000] bg-white left-0 right-0 shadow-lg top-[48px] rounded-b-lg overflow-hidden">
        <div class="flex min-h-[320px]">
            <div class="border-l border-neutral-100 divide-y divide-neutral-100 overflow-y-auto">
                @foreach ($categories as $key => $category)
                    <div data-role="category-parent" data-id="{{ $category->id }}" data-is-active="{{ var_export($key === 0, true) }}" class="px-5 py-3 w-28 md:w-48 font-light data-[is-active=true]:font-medium data-[is-active=true]:text-green-500 data-[is-active=true]:bg-neutral-50 text-[15px] select-none cursor-pointer">
                        {{ $category->name }}
                    </div>
                @endforeach
            </div>
            <div class="flex-1 font-light p-3">
                @foreach ($categories as $key => $category)
                    <div data-role="category-children" data-children-of="{{ $category->id }}" data-is-visible="{{ var_export($key === 0, true) }}" class="hidden data-[is-visible=true]:block">
                        <div>
                            <a href="{{ route('client.category', $category) }}" class="flex items-center link">
                                <span>همه محصولات {{ $category->name }}</span>
                                <span class="fi fi-rr-angle-small-left flex mr-1"></span>
                            </a>
                        </div>

                        {{ renderCategories($category) }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('bottom-scripts')
    <script>
        $('[data-role="category-parent"]').hover(function () {
            var id = $(this).data('id');

            $('[data-role="category-parent"]').attr('data-is-active', 'false');
            $(this).attr('data-is-active', 'true');

            $('[data-role="category-children"]').attr('data-is-visible', 'false');
            $('[data-role="category-children"][data-children-of="'+ id +'"]').attr('data-is-visible', 'true');
        });

        $('#categories-menu').hover(function () {
            setTimeout(function () {
                $('#categories-menu').attr('data-is-hovering', 'true');
            }, 100);
            $('#categories-dropdown').removeClass('hidden');
        }, function () {
            setTimeout(function () {
                $('#categories-menu').attr('data-is-hovering', 'false');
            }, 100);
            $('#categories-dropdown').addClass('hidden');
        });

        $('#categories-menu button').click(function () {
            if ($('#categories-menu').attr('data-is-hovering') === 'true') {
                var dropDown = $('#categories-dropdown');

                if (dropDown.hasClass('hidden')) {
                    dropDown.removeClass('hidden');
                } else {
                    dropDown.addClass('hidden');
                }
            }
        });
    </script>
@endpush

