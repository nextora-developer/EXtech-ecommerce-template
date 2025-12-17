<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>BRIF Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-[#f6f6f6] text-gray-900">
    <div x-data="{
        collapsed: (localStorage.getItem('admin_sidebar_collapsed') === '1'),
        toggle() {
            this.collapsed = !this.collapsed;
            localStorage.setItem('admin_sidebar_collapsed', this.collapsed ? '1' : '0');
        }
    }" class="min-h-screen flex">
        {{-- SIDEBAR --}}
        <aside class="bg-white border-r border-[#D4AF37]/20 flex flex-col" :class="collapsed ? 'w-20' : 'w-72'">
            {{-- Brand --}}
            <div class="px-5 py-5 border-b border-[#D4AF37]/15">
                <div class="flex items-center" :class="collapsed ? 'justify-center' : 'justify-between'">
                    <div class="min-w-0" x-show="!collapsed" x-cloak>
                        <div
                            class="text-lg font-semibold tracking-wide whitespace-nowrap overflow-hidden text-ellipsis">
                            <span class="text-[#D4AF37]">BRIF</span> Admin
                        </div>
                        <div class="text-xs text-gray-500 mt-1">Control Panel</div>
                    </div>

                    <button type="button" @click="toggle()"
                        class="inline-flex items-center justify-center h-10 w-10 rounded-xl
                   border border-gray-200 hover:border-[#D4AF37]/40 hover:bg-[#D4AF37]/10 transition
                   focus:outline-none focus-visible:outline-none"
                        title="Toggle sidebar">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                        </svg>
                    </button>
                </div>
            </div>


            @php
                $linkBase = "relative group flex items-center gap-3 px-4 py-3 rounded-xl transition
             outline-none focus:outline-none focus-visible:outline-none
             focus-visible:ring-2 focus-visible:ring-[#D4AF37]/35
             focus-visible:ring-offset-2 focus-visible:ring-offset-white";
                $idle = 'text-gray-700 hover:bg-[#D4AF37]/10 hover:text-[#8f6a10]';
                $active = 'bg-[#D4AF37]/12 text-[#8f6a10] border border-[#D4AF37]/25';
            @endphp

            {{-- Nav --}}
            <nav class="p-4 space-y-2">
                {{-- Dashboard --}}
                <a href="{{ route('admin.home') }}"
                    class="{{ $linkBase }} {{ request()->routeIs('admin.home') ? $active : $idle }}">
                    {{-- Active left gold line --}}
                    <span class="absolute left-0 top-2 bottom-2 w-[3px] rounded-r"
                        style="background: {{ request()->routeIs('admin.home') ? '#D4AF37' : 'transparent' }};"></span>

                    {{-- Heroicon: Home --}}
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.home') ? 'text-[#8f6a10]' : 'text-gray-500 group-hover:text-[#8f6a10]' }}"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M3 10.5L12 3l9 7.5V21a.75.75 0 01-.75.75H3.75A.75.75 0 013 21V10.5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 21V12h6v9" />
                    </svg>

                    <span class="font-medium" x-show="!collapsed" x-cloak>Dashboard</span>

                    {{-- Tooltip when collapsed --}}
                    <span x-show="collapsed" x-cloak
                        class="absolute left-full ml-3 px-2 py-1 rounded-lg text-xs bg-white border border-gray-200 shadow
                             opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">
                        Dashboard
                    </span>
                </a>

                {{-- Categories --}}
                <a href="{{ route('admin.categories.index') }}"
                    class="{{ $linkBase }} {{ request()->routeIs('admin.categories.*') ? $active : $idle }}">
                    <span class="absolute left-0 top-2 bottom-2 w-[3px] rounded-r"
                        style="background: {{ request()->routeIs('admin.categories.*') ? '#D4AF37' : 'transparent' }};"></span>

                    {{-- Heroicon: Tag --}}
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.categories.*') ? 'text-[#8f6a10]' : 'text-gray-500 group-hover:text-[#8f6a10]' }}"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M7.5 7.5h.01M3 8.25V3h5.25L21 15.75 15.75 21 3 8.25z" />
                    </svg>

                    <span class="font-medium" x-show="!collapsed" x-cloak>Categories</span>

                    <span x-show="collapsed" x-cloak
                        class="absolute left-full ml-3 px-2 py-1 rounded-lg text-xs bg-white border border-gray-200 shadow
                             opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">
                        Categories
                    </span>
                </a>

                {{-- Products --}}
                <a href="{{ route('admin.products.index') }}"
                    class="{{ $linkBase }} {{ request()->routeIs('admin.products.*') ? $active : $idle }}">
                    <span class="absolute left-0 top-2 bottom-2 w-[3px] rounded-r"
                        style="background: {{ request()->routeIs('admin.products.*') ? '#D4AF37' : 'transparent' }};"></span>

                    {{-- Heroicon: Cube --}}
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.products.*') ? 'text-[#8f6a10]' : 'text-gray-500 group-hover:text-[#8f6a10]' }}"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M21 8.25l-9 5.25-9-5.25M12 13.5V21M3 8.25V18a1.5 1.5 0 00.75 1.3l7.5 4.33a1.5 1.5 0 001.5 0l7.5-4.33A1.5 1.5 0 0021 18V8.25M12 3l9 5.25-9 5.25L3 8.25 12 3z" />
                    </svg>

                    <span class="font-medium" x-show="!collapsed" x-cloak>Products</span>

                    <span x-show="collapsed" x-cloak
                        class="absolute left-full ml-3 px-2 py-1 rounded-lg text-xs bg-white border border-gray-200 shadow
                             opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">
                        Products
                    </span>
                </a>

                {{-- Orders --}}
                <a href="{{ route('admin.orders.index') }}"
                    class="{{ $linkBase }} {{ request()->routeIs('admin.orders.*') ? $active : $idle }}">
                    <span class="absolute left-0 top-2 bottom-2 w-[3px] rounded-r"
                        style="background: {{ request()->routeIs('admin.orders.*') ? '#D4AF37' : 'transparent' }};"></span>

                    {{-- Heroicon: Shopping Bag --}}
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.orders.*') ? 'text-[#8f6a10]' : 'text-gray-500 group-hover:text-[#8f6a10]' }}"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M15 7.5a3 3 0 10-6 0M3.75 7.5h16.5l-1.2 13.2a1.5 1.5 0 01-1.5 1.3H6.45a1.5 1.5 0 01-1.5-1.3L3.75 7.5z" />
                    </svg>

                    <span class="font-medium" x-show="!collapsed" x-cloak>Orders</span>

                    <span x-show="collapsed" x-cloak
                        class="absolute left-full ml-3 px-2 py-1 rounded-lg text-xs bg-white border border-gray-200 shadow
                             opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">
                        Orders
                    </span>
                </a>
            </nav>

            {{-- Logout --}}
            <div class="p-4 mt-auto border-t border-[#D4AF37]/10">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button
                        class="w-full py-3 rounded-xl font-semibold
                               bg-white border border-[#D4AF37]/35 text-[#8f6a10]
                               hover:bg-[#D4AF37]/10 transition"
                        :class="collapsed ? 'px-0' : ''">
                        <span x-show="!collapsed" x-cloak>Logout</span>
                        <span x-show="collapsed" x-cloak class="inline-flex items-center justify-center">
                            {{-- Heroicon: Arrow Right On Rectangle --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#8f6a10]" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M18 12H9m0 0l3-3m-3 3l3 3" />
                            </svg>
                        </span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN --}}
        <main class="flex-1">
            {{-- Top bar --}}
            <header class="bg-white border-b border-[#D4AF37]/15 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <span class="inline-block w-2 h-2 rounded-full bg-[#D4AF37]"></span>
                        Logged in as <span class="font-semibold text-gray-900">{{ auth()->user()->email }}</span>
                    </div>
                </div>
            </header>

            <div class="p-6">
                @if (session('success'))
                    <div class="mb-4 p-3 rounded-xl bg-green-50 border border-green-200 text-green-800">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-800">
                        {{ $errors->first() }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>

</html>
