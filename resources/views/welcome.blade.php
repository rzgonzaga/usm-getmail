<!DOCTYPE html>
<html lang="en" class="light scroll-smooth group" data-layout="vertical" data-sidebar="light" data-sidebar-size="lg"
    data-mode="light" data-topbar="light" data-skin="default" data-navbar="sticky" data-content="fluid" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>USM GetMail | Institutional Email Request</title>
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
            <div class="!px-10 !py-12 card-body">

                <div class="text-center">
                    <a href="#!">
                        <img src="{{ asset('assets/images/logo-dark.png') }}" alt=""
                            class="hidden h-26 mx-auto dark:block">
                        <img src="{{ asset('assets/images/logo-dark.png') }}" alt=""
                            class="block mx-auto h-26 dark:hidden">
                    </a>
                    <h4 class="mt-6 mb-1 text-xl font-semibold dark:text-white">Email Request</h4>
                    <p class="mb-6 text-slate-500 dark:text-zink-200">Fill in the details to request your institutional
                        email</p>
                </div>

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="px-4 py-3 mb-4 text-sm text-red-600 bg-red-50 rounded-md">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Flash Messages -->
                @if (session('message'))
                    <div
                        class="px-4 py-3 mb-4 text-sm {{ session('requestSaved') ? 'text-green-600 bg-green-50' : 'text-yellow-600 bg-yellow-50' }} rounded-md">
                        {{ session('message') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('email.request.store') }}" class="mt-6">
                    @csrf

                    <!-- Campus -->
                    <div class="mb-4">
                        <label for="campus"
                            class="block mb-2 text-sm font-medium text-slate-700 dark:text-zink-100 text-left">Campus</label>
                        <select id="campus" name="campus" required
                            class="w-full px-3 py-2 border rounded border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700">
                            <option value="">---</option>
                            <option value="1" {{ old('campus') == 1 ? 'selected' : '' }}>Main Campus</option>
                            <option value="2" {{ old('campus') == 2 ? 'selected' : '' }}>Palma Campus</option>
                            <option value="3" {{ old('campus') == 3 ? 'selected' : '' }}>Kcc Campus</option>
                            <option value="4" {{ old('campus') == 4 ? 'selected' : '' }}>M'lang Campus</option>
                            <option value="5" {{ old('campus') == 5 ? 'selected' : '' }}>Buluan Campus</option>
                            <option value="6" {{ old('campus') == 6 ? 'selected' : '' }}>Graduate School Main
                                Campus</option>
                            <option value="7" {{ old('campus') == 7 ? 'selected' : '' }}>College of Medicine
                            </option>
                            <option value="8" {{ old('campus') == 8 ? 'selected' : '' }}>College of Law</option>
                        </select>
                    </div>

                    <!-- Student No -->
                    <div class="mb-4">
                        <label for="student_no"
                            class="block mb-2 text-sm font-medium text-slate-700 dark:text-zink-100 text-left">Student
                            No.</label>
                        <input type="text" id="student_no" name="student_no" value="{{ old('student_no') }}"
                            required
                            class="w-full px-3 py-2 border rounded border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700"
                            placeholder="Enter Student Number">
                    </div>

                    <!-- COR Number -->
                    <div class="mb-6">
                        <label for="cor_no"
                            class="block mb-2 text-sm font-medium text-slate-700 dark:text-zink-100 text-left">COR # or
                            Certification of Registration</label>
                        <input type="number" id="cor_no" name="cor_no" value="{{ old('cor_no') }}" required
                            class="w-full px-3 py-2 border rounded border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700"
                            placeholder="Enter COR Number">
                    </div>


                    <!-- Submit Button -->
                    <div class="mt-8">
                        <button type="submit"
                            class="w-full bg-custom-500 text-white border-custom-500 btn hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 active:text-white active:bg-custom-600 active:border-custom-600 transition-all py-3 rounded font-medium">
                            Submit Request
                        </button>
                    </div>
                </form>

            </div>
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
