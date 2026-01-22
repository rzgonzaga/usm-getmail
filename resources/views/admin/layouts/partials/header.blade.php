<header id="page-topbar"
    class="ltr:md:left-vertical-menu rtl:md:right-vertical-menu group-data-[sidebar-size=md]:ltr:md:left-vertical-menu-md group-data-[sidebar-size=md]:rtl:md:right-vertical-menu-md group-data-[sidebar-size=sm]:ltr:md:left-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:md:right-vertical-menu-sm group-data-[layout=horizontal]:ltr:left-0 group-data-[layout=horizontal]:rtl:right-0 fixed right-0 z-[1000] left-0 print:hidden group-data-[navbar=bordered]:m-4 group-data-[navbar=bordered]:[&.is-sticky]:mt-0 transition-all ease-linear duration-300 group-data-[navbar=hidden]:hidden group-data-[navbar=scroll]:absolute group/topbar group-data-[layout=horizontal]:z-[1004]">
    <div class="layout-width">
        <div
            class="flex items-center px-4 mx-auto bg-topbar border-b-2 border-topbar group-data-[topbar=dark]:bg-topbar-dark group-data-[topbar=dark]:border-topbar-dark group-data-[topbar=brand]:bg-topbar-brand group-data-[topbar=brand]:border-topbar-brand shadow-md h-header shadow-slate-200/50 group-data-[navbar=bordered]:rounded-md group-data-[navbar=bordered]:group-[.is-sticky]/topbar:rounded-t-none group-data-[topbar=dark]:dark:bg-zink-700 group-data-[topbar=dark]:dark:border-zink-700 dark:shadow-none group-data-[topbar=dark]:group-[.is-sticky]/topbar:dark:shadow-zink-500 group-data-[topbar=dark]:group-[.is-sticky]/topbar:dark:shadow-md group-data-[navbar=bordered]:shadow-none group-data-[layout=horizontal]:group-data-[navbar=bordered]:rounded-b-none group-data-[layout=horizontal]:shadow-none group-data-[layout=horizontal]:dark:group-[.is-sticky]/topbar:shadow-none">
            <div
                class="flex items-center w-full group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl navbar-header group-data-[layout=horizontal]:ltr:xl:pr-3 group-data-[layout=horizontal]:rtl:xl:pl-3">
                <!-- LOGO -->
                <div
                    class="items-center justify-center hidden px-5 text-center h-header group-data-[layout=horizontal]:md:flex group-data-[layout=horizontal]:ltr::pl-0 group-data-[layout=horizontal]:rtl:pr-0">
                    <a href="index.html">
                        <span class="hidden">
                            <img src="{{ asset('assets/images/usm.png') }}"
                                alt="" class="h-6 mx-auto">
                        </span>
                        <span class="group-data-[topbar=dark]:hidden group-data-[topbar=brand]:hidden">
                            <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" class="h-6 mx-auto">
                        </span>
                    </a>
                    <a href="index.html" class="hidden group-data-[topbar=dark]:block group-data-[topbar=brand]:block">
                        <span class="group-data-[topbar=dark]:hidden group-data-[topbar=brand]:hidden">
                            <img src="{{ asset('assets/images/usm.png') }}"
                                alt="" class="h-6 mx-auto">
                        </span>
                        <span class="group-data-[topbar=dark]:block group-data-[topbar=brand]:block">
                            <img src="{{ asset('assets/images/logo-light.png') }}" alt="" class="h-6 mx-auto">
                        </span>
                    </a>
                </div>

                <button type="button"
                    class="inline-flex relative justify-center items-center p-0 text-topbar-item transition-all w-[37.5px] h-[37.5px] duration-75 ease-linear bg-topbar rounded-md btn hover:bg-slate-100 group-data-[topbar=dark]:bg-topbar-dark group-data-[topbar=dark]:border-topbar-dark group-data-[topbar=dark]:text-topbar-item-dark group-data-[topbar=dark]:hover:bg-topbar-item-bg-hover-dark group-data-[topbar=dark]:hover:text-topbar-item-hover-dark group-data-[topbar=brand]:bg-topbar-brand group-data-[topbar=brand]:border-topbar-brand group-data-[topbar=brand]:text-topbar-item-brand group-data-[topbar=brand]:hover:bg-topbar-item-bg-hover-brand group-data-[topbar=brand]:hover:text-topbar-item-hover-brand group-data-[topbar=dark]:dark:bg-zink-700 group-data-[topbar=dark]:dark:text-zink-200 group-data-[topbar=dark]:dark:border-zink-700 group-data-[topbar=dark]:dark:hover:bg-zink-600 group-data-[topbar=dark]:dark:hover:text-zink-50 group-data-[layout=horizontal]:flex group-data-[layout=horizontal]:md:hidden hamburger-icon"
                    id="topnav-hamburger-icon">
                    <i data-lucide="chevrons-left" class="w-5 h-5 group-data-[sidebar-size=sm]:hidden"></i>
                    <i data-lucide="chevrons-right" class="hidden w-5 h-5 group-data-[sidebar-size=sm]:block"></i>
                </button>


                <div class="flex gap-3 ms-auto">


                    <div class="relative flex items-center h-header">
                        <button type="button"
                            class="inline-flex relative justify-center items-center p-0 text-topbar-item transition-all w-[37.5px] h-[37.5px] duration-200 ease-linear bg-topbar rounded-md btn hover:bg-topbar-item-bg-hover hover:text-topbar-item-hover group-data-[topbar=dark]:bg-topbar-dark group-data-[topbar=dark]:hover:bg-topbar-item-bg-hover-dark group-data-[topbar=dark]:hover:text-topbar-item-hover-dark group-data-[topbar=brand]:bg-topbar-brand group-data-[topbar=brand]:hover:bg-topbar-item-bg-hover-brand group-data-[topbar=brand]:hover:text-topbar-item-hover-brand group-data-[topbar=dark]:dark:bg-zink-700 group-data-[topbar=dark]:dark:hover:bg-zink-600 group-data-[topbar=brand]:text-topbar-item-brand group-data-[topbar=dark]:dark:hover:text-zink-50 group-data-[topbar=dark]:dark:text-zink-200 group-data-[topbar=dark]:text-topbar-item-dark"
                            id="light-dark-mode">
                            <i data-lucide="sun"
                                class="inline-block w-5 h-5 stroke-1 fill-slate-100 group-data-[topbar=dark]:fill-topbar-item-bg-hover-dark group-data-[topbar=brand]:fill-topbar-item-bg-hover-brand"></i>
                        </button>
                    </div>

                    <div class="relative flex items-center dropdown h-header">
                        <button type="button"
                            class="inline-flex justify-center relative items-center p-0 text-topbar-item transition-all w-[37.5px] h-[37.5px] duration-200 ease-linear bg-topbar rounded-md dropdown-toggle btn hover:bg-topbar-item-bg-hover hover:text-topbar-item-hover group-data-[topbar=dark]:bg-topbar-dark group-data-[topbar=dark]:hover:bg-topbar-item-bg-hover-dark group-data-[topbar=dark]:hover:text-topbar-item-hover-dark group-data-[topbar=brand]:bg-topbar-brand group-data-[topbar=brand]:hover:bg-topbar-item-bg-hover-brand group-data-[topbar=brand]:hover:text-topbar-item-hover-brand group-data-[topbar=dark]:dark:bg-zink-700 group-data-[topbar=dark]:dark:hover:bg-zink-600 group-data-[topbar=brand]:text-topbar-item-brand group-data-[topbar=dark]:dark:hover:text-zink-50 group-data-[topbar=dark]:dark:text-zink-200 group-data-[topbar=dark]:text-topbar-item-dark"
                            id="notificationDropdown" data-bs-toggle="dropdown">
                            <i data-lucide="bell-ring"
                                class="inline-block w-5 h-5 stroke-1 fill-slate-100 group-data-[topbar=dark]:fill-topbar-item-bg-hover-dark group-data-[topbar=brand]:fill-topbar-item-bg-hover-brand"></i>
                            <span class="absolute top-0 right-0 flex w-1.5 h-1.5">
                                <span
                                    class="absolute inline-flex w-full h-full rounded-full opacity-75 animate-ping bg-sky-400"></span>
                                <span class="relative inline-flex w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                            </span>
                        </button>
                        <div class="absolute z-50 hidden ltr:text-left rtl:text-right bg-white rounded-md shadow-md !top-4 dropdown-menu min-w-[20rem] lg:min-w-[26rem] dark:bg-zink-600"
                            aria-labelledby="notificationDropdown">
                            <div class="p-4">
                                <h6 class="mb-4 text-16">Notifications <span
                                        class="inline-flex items-center justify-center w-5 h-5 ml-1 text-[11px] font-medium border rounded-full text-white bg-orange-500 border-orange-500">15</span>
                                </h6>
                                <ul class="flex flex-wrap w-full p-1 mb-2 text-sm font-medium text-center rounded-md filter-btns text-slate-500 bg-slate-100 nav-tabs dark:bg-zink-500 dark:text-zink-200"
                                    data-filter-target="notification-list">
                                    <li class="grow">
                                        <a href="javascript:void(0);" data-filter="all"
                                            class="inline-block nav-link px-1.5 w-full py-1 text-xs transition-all duration-300 ease-linear rounded-md text-slate-500 border border-transparent [&.active]:bg-white [&.active]:text-custom-500 hover:text-custom-500 active:text-custom-500 dark:text-zink-200 dark:hover:text-custom-500 dark:[&.active]:bg-zink-600 -mb-[1px] active">View
                                            All</a>
                                    </li>
                                    <li class="grow">
                                        <a href="javascript:void(0);" data-filter="mention"
                                            class="inline-block nav-link px-1.5 w-full py-1 text-xs transition-all duration-300 ease-linear rounded-md text-slate-500 border border-transparent [&.active]:bg-white [&.active]:text-custom-500 hover:text-custom-500 active:text-custom-500 dark:text-zink-200 dark:hover:text-custom-500 dark:[&.active]:bg-zink-600 -mb-[1px]">Mentions</a>
                                    </li>
                                    <li class="grow">
                                        <a href="javascript:void(0);" data-filter="follower"
                                            class="inline-block nav-link px-1.5 w-full py-1 text-xs transition-all duration-300 ease-linear rounded-md text-slate-500 border border-transparent [&.active]:bg-white [&.active]:text-custom-500 hover:text-custom-500 active:text-custom-500 dark:text-zink-200 dark:hover:text-custom-500 dark:[&.active]:bg-zink-600 -mb-[1px]">Followers</a>
                                    </li>
                                    <li class="grow">
                                        <a href="javascript:void(0);" data-filter="invite"
                                            class="inline-block nav-link px-1.5 w-full py-1 text-xs transition-all duration-300 ease-linear rounded-md text-slate-500 border border-transparent [&.active]:bg-white [&.active]:text-custom-500 hover:text-custom-500 active:text-custom-500 dark:text-zink-200 dark:hover:text-custom-500 dark:[&.active]:bg-zink-600 -mb-[1px]">Invites</a>
                                    </li>
                                </ul>

                            </div>
                            <div data-simplebar class="max-h-[350px]">
                                <div class="flex flex-col gap-1" id="notification-list">
                                    <a href="#!"
                                        class="flex gap-3 p-4 product-item hover:bg-slate-50 dark:hover:bg-zink-500 follower">
                                        <div class="w-10 h-10 rounded-md shrink-0 bg-slate-100">
                                            <img src="{{ asset('assets/images/avatar-3.png') }}" alt=""
                                                class="rounded-md">
                                        </div>
                                        <div class="grow">
                                            <h6 class="mb-1 font-medium"><b>@willie_passem</b> followed you</h6>
                                            <p class="mb-0 text-sm text-slate-500 dark:text-zink-300"><i
                                                    data-lucide="clock" class="inline-block w-3.5 h-3.5 mr-1"></i>
                                                <span class="align-middle">Wednesday 03:42 PM</span>
                                            </p>
                                        </div>
                                        <div
                                            class="flex items-center self-start gap-2 text-xs text-slate-500 shrink-0 dark:text-zink-300">
                                            <div class="w-1.5 h-1.5 bg-custom-500 rounded-full"></div> 4 sec
                                        </div>
                                    </a>
                                    <a href="#!"
                                        class="flex gap-3 p-4 product-item hover:bg-slate-50 dark:hover:bg-zink-500 mention">
                                        <div class="w-10 h-10 bg-yellow-100 rounded-md shrink-0">
                                            <img src="{{ asset('assets/images/avatar-5.png') }}" alt=""
                                                class="rounded-md">
                                        </div>
                                        <div class="grow">
                                            <h6 class="mb-1 font-medium"><b>@caroline_jessica</b> commented on your
                                                post</h6>
                                            <p class="mb-3 text-sm text-slate-500 dark:text-zink-300"><i
                                                    data-lucide="clock" class="inline-block w-3.5 h-3.5 mr-1"></i>
                                                <span class="align-middle">Wednesday 03:42 PM</span>
                                            </p>
                                            <div
                                                class="p-2 rounded bg-slate-100 text-slate-500 dark:bg-zink-500 dark:text-zink-300">
                                                Amazing! Fast, to the point, professional and really amazing to work
                                                with them!!!</div>
                                        </div>
                                        <div
                                            class="flex items-center self-start gap-2 text-xs text-slate-500 shrink-0 dark:text-zink-300">
                                            <div class="w-1.5 h-1.5 bg-custom-500 rounded-full"></div> 15 min
                                        </div>
                                    </a>
                                    <a href="#!"
                                        class="flex gap-3 p-4 product-item hover:bg-slate-50 dark:hover:bg-zink-500 invite">
                                        <div
                                            class="flex items-center justify-center w-10 h-10 bg-red-100 rounded-md shrink-0">
                                            <i data-lucide="shopping-bag"
                                                class="w-5 h-5 text-red-500 fill-red-200"></i>
                                        </div>
                                        <div class="grow">
                                            <h6 class="mb-1 font-medium">Successfully purchased a business plan for
                                                <span class="text-red-500">$199.99</span>
                                            </h6>
                                            <p class="mb-0 text-sm text-slate-500 dark:text-zink-300"><i
                                                    data-lucide="clock" class="inline-block w-3.5 h-3.5 mr-1"></i>
                                                <span class="align-middle">Monday 11:26 AM</span>
                                            </p>
                                        </div>
                                        <div
                                            class="flex items-center self-start gap-2 text-xs text-slate-500 shrink-0 dark:text-zink-300">
                                            <div class="w-1.5 h-1.5 bg-custom-500 rounded-full"></div> Yesterday
                                        </div>
                                    </a>
                                    <a href="#!"
                                        class="flex gap-3 p-4 product-item hover:bg-slate-50 dark:hover:bg-zink-500 mention">
                                        <div class="relative shrink-0">
                                            <div class="w-10 h-10 bg-pink-100 rounded-md">
                                                <img src="{{ asset('assets/images/avatar-7.png') }}" alt=""
                                                    class="rounded-md">
                                            </div>
                                            <div class="absolute text-orange-500 -bottom-0.5 -right-0.5 text-16">
                                                <i class="ri-heart-fill"></i>
                                            </div>
                                        </div>
                                        <div class="grow">
                                            <h6 class="mb-1 font-medium"><b>@scott</b> liked your post</h6>
                                            <p class="mb-0 text-sm text-slate-500 dark:text-zink-300"><i
                                                    data-lucide="clock" class="inline-block w-3.5 h-3.5 mr-1"></i>
                                                <span class="align-middle">Thursday 06:59 AM</span>
                                            </p>
                                        </div>
                                        <div
                                            class="flex items-center self-start gap-2 text-xs text-slate-500 shrink-0 dark:text-zink-300">
                                            <div class="w-1.5 h-1.5 bg-custom-500 rounded-full"></div> 1 Week
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 p-4 border-t border-slate-200 dark:border-zink-500">
                                <div class="grow">
                                    <a href="#!">Manage Notification</a>
                                </div>
                                <div class="shrink-0">
                                    <button type="button"
                                        class="px-2 py-1.5 text-xs text-white transition-all duration-200 ease-linear btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100">View
                                        All Notification <i data-lucide="move-right"
                                            class="inline-block w-3.5 h-3.5 ml-1"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="relative flex items-center dropdown h-header">
                        <button type="button"
                            class="inline-block p-0 transition-all duration-200 ease-linear bg-topbar rounded-full text-topbar-item dropdown-toggle btn hover:bg-topbar-item-bg-hover hover:text-topbar-item-hover group-data-[topbar=dark]:text-topbar-item-dark group-data-[topbar=dark]:bg-topbar-dark group-data-[topbar=dark]:hover:bg-topbar-item-bg-hover-dark group-data-[topbar=dark]:hover:text-topbar-item-hover-dark group-data-[topbar=brand]:bg-topbar-brand group-data-[topbar=brand]:hover:bg-topbar-item-bg-hover-brand group-data-[topbar=brand]:hover:text-topbar-item-hover-brand group-data-[topbar=dark]:dark:bg-zink-700 group-data-[topbar=dark]:dark:hover:bg-zink-600 group-data-[topbar=brand]:text-topbar-item-brand group-data-[topbar=dark]:dark:hover:text-zink-50 group-data-[topbar=dark]:dark:text-zink-200"
                            id="dropdownMenuButton" data-bs-toggle="dropdown">
                            <div class="bg-pink-100 rounded-full">
                                <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('assets/images/avatar-6.png') }}" alt="" class="w-[37.5px] h-[37.5px] rounded-full">
                            </div>
                        </button>
                        <div class="absolute z-50 hidden p-4 ltr:text-left rtl:text-right bg-white rounded-md shadow-md !top-4 dropdown-menu min-w-[14rem] dark:bg-zink-600"
                            aria-labelledby="dropdownMenuButton">
                            <h6 class="mb-2 text-sm font-normal text-slate-500 dark:text-zink-300">Welcome to Leaper
                                Journal
                            </h6>
                            <a href="#!" class="flex gap-3 mb-3">
                                <div class="relative inline-block shrink-0">
                                    <div class="rounded bg-slate-100 dark:bg-zink-500">
                                        
                                        <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('assets/images/avatar-6.png') }}"
                                            alt="" class="w-12 h-12 rounded">
                                    </div>
                                    <span
                                        class="-top-1 ltr:-right-1 rtl:-left-1 absolute w-2.5 h-2.5 bg-green-400 border-2 border-white rounded-full dark:border-zink-600"></span>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-15">{{ Auth::user()->name }}</h6>
                                    <p class="text-slate-500 dark:text-zink-300">{{ Auth::user()->role }}</p>
                                </div>
                            </a>
                            <ul>
                                <li>
                                    <a class="block ltr:pr-4 rtl:pl-4 py-1.5 text-base font-medium transition-all duration-200 ease-linear text-slate-600 dropdown-item hover:text-custom-500 focus:text-custom-500 dark:text-zink-200 dark:hover:text-custom-500 dark:focus:text-custom-500"
                                        href=""><i data-lucide="user-2"
                                            class="inline-block size-4 ltr:mr-2 rtl:ml-2"></i> Profile</a>
                                </li>

                                <li class="pt-2 mt-2 border-t border-slate-200 dark:border-zink-500">
                                    <!-- Authentication Logout Form -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left block ltr:pr-4 rtl:pl-4 py-1.5 text-base font-medium transition-all duration-200 ease-linear text-slate-600 dropdown-item hover:text-custom-500 focus:text-custom-500 dark:text-zink-200 dark:hover:text-custom-500 dark:focus:text-custom-500">
                                            <i data-lucide="log-out"
                                                class="inline-block size-4 ltr:mr-2 rtl:ml-2"></i>
                                            Sign Out
                                        </button>
                                    </form>
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
