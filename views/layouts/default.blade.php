@inject('cartService', 'App\Services\CartService')
@php
    // Cart
    $itemsTotalQuantity = $cartService->getTotalQuantity();
    $pendingOrder = \App\Models\Order::currentActor()->payable()->first();

    // Title
    $titleWithoutSiteName ??= false;
    $title = $__env->yieldContent('title');
    $title = $title ? (
        $titleWithoutSiteName ? '' : settingService('general')['title'] .' - '
    ) . $title : settingService('general')['title'];

    // Description
    $description = str(text_from_html($__env->yieldContent('description') ?: settingService('general')['description']))->limit(160);
@endphp
<!DOCTYPE html>
<html lang="fa" dir="rtl">
    <head>
        <!-- Meta Tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        @stack('meta-tags')
        @if ($description)
            <meta property="description" content="{{ $description }}">
            <meta property="og:description" content="{{ $description }}">
        @endif
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="{{ settingService('general')['name'] }}">
        <meta property="og:title" content="{{ $title }}">
        <meta name="twitter:title" content="{{ $title }}">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:locale" content="fa_IR">
        <meta name="twitter:widgets:csp" content="on">
        <meta name="twitter:card" content="summary">
        <meta name="apple-mobile-web-app-title" content="{{ settingService('general')['name'] }}">

        <title>{{ $title }}</title>

        <!-- Canonical -->
        <link rel="canonical" href="{{ url()->current() }}" />

        <!-- Styles -->
        <link href="/assets/icons/fi/css/uicons-regular-rounded.css" rel="stylesheet">
        <link href="/assets/icons/fi/css/uicons-solid-rounded.css" rel="stylesheet">
        <link href="{{ template_asset('assets/css/style.css?t='. time()) }}" rel="stylesheet">

        <!-- Scripts -->
        <script>
            const endpoints = {
                addToCart: '{{ route('client.cart.ajax.add') }}',
                updateCartItem: '{{ route('client.cart.ajax.update') }}',
                removeCartItem: '{{ route('client.cart.ajax.remove') }}',
                clearCart: '{{ route('client.cart.ajax.clear') }}',
            };
        </script>
        <script type="text/javascript" src="/assets/js/library/axios.min.js"></script>
        <script type="text/javascript" src="/assets/js/library/jquery.min.js"></script>
        <script type="text/javascript" src="{{ template_asset('assets/js/base.js?t='. time()) }}"></script>
        <script type="text/javascript" src="/assets/js/library/toast.js"></script>
        <script type="text/javascript" src="/assets/js/library/locale.js?t=1712936936"></script>
        <script type="text/javascript" src="/assets/js/library/modal.js?t=2712936936"></script>
        <script type="text/javascript" src="/assets/js/library/selectbox.js?t=3712936936"></script>
        <script type="text/javascript" src="/assets/js/library/tooltip.js?t=1719368490"></script>
        <script type="text/javascript" src="{{ template_asset('assets/js/dropdown.js?t='. time()) }}"></script>
        @stack('head-scripts')
    </head>
    <body>
        <div class="fixed top-0 left-0 right-0 z-50">
            <header class="header">
                <div class="container">
                    <!-- Logo -->
                    @include('templates.default.views.layouts.default.logo')

                    <!-- Search -->
                    @include('templates.default.views.layouts.default.search')

                    <div class="flex items-center">
                        <!-- Auth Menu -->
                        @include('templates.default.views.layouts.default.auth-menu')

                        <a href="{{ route('client.cart.index') }}" class="relative mr-8" rel="nofollow">
                            <i class="icon icon-cart w-7 h-7"></i>
                            <span data-role="items-total-quantity-count" class="absolute top-4 -right-3 flex items-center justify-center bg-green-500 border-2 border-neutral-100 px-2 rounded-md text-sm text-white font-light">{{ $itemsTotalQuantity }}</span>
                        </a>
                    </div>
                </div>
            </header>

            <nav id="navbar" class="shadow bg-neutral-100 transition-transform data-[is-hidden=true]:-translate-y-full" data-is-hidden="false">
                <div class="container relative navbar-indicator-triggers">
                    <div class="relative flex items-center h-12">
                        @include('templates.default.views.layouts.default.categories')

                        <div class="h-6 w-px bg-neutral-300 mx-4"></div>

                        <div class="overflow-x-auto hide-scrollbar flex-1">
                            <ul class="flex items-center space-x-4 space-x-reverse text-sm">
                                <li class="navbar-indicator-trigger">
                                    <a href="{{ route('client.latest') }}" class="whitespace-pre">جدید ترین ها</a>
                                </li>
                                <li class="navbar-indicator-trigger">
                                    <a href="{{ route('client.best-selling') }}" class="whitespace-pre">پرفروش ترین ها</a>
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
            @if ($pendingOrder)
                <div class="container mb-5">
                    <div class="flex justify-between border border-red-300 rounded-md px-5 py-3">
                        <div class="space-y-1">
                            <div class="text-red-500 font-medium">یک سفارش در انتظار پرداخت دارید.</div>
                            <div class="text-sm">لطفاً نسبت به پرداخت سفارش خود اقدام و یا لغو کنید.</div>
                        </div>

                        <div class="flex items-center">
                            <div class="text-sm px-3">{{ number_format($pendingOrder->invoice->total_amount) }} {{ productCurrency()->label() }}</div>
                            <div class="flex items-center justify-center font-light text-sm px-3 border-r border-neutral-200">
                                <div class="flex items-center font-medium text-red-500 w-12" style="direction: ltr;" data-role="countdown" data-seconds="{{ $pendingOrder->expires_at->timestamp - now()->timestamp }}"></div>
                            </div>
                            <a href="#" class="text-sm font-medium text-sky-500 px-3">مشاهده</a>
                            <form action="{{ route('client.cart.finalizing.order.cancel', $pendingOrder) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <button class="text-sm font-medium text-danger px-3 border-r border-neutral-200">لغو سفارش</button>
                            </form>
                            <form action="{{ route('client.cart.finalizing.order.purchase', $pendingOrder) }}" method="POST">
                                @csrf

                                <button class="btn btn-success btn-sm mr-2">پرداخت سفارش</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>

        <!-- Footer -->
        @include('templates.default.views.layouts.default.footer')

        <!-- Scripts -->
        @stack('bottom-scripts')
        <script type="text/javascript" src="{{ template_asset('assets/js/main.js?t='. time()) }}"></script>
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
                        //header.find('nav').show();
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
