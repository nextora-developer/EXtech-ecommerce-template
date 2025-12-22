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
        <aside
            class="fixed inset-y-0 left-0 h-screen z-30
           bg-white border-r border-[#D4AF37]/20 flex flex-col"
            :class="collapsed ? 'w-20' : 'w-72'">
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
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
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
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
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
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>

                    <span class="font-medium" x-show="!collapsed" x-cloak>Orders</span>

                    <span x-show="collapsed" x-cloak
                        class="absolute left-full ml-3 px-2 py-1 rounded-lg text-xs bg-white border border-gray-200 shadow
                             opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">
                        Orders
                    </span>
                </a>

                {{-- Users --}}
                <a href="{{ route('admin.users.index') }}"
                    class="{{ $linkBase }} {{ request()->routeIs('admin.users.*') ? $active : $idle }}">
                    <span class="absolute left-0 top-2 bottom-2 w-[3px] rounded-r"
                        style="background: {{ request()->routeIs('admin.users.*') ? '#D4AF37' : 'transparent' }};"></span>

                    {{-- Heroicon: Users --}}
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.users.*') ? 'text-[#8f6a10]' : 'text-gray-500 group-hover:text-[#8f6a10]' }}"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>

                    <span class="font-medium" x-show="!collapsed" x-cloak>Users</span>

                    <span x-show="collapsed" x-cloak
                        class="absolute left-full ml-3 px-2 py-1 rounded-lg text-xs bg-white border border-gray-200 shadow
                            opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">
                        Users
                    </span>
                </a>

                {{-- Reports --}}
                <a href="{{ route('admin.reports.index') }}"
                    class="{{ $linkBase }} {{ request()->routeIs('admin.reports.*') ? $active : $idle }}">
                    <span class="absolute left-0 top-2 bottom-2 w-[3px] rounded-r"
                        style="background: {{ request()->routeIs('admin.reports.*') ? '#D4AF37' : 'transparent' }};"></span>

                    {{-- Icon: Chart Bar --}}

                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.reports.*') ? 'text-[#8f6a10]' : 'text-gray-500 group-hover:text-[#8f6a10]' }}"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" />
                    </svg>

                    <span class="font-medium" x-show="!collapsed" x-cloak>Reports</span>

                    <span x-show="collapsed" x-cloak
                        class="absolute left-full ml-3 px-2 py-1 rounded-lg text-xs bg-white border border-gray-200 shadow
                            opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">
                        Reports
                    </span>
                </a>

                {{-- Banner --}}
                <a href="{{ route('admin.banners.index') }}"
                    class="{{ $linkBase }} {{ request()->routeIs('admin.banners.*') ? $active : $idle }}">

                    <span class="absolute left-0 top-2 bottom-2 w-[3px] rounded-r"
                        style="background: {{ request()->routeIs('admin.banners.*') ? '#D4AF37' : 'transparent' }};"></span>

                    {{-- Icon: Photo --}}
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.banners.*') ? 'text-[#8f6a10]' : 'text-gray-500 group-hover:text-[#8f6a10]' }}"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>


                    <span class="font-medium" x-show="!collapsed" x-cloak>Banner</span>

                    <span x-show="collapsed" x-cloak
                        class="absolute left-full ml-3 px-2 py-1 rounded-lg text-xs bg-white border border-gray-200 shadow opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">
                        Banner
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
        <main class="flex-1 min-h-screen transition-all duration-300" :class="collapsed ? 'ml-20' : 'ml-72'">
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

    @stack('scripts')
</body>

</html>
