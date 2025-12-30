@extends('admin.layouts.app')

@section('content')
    {{-- 顶部标题 --}}
    <div class="flex items-center justify-between mb-5">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">User details</h1>
            <p class="text-sm text-gray-500">View user profile & activity</p>
        </div>

        {{-- ⭐ 把按钮包成一组 --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200
          hover:bg-gray-50 transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                    stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                </svg>
                <span>Back</span>
            </a>

            <a href="{{ route('admin.users.edit', $user) }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#D4AF37]/15 border border-[#D4AF37]/30 text-[#8f6a10] font-semibold hover:bg-[#D4AF37]/20 transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                </svg>

                <span>Edit User</span>
            </a>
        </div>
    </div>

    <div class="space-y-5">

        {{-- Overview card --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
            {{-- Top row: Name + Role (左)  &  Account status (右) --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 w-full">

                {{-- 左 --}}
                <div class="flex items-center gap-2">
                    <p class="font-semibold text-gray-900 text-base">
                        {{ $user->name }}
                    </p>

                    @if ($user->is_admin ?? false)
                        <span
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-gray-900 text-white">
                            Admin
                        </span>
                    @else
                        <span
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-[#D4AF37]/10 text-[#8f6a10]">
                            Customer
                        </span>
                    @endif
                </div>

                {{-- 右：Account Status --}}
                <div class="flex justify-end w-full md:w-auto">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
            {{ $user->is_active ?? true ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }}">
                        <span
                            class="inline-block w-2 h-2 rounded-full mr-1.5
                {{ $user->is_active ?? true ? 'bg-green-500' : 'bg-gray-500' }}">
                        </span>
                        {{ $user->is_active ?? true ? 'Active' : 'Inactive' }}
                    </span>
                </div>

            </div>


            {{-- Bottom row: 4 items in ONE row on desktop --}}
            <div class="mt-5 grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                <div class="rounded-xl bg-gray-50 px-3 py-2.5">
                    <p class="text-[11px] uppercase tracking-wide text-gray-500 mb-0.5">Email</p>
                    <p class="text-gray-900 font-medium">
                        {{ $user->email ?? '—' }}
                    </p>
                </div>

                <div class="rounded-xl bg-gray-50 px-3 py-2.5">
                    <p class="text-[11px] uppercase tracking-wide text-gray-500 mb-0.5">Phone</p>
                    <p class="text-gray-900 font-medium">
                        {{ $user->phone ?? '—' }}
                    </p>
                </div>

                <div class="rounded-xl bg-gray-50 px-3 py-2.5">
                    <p class="text-[11px] uppercase tracking-wide text-gray-500 mb-0.5">Registered</p>
                    <p class="text-gray-900 font-medium">
                        {{ $user->created_at?->format('Y-m-d H:i') ?? '—' }}
                    </p>
                </div>

                <div class="rounded-xl bg-gray-50 px-3 py-2.5">
                    <p class="text-[11px] uppercase tracking-wide text-gray-500 mb-0.5">Updated</p>
                    <p class="text-gray-900 font-medium">
                        {{ $user->updated_at?->format('Y-m-d H:i') ?? '—' }}
                    </p>
                </div>
            </div>
        </div>



        {{-- 第二行：Addresses + Recent orders --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            {{-- Addresses --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-semibold text-gray-800">Addresses</h2>
                    {{-- 之后你要可以在这里加「Add address」按钮 --}}
                    {{-- <button class="text-xs text-[#8f6a10] font-semibold">+ Add address</button> --}}
                </div>

                @if ($user->addresses->isEmpty())
                    <p class="text-sm text-gray-500">
                        No addresses yet.
                    </p>
                @else
                    <div class="space-y-3 text-sm">
                        @foreach ($user->addresses as $address)
                            <div
                                class="rounded-xl border px-3 py-3 {{ $address->is_default ? 'bg-[#D4AF37]/4 border-[#D4AF37]/40' : 'bg-gray-50/50' }}">
                                <div class="flex items-center justify-between mb-1.5">
                                    <div class="font-medium text-gray-900">
                                        {{ $address->recipient_name }}
                                        <span class="text-xs text-gray-500">({{ $address->phone }})</span>
                                    </div>
                                    @if ($address->is_default)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-[#D4AF37]/15 text-[#8f6a10]">
                                            Default
                                        </span>
                                    @endif
                                </div>
                                <p class="text-gray-700">
                                    {{ $address->address_line1 }}@if ($address->address_line2)
                                        , {{ $address->address_line2 }}
                                    @endif
                                    <br>
                                    {{ $address->postcode }} {{ $address->city }}, {{ $address->state }},
                                    {{ $address->country }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Recent orders --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-semibold text-gray-800">Recent orders</h2>
                    <span class="text-xs text-gray-400">Coming soon</span>
                </div>

                <p class="text-sm text-gray-500">
                    Once orders are linked to this user, you can show a quick summary here
                    (last few orders, total spent, etc.).
                </p>
            </div>
        </div>
    </div>
@endsection
