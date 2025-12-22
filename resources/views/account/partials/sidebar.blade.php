@php
    // Ensure we have a $user
    $user = $user ?? auth()->user();

    // Base class for menu items — increased to text-base
    $itemBase = 'flex items-center gap-2 px-5 py-2.5 text-base rounded-xl mx-3 mt-3';
@endphp

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm flex flex-col">

    {{-- Top User Info --}}
    <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3">
        <div
            class="h-10 w-10 rounded-full bg-gradient-to-br from-[#D4AF37] to-[#8f6a10] flex items-center justify-center text-base font-semibold text-white">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div class="leading-tight">
            <div class="text-base font-semibold text-gray-900">
                Hi, {{ $user->name }}
            </div>
            <div class="text-sm text-gray-500">
                {{ $user->email }}
            </div>
        </div>
    </div>

    {{-- Menu --}}
    <nav class="flex-1 pb-4">

        {{-- Account --}}
        <a href="{{ route('account.index') }}"
            class="{{ $itemBase }}
            {{ request()->routeIs('account.index') ? 'bg-[#FFF9E6] text-[#8f6a10]' : 'text-gray-700 hover:bg-gray-50 hover:text-[#0A0A0C]' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 12a5 5 0 100-10 5 5 0 000 10zm8 9a8 8 0 10-16 0" />
            </svg>
            <span>Account</span>
        </a>

        {{-- Orders --}}
        <a href="{{ route('account.orders.index') }}"
            class="{{ $itemBase }}
            {{ request()->routeIs('account.orders.*') ? 'bg-[#FFF9E6] text-[#8f6a10]' : 'text-gray-700 hover:bg-gray-50 hover:text-[#0A0A0C]' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 8l1 12h10l1-12H6zm2-3a4 4 0 118 0v3H8V5z" />
            </svg>
            <span>Orders</span>
        </a>

        {{-- Favorites --}}
        <a href="{{ route('account.favorites.index') }}"
            class="{{ $itemBase }} text-gray-700 hover:bg-gray-50 hover:text-[#0A0A0C]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4.318 6.318a5.5 5.5 0 017.778 0L12 6.586l-.096-.097a5.5 5.5 0 117.778 7.778L12 21.192l-7.682-7.682a5.5 5.5 0 010-7.192z" />
            </svg>
            <span>Favorites</span>
        </a>

        {{-- Addresses --}}
        <a href="{{ route('account.address.index') }}"
            class="{{ $itemBase }} {{ request()->routeIs('account.address.*') ? 'bg-[#FFF9E6] text-[#8f6a10]' : 'text-gray-700 hover:bg-gray-50 hover:text-[#0A0A0C]' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 9.75L12 3l9 6.75M5.25 10.5v9A1.5 1.5 0 006.75 21h10.5a1.5 1.5 0 001.5-1.5v-9" />
            </svg>
            <span>Addresses</span>
        </a>

        {{-- Edit Profile --}}
        <a href="{{ route('account.profile.edit') }}"
            class="{{ $itemBase }} {{ request()->routeIs('account.profile.edit') ? 'bg-[#FFF9E6] text-[#8f6a10]' : 'text-gray-700 hover:bg-gray-50 hover:text-[#0A0A0C]' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.232 5.232l3.536 3.536M4 20h4l9.536-9.536a2 2 0 00-2.828-2.828L5.172 17.172A2 2 0 004 18.586V20z" />
            </svg>
            <span>Edit Profile</span>
        </a>

        <hr class="my-4 mx-4 border-gray-200">

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}" class="mx-3 mb-4">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-2 px-5 py-2.5 text-base rounded-xl text-red-500 hover:bg-red-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V5" />
                </svg>
                <span>Logout</span>
            </button>
        </form>

    </nav>

</div>
