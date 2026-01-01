@php
    $user = $user ?? auth()->user();

    // Base class: Added smooth transitions and slightly tighter tracking for a high-end feel
    $itemBase = 'flex items-center gap-3 px-5 py-3 text-sm font-medium rounded-xl mx-3 mt-1.5 transition-all duration-200 group';
    
    // Active class: Subtle shadow and richer gold tones
    $activeClass = 'bg-[#fcfaf6] text-[#8f6a10] shadow-sm shadow-[#8f6a10]/5 ring-1 ring-[#8f6a10]/10';
    
    // Inactive class: Gray-600 is often more readable/premium than Gray-700 on white
    $inactiveClass = 'text-gray-600 hover:bg-gray-50 hover:text-black';
@endphp

<div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm flex flex-col sticky top-28 overflow-hidden">

    {{-- Top User Info: Concierge Style --}}
    <div class="px-6 py-6 border-b border-gray-50 bg-gradient-to-b from-gray-50/50 to-transparent flex items-center gap-4">
        <div class="relative">
            <div class="h-12 w-12 rounded-2xl bg-gradient-to-tr from-[#0A0A0C] to-[#2D2D2E] flex items-center justify-center text-lg font-bold text-white shadow-inner">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            {{-- Small status indicator --}}
            <div class="absolute -bottom-1 -right-1 h-4 w-4 bg-emerald-500 border-2 border-white rounded-full"></div>
        </div>
        <div class="leading-tight">
            <p class="text-[10px] font-black uppercase tracking-[0.15em] text-[#8f6a10] mb-0.5">Member</p>
            <h3 class="text-base font-bold text-gray-900 truncate max-w-[140px]">
                {{ $user->name }}
            </h3>
        </div>
    </div>

    {{-- Menu --}}
    <nav class="flex-1 py-4">

        {{-- Account --}}
        <a href="{{ route('account.index') }}"
            class="{{ $itemBase }} {{ request()->routeIs('account.index') ? $activeClass : $inactiveClass }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span>Overview</span>
        </a>

        {{-- Orders --}}
        <a href="{{ route('account.orders.index') }}"
            class="{{ $itemBase }} {{ request()->routeIs('account.orders.*') ? $activeClass : $inactiveClass }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <span>My Orders</span>
        </a>

        {{-- Favorites --}}
        <a href="{{ route('account.favorites.index') }}"
            class="{{ $itemBase }} {{ request()->routeIs('account.favorites.*') ? $activeClass : $inactiveClass }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a5.5 5.5 0 017.778 0L12 6.586l-.096-.097a5.5 5.5 0 117.778 7.778L12 21.192l-7.682-7.682a5.5 5.5 0 010-7.192z" />
            </svg>
            <span>Wishlist</span>
        </a>

        {{-- Addresses --}}
        <a href="{{ route('account.address.index') }}"
            class="{{ $itemBase }} {{ request()->routeIs('account.address.*') ? $activeClass : $inactiveClass }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>Shipping Addresses</span>
        </a>

        {{-- Settings --}}
        <a href="{{ route('account.profile.edit') }}"
            class="{{ $itemBase }} {{ request()->routeIs('account.profile.edit') ? $activeClass : $inactiveClass }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>Profile Settings</span>
        </a>

        <div class="px-8 mt-6 mb-2">
            <div class="h-px bg-gray-50 w-full"></div>
        </div>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}" class="mx-3">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-5 py-3 text-sm font-medium rounded-xl text-gray-400 hover:text-red-600 hover:bg-red-50 transition-all duration-200">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V5" />
                </svg>
                <span>Sign Out</span>
            </button>
        </form>

    </nav>
</div>