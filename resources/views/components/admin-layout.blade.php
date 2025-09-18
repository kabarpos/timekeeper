@props(['title' => 'Dashboard'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TimeKeeper') }} - {{ $title }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @livewireStyles
</head>
<body class="bg-gray-50 font-inter">
    <!-- Sidebar -->
    <div id="hs-application-sidebar" class="hs-overlay [--auto-close:lg] hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform w-[260px] h-full hidden fixed inset-y-0 start-0 z-[60] bg-white border-e border-gray-200 lg:block lg:translate-x-0 lg:end-auto lg:bottom-0">
        <div class="relative flex flex-col h-full max-h-full">
            <!-- Header -->
            <div class="px-6 pt-4">
                <a class="flex-none rounded-xl text-xl inline-block font-semibold focus:outline-none focus:opacity-80" href="{{ route('admin.index') }}" aria-label="TimeKeeper">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-violet-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-white text-sm"></i>
                        </div>
                        <span class="text-gray-800 font-bold">TimeKeeper</span>
                    </div>
                </a>
            </div>
            <!-- End Header -->

            <!-- Content -->
            <div class="h-full overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300">
                <nav class="hs-accordion-group p-3 w-full flex flex-col flex-wrap" data-hs-accordion-always-open>
                    <ul class="flex flex-col space-y-1">
                        <!-- Dashboard -->
                        <li>
                            <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 {{ request()->routeIs('admin.index') ? 'bg-gray-100 text-blue-600' : '' }}" href="{{ route('admin.index') }}">
                                <i class="fas fa-tachometer-alt flex-shrink-0 size-4"></i>
                                Dashboard
                            </a>
                        </li>
                        <!-- End Dashboard -->

                        <!-- Timer Control -->
                        <li>
                            <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 {{ request()->routeIs('admin.timer') ? 'bg-gray-100 text-blue-600' : '' }}" href="{{ route('admin.timer') }}">
                                <i class="fas fa-clock flex-shrink-0 size-4"></i>
                                Timer Control
                            </a>
                        </li>
                        <!-- End Timer Control -->

                        <!-- Messages -->
                        <li>
                            <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 {{ request()->routeIs('admin.messages') ? 'bg-gray-100 text-blue-600' : '' }}" href="{{ route('admin.messages') }}">
                                <i class="fas fa-comments flex-shrink-0 size-4"></i>
                                Messages
                            </a>
                        </li>
                        <!-- End Messages -->

                        <!-- Divider -->
                        <li class="pt-2">
                            <div class="border-t border-gray-200"></div>
                        </li>

                        <!-- Display Links -->
                        <li class="pt-2">
                            <span class="px-2.5 text-xs font-semibold uppercase text-gray-400">Display Links</span>
                        </li>

                        <li>
                            <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100" href="{{ route('display.index') }}" target="_blank">
                                <i class="fas fa-desktop flex-shrink-0 size-4"></i>
                                Main Display
                                <i class="fas fa-external-link-alt ml-auto text-xs text-gray-400"></i>
                            </a>
                        </li>

                        <li>
                            <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100" href="{{ route('display.timer') }}" target="_blank">
                                <i class="fas fa-stopwatch flex-shrink-0 size-4"></i>
                                Timer Only
                                <i class="fas fa-external-link-alt ml-auto text-xs text-gray-400"></i>
                            </a>
                        </li>

                        <li>
                            <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100" href="{{ route('display.message') }}" target="_blank">
                                <i class="fas fa-comment-alt flex-shrink-0 size-4"></i>
                                Message Only
                                <i class="fas fa-external-link-alt ml-auto text-xs text-gray-400"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <!-- End Content -->

            <!-- Footer -->
            <div class="mt-auto">
                <div class="p-3 border-t border-gray-200">
                    <div class="flex items-center gap-x-3">
                        <div class="flex-shrink-0">
                            <div class="size-8 bg-gradient-to-br from-blue-600 to-violet-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-medium">{{ substr(auth()->user()->name ?? 'A', 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="grow">
                            <span class="block text-sm font-medium text-gray-800">{{ auth()->user()->name ?? 'Admin' }}</span>
                            <span class="block text-xs text-gray-500">Administrator</span>
                        </div>
                        <div class="hs-dropdown relative inline-flex" data-hs-dropdown-placement="top-start">
                            <button id="hs-dropdown-account" type="button" class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 focus:outline-none disabled:opacity-50 disabled:pointer-events-none" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg mt-2 after:h-4 after:absolute after:-bottom-4 after:start-0 after:w-full before:h-4 before:absolute before:-top-4 before:start-0 before:w-full" role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-account">
                                <div class="p-1">
                                    @auth
                                        <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100" href="{{ route('profile.edit') }}">
                                            <i class="fas fa-user flex-shrink-0 size-4"></i>
                                            Profile
                                        </a>
                                        <div class="border-t border-gray-200 my-1"></div>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100">
                                                <i class="fas fa-sign-out-alt flex-shrink-0 size-4"></i>
                                                Logout
                                            </button>
                                        </form>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Footer -->
        </div>
    </div>
    <!-- End Sidebar -->

    <!-- Content -->
    <div class="w-full lg:ps-64">
        <!-- Header -->
        <div class="sticky top-0 inset-x-0 z-20 bg-white border-y px-4 sm:px-6 lg:px-8 lg:hidden">
            <div class="flex items-center py-2">
                <!-- Navigation Toggle -->
                <button type="button" class="size-8 flex justify-center items-center gap-x-2 border border-gray-200 text-gray-800 hover:text-gray-500 rounded-lg focus:outline-none focus:text-gray-500 disabled:opacity-50 disabled:pointer-events-none" aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-application-sidebar" aria-label="Toggle navigation" data-hs-overlay="#hs-application-sidebar">
                    <span class="sr-only">Toggle Navigation</span>
                    <i class="fas fa-bars flex-shrink-0 size-4"></i>
                </button>
                <!-- End Navigation Toggle -->

                <!-- Breadcrumb -->
                <ol class="ms-3 flex items-center whitespace-nowrap">
                    <li class="flex items-center text-sm text-gray-800">
                        TimeKeeper Admin
                        <i class="fas fa-chevron-right flex-shrink-0 mx-3 overflow-visible text-gray-400 text-xs"></i>
                    </li>
                    <li class="text-sm font-semibold text-gray-800 truncate" aria-current="page">
                        {{ $title }}
                    </li>
                </ol>
                <!-- End Breadcrumb -->
            </div>
        </div>
        <!-- End Header -->

        <!-- Main Content -->
        <main class="p-4 sm:p-6 space-y-4 sm:space-y-6">
            @if (isset($header))
                <div class="max-w-7xl mx-auto">
                    {{ $header }}
                </div>
            @endif

            {{ $slot }}
        </main>
        <!-- End Main Content -->
    </div>
    <!-- End Content -->

    @livewireScripts
    
    <script src="{{ asset('node_modules/preline/dist/preline.js') }}"></script>
    <script>
        // Initialize Preline components
        window.addEventListener('load', () => {
            if (window.HSStaticMethods) {
                window.HSStaticMethods.autoInit();
            }
        });
        
        // Reinitialize after Livewire updates
        document.addEventListener('livewire:navigated', () => {
            if (window.HSStaticMethods) {
                window.HSStaticMethods.autoInit();
            }
        });
    </script>
</body>
</html>