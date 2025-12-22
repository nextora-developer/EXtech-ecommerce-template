<nav x-data="{ open: false }" class="border-b border-[#F2E8D0] bg-white/95 backdrop-blur sticky top-0 z-30">

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl5 mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">

            {{-- 左侧：Logo + 菜单 + 搜索，占据全部剩余宽度 --}}
            <div class="flex items-center flex-1">

                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <div
                            class="h-9 w-9 rounded-xl bg-gradient-to-br from-[#D4AF37] to-[#8f6a10] flex items-center justify-center text-xs font-bold text-white shadow">
                            BR
                        </div>
                        <div class="leading-tight">
                            <div class="text-base font-semibold tracking-wide text-[#0A0A0C]">
                                BRIF Shop
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Desktop Nav Links -->
                <div class="hidden sm:flex sm:items-center sm:ms-10 space-x-4">
                    @php
                        $link = 'inline-flex items-center px-4 py-2 text-base font-medium transition rounded-lg';
                    @endphp

                    <a href="{{ route('home') }}"
                        class="{{ $link }} {{ request()->routeIs('home') ? 'text-[#8f6a10] bg-[#FFF9E6]' : 'text-gray-700 hover:text-[#8f6a10] hover:bg-[#FFF9E6]' }}">
                        Home
                    </a>

                    <a href="{{ route('shop.index') }}"
                        class="{{ $link }} {{ request()->routeIs('shop.*') ? 'text-[#8f6a10] bg-[#FFF9E6]' : 'text-gray-700 hover:text-[#8f6a10] hover:bg-[#FFF9E6]' }}">
                        Shop
                    </a>

                    {{-- More dropdown --}}
                    <div x-data="{ openMore: false }" class="relative">
                        <button type="button" @click="openMore = !openMore" @keydown.escape.window="openMore = false"
                            class="{{ $link }} text-gray-700 hover:text-[#8f6a10] hover:bg-[#FFF9E6]">
                            <span>More</span>
                            <svg class="ms-1.5 h-4 w-4" :class="{ 'rotate-180': openMore }"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>

                        {{-- Dropdown menu --}}
                        <div x-cloak x-show="openMore" x-transition @click.outside="openMore = false"
                            class="absolute left-0 mt-2 w-52 rounded-xl border border-gray-200 bg-white shadow-lg z-30">
                            <div class="py-2 text-sm">
                                <a href="#"
                                    class="block px-4 py-2 text-gray-700 hover:bg-[#FFF9E6] hover:text-[#8f6a10]">
                                    About BRIF Shop
                                </a>

                                <a href="#"
                                    class="block px-4 py-2 text-gray-700 hover:bg-[#FFF9E6] hover:text-[#8f6a10]">
                                    How to Order
                                </a>

                                <a href="#"
                                    class="block px-4 py-2 text-gray-700 hover:bg-[#FFF9E6] hover:text-[#8f6a10]">
                                    FAQ
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Desktop Search Bar --}}
                <form method="GET" action="{{ route('shop.index') }}"
                    class="hidden md:flex items-center ms-4 lg:ms-8 flex-1">
                    <div class="relative w-full">
                        <input type="text" name="q" value="{{ request('q') }}"
                            placeholder="Search products..."
                            class="w-full rounded-full border border-gray-200 bg-white/80 px-5 py-2.5 text-base text-gray-700
                                   focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 focus:outline-none shadow-sm">
                        <button type="submit"
                            class="absolute right-1 top-1 bottom-1 px-4 rounded-full text-[#8f6a10] text-base hover:text-[#D4AF37]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-4.35-4.35M11 18a7 7 0 1 1 0-14 7 7 0 0 1 0 14z" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Authentication -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">

                {{-- CART ICON --}}
                <a href="{{ route('cart.index') }}" class="relative inline-flex items-center justify-center me-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-[#0A0A0C]" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>

                    <span
                        class="absolute -top-2 -right-2 min-w-[20px] h-[20px] px-1 rounded-full bg-[#D4AF37] text-[11px] font-semibold text-white flex items-center justify-center">
                        {{ auth()->user()->cart?->items->count() ?? 0}}
                    </span>

                </a>

                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-2 py-2 border border-[#D4AF37]/40 text-base leading-5 font-medium rounded-xl text-[#0A0A0C] bg-white hover:bg-[#FFF9E6] hover:border-[#D4AF37]/70 transition">

                                <div
                                    class="me-2 h-8 w-8 rounded-full bg-gradient-to-br from-[#D4AF37] to-[#8f6a10] flex items-center justify-center text-[12px] font-semibold text-white">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>

                                <div class="max-w-[140px] truncate text-left text-base">
                                    {{ Auth::user()->name }}
                                </div>

                                <svg class="ms-2 h-5 w-5 text-[#8f6a10]" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            {{-- <div class="px-4 py-3 border-b border-gray-200">
                                <div class="text-sm text-gray-500">Signed in as</div>
                                <div class="text-base font-medium text-black truncate">
                                    {{ Auth::user()->email }}
                                </div>
                            </div> --}}

                            <x-dropdown-link :href="route('account.index')" class="text-base">
                                Account
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('account.orders.index')" class="text-base">
                                My Order
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" class="text-base"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}"
                            class="text-base font-semibold px-5 py-2 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#8f6a10] text-white shadow hover:brightness-105 transition">
                            Login
                        </a>
                    </div>
                @endauth

            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-[#8f6a10] hover:text-[#D4AF37] hover:bg-[#FFF9E6] focus:outline-none transition">
                    <svg class="h-7 w-7" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden border-t border-[#D4AF37]/20 bg-white">

        {{-- Mobile Search --}}
        <div class="px-4 pt-3 pb-2">
            <form method="GET" action="{{ route('shop.index') }}">
                <div class="flex items-center gap-2">
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="Search products..."
                        class="w-full rounded-full border border-gray-200 px-4 py-2 text-sm text-gray-700
                              focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 focus:outline-none">
                    <button type="submit"
                        class="shrink-0 rounded-full px-3 py-2 bg-[#D4AF37] text-white text-xs font-semibold hover:brightness-110">
                        Search
                    </button>
                </div>
            </form>
        </div>

        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('home') }}"
                class="block px-4 py-2 text-base font-medium {{ request()->routeIs('home') ? 'text-[#8f6a10] bg-[#FFF9E6]' : 'text-gray-700 hover:bg-[#FFF9E6]' }}">
                Home
            </a>

            <a href="{{ route('shop.index') }}"
                class="block px-4 py-2 text-base font-medium {{ request()->routeIs('shop.*') ? 'text-[#8f6a10] bg-[#FFF9E6]' : 'text-gray-700 hover:bg-[#FFF9E6]' }}">
                Shop
            </a>
        </div>

        <div class="pt-4 pb-4 border-t border-gray-200">
            <div class="px-4">
                @auth
                    <div class="font-medium text-lg text-black">
                        {{ Auth::user()->name }}
                    </div>

                    <div class="text-base text-gray-600">
                        {{ Auth::user()->email }}
                    </div>
                @endauth
            </div>

            <a href="{{ route('account.index') }}"
                class="block px-4 py-2 text-base text-gray-700 hover:bg-[#FFF9E6] rounded-lg">
                My Account
            </a>

            <div class="space-y-1 px-2">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-base font-medium text-red-500 hover:text-red-600 hover:bg-red-50 rounded-lg">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="block px-4 py-2 text-base text-gray-700 hover:bg-[#FFF9E6] rounded-lg">
                        Login
                    </a>

                    <a href="{{ route('register') }}"
                        class="block px-4 py-2 text-base font-semibold text-white bg-gradient-to-r from-[#D4AF37] to-[#8f6a10] rounded-lg text-center shadow">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
