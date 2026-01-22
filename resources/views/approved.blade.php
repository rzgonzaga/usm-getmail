<!DOCTYPE html>
<html lang="en" class="light scroll-smooth group" data-layout="vertical" data-sidebar="light" data-sidebar-size="lg"
    data-mode="light" data-topbar="light" data-skin="default" data-navbar="sticky" data-content="fluid" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>USM GetMail | Approved Request</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta content="USM GetMail" name="description">
    <meta content="Themesdesign" name="author">
    <link rel="shortcut icon" href="{{ asset('assets/images/usm.png') }}">
    <script src="{{ asset('assets/js/layout.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/tailwind2.css') }}">
</head>

<body class="flex items-center justify-center min-h-screen bg-slate-50 dark:bg-zink-800 dark:text-zink-100 font-public">

    <div class="w-screen lg:w-[500px] card shadow-lg border-none mx-auto my-10 p-10">
        <div class="text-center mb-6">
            <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" class="mx-auto h-26">
            <h2 class="mt-4 text-2xl font-semibold dark:text-white">Your Email Request is Approved!</h2>
        </div>

        @if (session('message'))
            <div class="px-4 py-3 mb-4 text-sm text-green-600 bg-green-50 rounded-md">
                {{ session('message') }}
            </div>
        @endif

        <div class="mb-4 text-left">
            <p><strong>Student No:</strong> {{ $request->studentno }}</p>
            <p><strong>Name:</strong> {{ $request->firstname }} {{ $request->middlename }} {{ $request->lastname }}</p>
            <p><strong>Email:</strong> {{ $request->email }}</p>
            <p><strong>Password:</strong> {{ $request->password }}</p>
        </div>
        <div class="flex flex-col gap-2 mt-6">
            <!-- Reset Password (Red) -->
            <form method="POST" action="{{ route('email.request.reset', Crypt::encrypt($request->id)) }}" class="flex-1">
    @csrf
    <button type="submit"
        class="text-white bg-red-500 border-red-500 btn hover:text-white hover:bg-red-600 hover:border-red-600 focus:text-white focus:bg-red-600 focus:border-red-600 focus:ring focus:ring-red-100 active:text-white active:bg-red-600 active:border-red-600 active:ring active:ring-red-100 rounded font-medium w-full">
        Reset Password
    </button>
</form>

            <!-- Back to Request (Sky/Blue) -->
            <a href="{{ url('/') }}"
                class="w-full text-white bg-sky-500 border-sky-500 btn hover:bg-sky-600 hover:border-sky-600 focus:bg-sky-600 focus:border-sky-600 focus:ring focus:ring-sky-100 active:bg-sky-600 active:border-sky-600 active:ring active:ring-sky-100 rounded font-medium py-3 text-center transition-all">
                Back to Request
            </a>
        </div>


    </div>
    <script src="{{ asset('assets/libs/@popperjs/core/umd/popper.min.js') }}"></script>
    <script src="{{ asset('assets/libs/tippy.js/tippy-bundle.umd.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/prismjs/prism.js') }}"></script>
    <script src="{{ asset('assets/libs/lucide/umd/lucide.js') }}"></script>
    <script src="{{ asset('assets/js/tailwick.bundle.js') }}"></script>
</body>

</html>
