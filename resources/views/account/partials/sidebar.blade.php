{{-- resources/views/account/partials/sidebar.blade.php --}}

@php
    // 确保有 user，可以从外面传，也可以用当前登录用户
    $user = $user ?? auth()->user();

    $itemBase = 'flex items-center gap-2 px-5 py-2.5 text-sm rounded-xl mx-3 mt-3';
@endphp

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm flex flex-col">

    {{-- 顶部用户信息 --}}
    <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3">
        <div
            class="h-10 w-10 rounded-full bg-gradient-to-br from-[#D4AF37] to-[#8f6a10] flex items-center justify-center text-sm font-semibold text-white">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div class="leading-tight">
            <div class="text-sm font-semibold text-gray-900">
                Hi, {{ $user->name }}
            </div>
            <div class="text-xs text-gray-500">
                {{ $user->email }}
            </div>
        </div>
    </div>

    {{-- 菜单 --}}
    <nav class="flex-1 pb-4">
        {{-- Account --}}
        <a href="{{ route('account.index') }}"
            class="{{ $itemBase }}
           {{ request()->routeIs('account.index') ? 'bg-[#FFF9E6] text-[#8f6a10]' : 'text-gray-700 hover:bg-gray-50 hover:text-[#0A0A0C]' }}">
            <span class="text-base">≡</span>
            <span>Account</span>
        </a>

        {{-- Orders --}}
        <a href="{{ route('account.orders') }}"
            class="{{ $itemBase }}
           {{ request()->routeIs('account.orders') ? 'bg-[#FFF9E6] text-[#8f6a10]' : 'text-gray-700 hover:bg-gray-50 hover:text-[#0A0A0C]' }}">
            <span class="text-base">🛒</span>
            <span>Orders</span>
        </a>

        {{-- Favorites（先占位，将来再做 page） --}}
        <a href="#" class="{{ $itemBase }} text-gray-700 hover:bg-gray-50 hover:text-[#0A0A0C]">
            <span class="text-base">♡</span>
            <span>Favorites</span>
        </a>

        {{-- Addresses --}}
        <a href="#" class="{{ $itemBase }} text-gray-700 hover:bg-gray-50 hover:text-[#0A0A0C]">
            <span class="text-base">📍</span>
            <span>Addresses</span>
        </a>

        {{-- Edit Profile --}}
        <a href="#" class="{{ $itemBase }} text-gray-700 hover:bg-gray-50 hover:text-[#0A0A0C]">
            <span class="text-base">✏️</span>
            <span>Edit Profile</span>
        </a>

        <hr class="my-4 mx-4 border-gray-200">

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}" class="mx-3 mb-4">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-2 px-5 py-2.5 text-sm rounded-xl text-red-500 hover:bg-red-50">
                <span class="text-base">↩</span>
                <span>Logout</span>
            </button>
        </form>
    </nav>
</div>
