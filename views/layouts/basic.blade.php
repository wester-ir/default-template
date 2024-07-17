<!doctype html>
<html lang="en" dir="rtl">
<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">

    <title>@yield('title')</title>

    <!-- Styles -->
    <link href="http://127.0.0.1/assets/icons/fi/css/uicons-regular-rounded.css" rel="stylesheet">
    <link href="http://127.0.0.1/assets/icons/fi/css/uicons-solid-rounded.css" rel="stylesheet">
    <link href="{{ template_asset('assets/css/style.css?t='. time()) }}" rel="stylesheet">

    <!-- Scripts -->
    <script type="text/javascript" src="http://127.0.0.1/assets/js/library/jquery.min.js"></script>
    <script type="text/javascript" src="{{ template_asset('assets/js/base.js?t='. time()) }}"></script>
</head>
<body>
    @yield('content')

    <!-- Scripts -->
    @stack('bottom-scripts')
</body>
</html>
