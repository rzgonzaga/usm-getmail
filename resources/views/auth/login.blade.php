<!DOCTYPE html>
<html lang="en" class="light scroll-smooth group" data-layout="vertical" data-sidebar="light" data-sidebar-size="lg"
    data-mode="light" data-topbar="light" data-skin="default" data-navbar="sticky" data-content="fluid" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>USM GetMail | Sign in </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta content="USM GetMail" name="description">
    <meta content="Themesdesign" name="author">
    <link rel="shortcut icon" href="{{ asset('assets/images/usm.png') }}">
    <script src="{{ asset('assets/js/layout.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/tailwind2.css') }}">
</head>

<body
    class="flex items-center justify-center min-h-screen py-16 lg:py-10 bg-slate-50 dark:bg-zink-800 dark:text-zink-100 font-public">

    <div class="relative">
        <div class="mb-0 w-screen lg:mx-auto lg:w-[500px] card shadow-lg border-none shadow-slate-100 relative">
            <div class="!px-10 !py-12 card-body text-center">
                
                <a href="#!">
                    <img src="{{ asset('assets/images/logo-dark.png') }}" alt=""
                        class="hidden h-26 mx-auto dark:block">
                    <img src="{{ asset('assets/images/logo-dark.png') }}" alt=""
                        class="block mx-auto h-26 dark:hidden">
                </a>

                <p class="mt-2 text-slate-500 dark:text-zink-200">Sign in to continue to GetMail</p>

                <!-- Display error message if any -->
                @if(session('error'))
                    <div class="mt-4 mb-4 text-red-600 font-medium">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Google Login Button -->
                <div class="mt-6">
                    <a href="{{ route('google.login') }}"
                        class="w-full flex items-center justify-center gap-3 bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 focus:ring-2 focus:ring-offset-1 focus:ring-gray-300 py-3 rounded shadow-sm font-medium transition">
                        
                        <!-- Google "G" Logo -->
                        <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Google_Favicon_2025.svg" alt="Google logo" class="w-6 h-6">

                        <span>Sign in with Google</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/libs/@popperjs/core/umd/popper.min.js') }}"></script>
    <script src="{{ asset('assets/libs/tippy.js/tippy-bundle.umd.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/prismjs/prism.js') }}"></script>
    <script src="{{ asset('assets/libs/lucide/umd/lucide.js') }}"></script>
    <script src="{{ asset('assets/js/tailwick.bundle.js') }}"></script>
</body>

</html>
