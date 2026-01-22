 <meta charset="utf-8">
    <title>@yield('title', 'Leaper Journal')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta content="Leaper Journal" name="description">
    <meta content="Themesdesign" name="author">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/usm.png') }}">

    <!-- Layout config Js -->
    <script src="{{ asset('assets/js/layout.js') }}"></script>

    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/tailwind2.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
    <!-- JS Libraries -->
    <script src="{{ asset('assets/libs/@popperjs/core/umd/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/common.js') }}"></script>

    <!-- Page specific styles -->
    @stack('styles')