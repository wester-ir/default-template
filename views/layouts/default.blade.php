<!DOCTYPE html>
<html lang="fa" dir="rtl">
    <head>
        <!-- Meta Tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @stack('meta-tags')
        <meta property="description" content="@description">
        <meta property="og:description" content="@description">
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="{{ settingService('general')['name'] }}">
        <meta property="og:title" content="@title">
        <meta name="twitter:title" content="@title">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:locale" content="fa_IR">
        <meta name="twitter:widgets:csp" content="on">
        <meta name="twitter:card" content="summary">
        <meta name="apple-mobile-web-app-title" content="{{ settingService('general')['name'] }}">
        <meta name="color-scheme" content="light">

        @if (has_site_logo())
            <!-- Icons -->
            <link rel="icon" type="image/png" sizes="32x32" href="{{ site_favicon(32) }}">
            <link rel="icon" type="image/png" sizes="16x16" href="{{ site_favicon(16) }}">
        @endif

        <title>@title</title>

        <!-- Canonical -->
        <link rel="canonical" href="{{ url()->current() }}" />

        <!-- Styles -->
        <link href="{!! versioned_asset('/assets/icons/fi/css/uicons-regular-rounded.css') !!}" rel="stylesheet">
        <link href="{!! versioned_asset('/assets/icons/fi/css/uicons-solid-rounded.css') !!}" rel="stylesheet">
        <link href="{!! template_versioned_asset('/assets/css/style.css') !!}" rel="stylesheet">

        <!-- Scripts -->
        <script>
            const endpoints = {
                addToCart: '{{ route('client.cart.ajax.add') }}',
                updateCartItem: '{{ route('client.cart.ajax.update') }}',
                removeCartItem: '{{ route('client.cart.ajax.remove') }}',
                clearCart: '{{ route('client.cart.ajax.clear') }}',
            };
        </script>
        <script type="text/javascript" src="{!! template_versioned_asset('/assets/js/utils.js') !!}"></script>

        <!-- Axios -->
        <script type="text/javascript" src="{!! versioned_asset('/assets/js/libs/axios/axios.min.js') !!}"></script>

        <!-- Jquery -->
        <script type="text/javascript" src="{!! versioned_asset('/assets/js/libs/jquery/jquery.min.js') !!}"></script>

        <!-- Modal -->
        <script type="text/javascript" src="{!! versioned_asset('/assets/js/libs/modal/modal.min.js') !!}"></script>
        <link href="{{ versioned_asset('/assets/js/libs/modal/modal.css') }}" rel="stylesheet">

        <!-- iTooltip -->
        <script type="text/javascript" src="{!! versioned_asset('/assets/js/libs/itooltip/itooltip.min.js') !!}"></script>
        <link href="{{ versioned_asset('/assets/js/libs/itooltip/itooltip.css') }}" rel="stylesheet">

        <!-- Selectbox -->
        <script type="text/javascript" src="{!! versioned_asset('/assets/js/libs/selectbox/selectbox.min.js') !!}"></script>
        <link href="{!! versioned_asset('/assets/js/libs/selectbox/selectbox.css') !!}" rel="stylesheet">

        <!-- Toast -->
        <script type="text/javascript" src="{!! versioned_asset('/assets/js/libs/toast/toast.min.js') !!}"></script>
        <link href="{!! versioned_asset('/assets/js/libs/toast/toast.css') !!}" rel="stylesheet">

        @stack('head-scripts')

        {!! template_head() !!}
    </head>
    <body>
        <div class="fixed top-0 left-0 right-0 z-50">
            <header class="header">
                <div class="container">
                    <!-- Logo -->
                    @template_include('views.layouts.default.logo')

                    <!-- Search -->
                    @template_include('views.layouts.default.search')

                    <div class="flex items-center">
                        <!-- Auth Menu -->
                        @template_include('views.layouts.default.auth-menu')

                        <a href="{{ route('client.cart.index') }}" class="relative mr-7 md:mr-8" rel="nofollow">
                            <i class="fi fi-rr-shopping-cart text-[28px]"></i>
                            <span data-role="items-total-quantity-count" class="absolute top-4 -right-3 flex items-center justify-center bg-green-500 border-2 border-neutral-100 px-2 rounded-md text-sm text-white font-light">
                                {{ get_cart_total_quantity() }}
                            </span>
                        </a>
                    </div>
                </div>
            </header>

            <nav id="navbar" class="shadow bg-neutral-100 transition-transform data-[is-hidden=true]:-translate-y-full" data-is-hidden="false">
                <div class="container relative navbar-indicator-triggers">
                    <div class="relative flex items-center h-12">
                        @template_include('views.layouts.default.categories')

                        <div class="h-6 w-px bg-neutral-300 mx-4"></div>

                        <div class="overflow-x-auto hide-scrollbar flex-1">
                            <ul class="flex items-center space-x-4 space-x-reverse text-sm">
                                <li class="navbar-indicator-trigger">
                                    <a href="{{ route('client.latest') }}" class="flex items-center whitespace-pre">
                                        <i class="fi fi-rr-flower text-lg flex ml-2"></i>
                                        <span>جدیدترین ها</span>
                                    </a>
                                </li>
                                <li class="navbar-indicator-trigger">
                                    <a href="{{ route('client.best-selling') }}" class="flex items-center whitespace-pre">
                                        <i class="fi fi-rr-star text-lg flex ml-2"></i>
                                        <span>پرفروش ترین ها</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="items-center hidden md:flex">
                            <i class="fi fi-rr-shipping-fast flex"></i>
                            <span class="text-sm mr-3">ارسال به تمام نقاط کشور</span>
                        </div>

                        <span id="navbar-indicator" class="absolute bottom-0 bg-green-400 h-[2px]"></span>
                    </div>
                </div>
            </nav>
        </div>

        @yield('raw-content')

        <div class="main">
            @template_include('views.layouts.default.pending-order')

            @yield('content')
        </div>

        <!-- Footer -->
        @template_include('views.layouts.default.footer')

        <!-- Scripts -->
        @stack('bottom-scripts')
        <script type="text/javascript" src="{{ template_versioned_asset('assets/js/main.js') }}"></script>
        <script>
            ready(function () {
                axios.defaults.withCredentials = true;
            });

            $('[data-itooltip]').itooltip();

            function countdown(elem, seconds, delay = 1000) {
                setTimeout(function () {
                    seconds = Number(seconds);

                    if (seconds > 0) {
                        seconds = seconds - 1;
                    } else {
                        seconds = 0;
                    }

                    const mins = ~~(seconds / 60);
                    const secs = seconds - (mins * 60);

                    elem.innerHTML = '<div class="w-5 text-center">'+ pad(mins) +'</div>' + ':' + '<div class="w-5 text-center">'+ pad(secs) +'</div>';

                    if (seconds > 0) {
                        countdown(elem, seconds);
                    }
                }, delay);
            }

            document.querySelectorAll('[data-role="countdown"]').forEach(function (elem) {
                const seconds = elem.getAttribute('data-seconds');

                countdown(elem, seconds, 0);
            });

            // Hide the navbar in the header when the user scrolls down
            (function () {
                var navbar = $('#navbar');

                var scrollThreshold = 100;
                var prevScrollPos = $(window).scrollTop();
                var isScrollingDown = false;
                var timer;

                $(window).scroll(function() {
                    var productScrollPos = $(window).scrollTop();

                    if (productScrollPos > prevScrollPos) {
                        // Scrolling down
                        if (productScrollPos > scrollThreshold && ! isScrollingDown && $('#navbar').find('#categories-dropdown').hasClass('hidden')) {
                            navbar.attr('data-is-hidden', 'true');
                            isScrollingDown = true;
                        }
                    } else {
                        clearTimeout(timer);

                        // Scrolling up
                        navbar.attr('data-is-hidden', 'false');
                        isScrollingDown = false;
                    }

                    prevScrollPos = productScrollPos;
                });
            })();
        </script>
        @if (auth()->check())
            <script>
                window.user = {
                    first_name: '{{ auth()->user()->first_name }}',
                    last_name: '{{ auth()->user()->last_name }}',
                    full_name: '{{ auth()->user()->full_name }}',
                };
            </script>
        @endif
    </body>
</html>
