<!doctype html>
<html lang="en" dir="rtl">
<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@description">
    <meta name="color-scheme" content="light">

    <title>@title</title>

    <!-- Styles -->
    <link href="{{ asset('/assets/icons/fi/css/uicons-regular-rounded.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/icons/fi/css/uicons-solid-rounded.css') }}" rel="stylesheet">
    <link href="{{ template_debuggable_asset('assets/css/style.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script type="text/javascript" src="{{ template_debuggable_asset('/assets/js/utils.js') }}"></script>

    <!-- Jquery -->
    <script type="text/javascript" src="{{ asset('/assets/js/libs/jquery/jquery.min.js') }}"></script>

    {!! template_head() !!}
</head>
<body class="p-0">
    @yield('content')

    <!-- Scripts -->
    @stack('bottom-scripts')
</body>
</html>
