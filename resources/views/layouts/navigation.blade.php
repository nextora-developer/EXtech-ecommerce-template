<nav x-data="{ open: false }" class="bg-white/95 border-b border-[#D4AF37]/25 backdrop-blur shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <div
                            class="h-9 w-9 rounded-xl bg-gradient-to-br from-[#D4AF37] to-[#8f6a10] flex items-center justify-center text-xs font-bold text-white shadow">
                            BR
                        </div>
                        <div class="leading-tight">
                            <div class="text-sm font-semibold tracking-wide text-[#0A0A0C]">
                                BRIF Shop
                            </div>
                            <div class="text-[11px] text-gray-500">
                                Premium Selection
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Desktop Nav Links -->
                <div class="hidden sm:flex sm:items-center sm:ms-10 space-x-4">
                    @php
                        $link = 'inline-flex items-center px-3 py-2 text-sm font-medium transition rounded-lg';
                    @endphp

                    <a href="{{ route('home') }}"
                        class="{{ $link }} {{ request()->routeIs('home') ? 'text-[#8f6a10] bg-[#FFF9E6]' : 'text-gray-700 hover:text-[#8f6a10] hover:bg-[#FFF9E6]' }}">
                        Home
                    </a>

                    <a href="{{ route('shop.index') }}"
                        class="{{ $link }} {{ request()->routeIs('shop.*') ? 'text-[#8f6a10] bg-[#FFF9E6]' : 'text-gray-700 hover:text-[#8f6a10] hover:bg-[#FFF9E6]' }}">
                        Shop
                    </a>
                </div>
            </div>

            <!-- Right Authentication -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-[#D4AF37]/40 text-sm leading-4 font-medium rounded-xl text-[#0A0A0C] bg-white hover:bg-[#FFF9E6] hover:border-[#D4AF37]/70 transition">
                                <div
                                    class="me-2 h-7 w-7 rounded-full bg-gradient-to-br from-[#D4AF37] to-[#8f6a10] flex items-center justify-center text-[11px] font-semibold text-white">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="max-w-[120px] truncate text-left">
                                    {{ Auth::user()->name }}
                                </div>

                                <svg class="ms-2 h-4 w-4 text-[#8f6a10]" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-3 py-2 border-b border-gray-200">
                                <div class="text-xs text-gray-500">Signed in as</div>
                                <div class="text-sm font-medium text-black truncate">
                                    {{ Auth::user()->email }}
                                </div>
                            </div>

                            {{-- My Account --}}
                            <x-dropdown-link :href="route('account.index')">
                                My Account
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}"
                            class="text-sm font-medium text-[#0A0A0C] hover:text-[#8f6a10] hover:underline">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                            class="text-sm font-semibold px-4 py-2 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#8f6a10] text-white shadow hover:brightness-105 transition">
                            Register
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-[#8f6a10] hover:text-[#D4AF37] hover:bg-[#FFF9E6] focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
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
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('home') }}"
                class="block px-4 py-2 text-sm font-medium {{ request()->routeIs('home') ? 'text-[#8f6a10] bg-[#FFF9E6]' : 'text-gray-700 hover:bg-[#FFF9E6]' }}">
                Home
            </a>

            <a href="{{ route('shop.index') }}"
                class="block px-4 py-2 text-sm font-medium {{ request()->routeIs('shop.*') ? 'text-[#8f6a10] bg-[#FFF9E6]' : 'text-gray-700 hover:bg-[#FFF9E6]' }}">
                Shop
            </a>
        </div>

        <div class="pt-4 pb-4 border-t border-gray-200">
            <div class="px-4">
                @auth
                    <div class="font-medium text-base text-black">
                        {{ Auth::user()->name }}
                    </div>
                    <div class="text-sm text-gray-600">
                        {{ Auth::user()->email }}
                    </div>
                @endauth
            </div>

            <a href="{{ route('account.index') }}"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#FFF9E6] rounded-lg">
                My Account
            </a>

            <div class="space-y-1 px-2">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm font-medium text-red-500 hover:text-red-600 hover:bg-red-50 rounded-lg">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#FFF9E6] rounded-lg">
                        Login
                    </a>

                    <a href="{{ route('register') }}"
                        class="block px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-[#D4AF37] to-[#8f6a10] rounded-lg text-center shadow">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
