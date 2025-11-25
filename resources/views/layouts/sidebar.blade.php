<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: false }" :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MyRentals') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="flex h-screen bg-gray-100 dark:bg-gray-900 overflow-hidden" x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-lg transform transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static flex flex-col h-screen"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

            <!-- Sidebar Header -->
            <div
                class="flex items-center justify-between flex-shrink-0 h-16 px-6 bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-800 dark:to-blue-900">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    <span class="ml-3 text-xl font-bold text-white">MyRentals</span>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="flex-1 px-4 py-6 overflow-y-auto scrollbar-hide">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-4 py-3 mb-2 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-blue-100 dark:bg-gray-700 text-blue-700 dark:text-blue-400' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span class="ml-3 font-medium">Dashboard</span>
                </a>

                <!-- Apartments -->
                <a href="{{ route('apartments.index') }}"
                    class="flex items-center px-4 py-3 mb-2 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 {{ request()->routeIs('apartments.*') ? 'bg-blue-100 dark:bg-gray-700 text-blue-700 dark:text-blue-400' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    <span class="ml-3 font-medium">Apartments</span>
                </a>

                <!-- Leases Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('leases.*') || request()->routeIs('lease-payments.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-3 mb-2 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 {{ request()->routeIs('leases.*') || request()->routeIs('lease-payments.*') ? 'bg-blue-100 dark:bg-gray-700 text-blue-700 dark:text-blue-400' : '' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <span class="ml-3 font-medium">Leases & Tenants</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="ml-4 space-y-1">
                        <a href="{{ route('leases.index') }}"
                            class="flex items-center px-4 py-2 text-sm text-gray-600 dark:text-gray-300 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 {{ request()->routeIs('leases.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
                            <span class="ml-3">All Leases</span>
                        </a>
                        <a href="{{ route('lease-payments.index') }}"
                            class="flex items-center px-4 py-2 text-sm text-gray-600 dark:text-gray-300 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 {{ request()->routeIs('lease-payments.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
                            <span class="ml-3">Monthly Payments</span>
                        </a>
                    </div>
                </div>

                <!-- Expenses -->
                <a href="{{ route('expenses.index') }}"
                    class="flex items-center px-4 py-3 mb-2 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 {{ request()->routeIs('expenses.*') ? 'bg-blue-100 dark:bg-gray-700 text-blue-700 dark:text-blue-400' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .672-3 1.5S10.343 11 12 11s3 .672 3 1.5S13.657 14 12 14s-3 .672-3 1.5S10.343 17 12 17m0-9V3m0 18v-4" />
                    </svg>
                    <span class="ml-3 font-medium">Expenses</span>
                </a>

                <!-- Divider -->
                <div class="my-4 border-t border-gray-200 dark:border-gray-700"></div>

                <!-- Water Management Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('water-services.*') || request()->routeIs('water-readings.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-3 mb-2 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 {{ request()->routeIs('water-services.*') || request()->routeIs('water-readings.*') ? 'bg-blue-100 dark:bg-gray-700 text-blue-700 dark:text-blue-400' : '' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                </path>
                            </svg>
                            <span class="ml-3 font-medium">Water</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="ml-4 space-y-1">
                        <a href="{{ route('water-services.index') }}"
                            class="flex items-center px-4 py-2 text-sm text-gray-600 dark:text-gray-300 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 {{ request()->routeIs('water-services.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
                            <span class="ml-3">Services</span>
                        </a>
                        <a href="{{ route('water-readings.index') }}"
                            class="flex items-center px-4 py-2 text-sm text-gray-600 dark:text-gray-300 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 {{ request()->routeIs('water-readings.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
                            <span class="ml-3">Meter Readings</span>
                        </a>
                    </div>
                </div>

                <!-- Electric Management Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('electric-services.*') || request()->routeIs('electric-readings.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-3 mb-2 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 {{ request()->routeIs('electric-services.*') || request()->routeIs('electric-readings.*') ? 'bg-blue-100 dark:bg-gray-700 text-blue-700 dark:text-blue-400' : '' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span class="ml-3 font-medium">Electric</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="ml-4 space-y-1">
                        <a href="{{ route('electric-services.index') }}"
                            class="flex items-center px-4 py-2 text-sm text-gray-600 dark:text-gray-300 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 {{ request()->routeIs('electric-services.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
                            <span class="ml-3">Services</span>
                        </a>
                        <a href="{{ route('electric-readings.index') }}"
                            class="flex items-center px-4 py-2 text-sm text-gray-600 dark:text-gray-300 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 {{ request()->routeIs('electric-readings.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
                            <span class="ml-3">Meter Readings</span>
                        </a>
                    </div>
                </div>

                <!-- Divider -->
                <div class="my-4 border-t border-gray-200 dark:border-gray-700"></div>

                <!-- Reports -->
                <a href="#"
                    class="flex items-center px-4 py-3 mb-2 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span class="ml-3 font-medium">Reports</span>
                </a>

                <!-- Divider -->
                <div class="my-4 border-t border-gray-200 dark:border-gray-700"></div>

                <!-- Settings -->
                <a href="#"
                    class="flex items-center px-4 py-3 mb-2 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="ml-3 font-medium">Settings</span>
                </a>
            </nav>

            <!-- User Profile Section -->
            <div class="flex-shrink-0 px-4 py-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-600 to-blue-700 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Mobile Sidebar Backdrop -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden" style="display: none;"></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden h-screen">
            <!-- Top Navigation Bar -->
            <header class="flex-shrink-0 bg-white dark:bg-gray-800 shadow-sm h-16">
                <div class="flex items-center justify-between h-full px-6">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none lg:hidden">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <h1 class="ml-4 text-2xl font-semibold text-gray-800 dark:text-gray-200 lg:ml-0">
                            @yield('title', 'Dashboard')</h1>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Dark Mode Toggle -->
                        <button @click="darkMode = !darkMode"
                            class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                                </path>
                            </svg>
                            <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </button>

                        <!-- Notifications -->
                        <button
                            class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-gray-900 scrollbar-hide">
                <div class="container mx-auto px-6 py-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>

</html>
