@extends('admin.layouts.app')

@section('content')
    {{-- Header Section --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-semibold text-gray-900 tracking-tight">User Profile</h1>
            <p class="text-sm text-gray-500 mt-1">Detailed overview of account credentials and saved locations.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200
                       text-sm font-semibold text-gray-600 hover:bg-gray-50 transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>Back</span>
            </a>

            <a href="{{ route('admin.users.edit', $user) }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#D4AF37]/15 border border-[#D4AF37]/30 
                       text-[#8f6a10] font-bold hover:bg-[#D4AF37]/20 transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                </svg>
                <span>Edit Profile</span>
            </a>
        </div>
    </div>

    <div class="space-y-6">
        {{-- Overview Card --}}
        <div class="bg-white border border-[#D4AF37]/18 rounded-2xl p-6 shadow-[0_18px_40px_rgba(0,0,0,0.06)]">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-gray-100 pb-6">
                <div class="flex items-center gap-3">
                    <div
                        class="h-12 w-12 rounded-full bg-[#D4AF37]/10 border border-[#D4AF37]/20 flex items-center justify-center text-[#8f6a10] font-bold text-xl uppercase">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 text-lg leading-tight">{{ $user->name }}</p>
                        @if ($user->is_admin ?? false)
                            <span
                                class="inline-flex mt-1 items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-gray-900 text-white uppercase tracking-wider">
                                Administrator
                            </span>
                        @else
                            <span
                                class="inline-flex mt-1 items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-[#D4AF37]/10 text-[#8f6a10] border border-[#D4AF37]/20 uppercase tracking-wider">
                                Customer Account
                            </span>
                        @endif
                    </div>
                </div>

                <div class="flex items-center">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold border {{ $user->is_active ?? true ? 'border-green-500 bg-green-50 text-green-700' : 'border-gray-300 bg-gray-50 text-gray-500' }}">
                        <span
                            class="w-1.5 h-1.5 rounded-full mr-2 {{ $user->is_active ?? true ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                        {{ $user->is_active ?? true ? 'ACTIVE ACCOUNT' : 'INACTIVE' }}
                    </span>
                </div>
            </div>

            {{-- Info Grid --}}
            <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                <div class="rounded-xl bg-gray-50/80 border border-gray-100 px-4 py-3">
                    <p class="text-xs uppercase font-bold tracking-widest text-gray-400 mb-1">Email Address</p>
                    <p class="text-gray-900 font-semibold truncate">{{ $user->email ?? '—' }}</p>
                </div>

                <div class="rounded-xl bg-gray-50/80 border border-gray-100 px-4 py-3">
                    <p class="text-xs uppercase font-bold tracking-widest text-gray-400 mb-1">Mobile Phone</p>
                    <p class="text-gray-900 font-semibold">{{ $user->phone ?? '—' }}</p>
                </div>

                <div class="rounded-xl bg-gray-50/80 border border-gray-100 px-4 py-3">
                    <p class="text-xs uppercase font-bold tracking-widest text-gray-400 mb-1">Created date</p>
                    <p class="text-gray-900 font-semibold">{{ $user->created_at?->format('d M Y') ?? '—' }}</p>
                </div>

                <div class="rounded-xl bg-gray-50/80 border border-gray-100 px-4 py-3">
                    <p class="text-xs uppercase font-bold tracking-widest text-gray-400 mb-1">Last Updated</p>
                    <p class="text-gray-900 font-semibold">{{ $user->updated_at?->format('d M Y') ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- Bottom Row: Addresses & Activity --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Addresses --}}
            <div class="bg-white border border-[#D4AF37]/18 rounded-2xl p-6 shadow-[0_18px_40px_rgba(0,0,0,0.06)]">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="font-bold text-gray-900 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-4 h-4 text-[#D4AF37]">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        Saved Addresses
                    </h2>
                </div>

                @if ($user->addresses->isEmpty())
                    <div class="py-10 text-center border-2 border-dashed border-gray-100 rounded-2xl">
                        <p class="text-sm text-gray-400 italic">No registered addresses found.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($user->addresses as $address)
                            <div
                                class="relative rounded-2xl border px-4 py-4 transition-all {{ $address->is_default ? 'bg-[#D4AF37]/5 border-[#D4AF37]/40 shadow-sm' : 'bg-white border-gray-100 hover:border-gray-300' }}">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <div class="font-bold text-gray-900">{{ $address->recipient_name }}</div>
                                        <div class="text-xs text-gray-500 font-medium">{{ $address->phone }}</div>
                                    </div>
                                    @if ($address->is_default)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-black bg-[#D4AF37] text-white uppercase tracking-tighter">
                                            Default
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-700 leading-relaxed">
                                    {{ $address->address_line1 }}@if ($address->address_line2)
                                        , {{ $address->address_line2 }}
                                    @endif
                                    <br>
                                    <span class="font-medium text-gray-900">{{ $address->postcode }}
                                        {{ $address->city }}</span>, {{ $address->state }}
                                    <br>
                                    <span
                                        class="text-xs text-gray-400 uppercase tracking-widest font-bold">{{ $address->country }}</span>
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Recent activity --}}
            <div
                class="bg-white border border-[#D4AF37]/18 rounded-2xl p-6 shadow-[0_18px_40px_rgba(0,0,0,0.06)] flex flex-col">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="font-bold text-gray-900 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-4 h-4 text-[#D4AF37]">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                        Shopping Activity
                    </h2>
                    <span class="text-xs font-bold text-gray-300 uppercase tracking-widest">
                        @if ($recentOrders->count())
                            Last {{ $recentOrders->count() }} Orders
                        @else
                            No Orders Yet
                        @endif
                    </span>
                </div>

                @if ($recentOrders->count())
                    {{-- ✅ 有订单时：订单列表 --}}
                    <div class="flex-1 -mx-3">
                        <ul class="divide-y divide-gray-100">
                            @foreach ($recentOrders as $order)
                                <li class="px-3 py-3 flex items-center justify-between gap-4">
                                    <div>
                                        <div class="flex items-center gap-2 text-sm font-semibold text-gray-900">
                                            <span>Order #{{ $order->order_no }}</span>

                                            {{-- status badge --}}
                                            <span
                                                class="text-[10px] px-2 py-0.5 rounded-full
                                    @switch($order->status)
                                        @case('pending') bg-amber-100 text-amber-700 @break
                                        @case('paid') bg-emerald-100 text-emerald-700 @break
                                        @case('processing') bg-blue-100 text-blue-700 @break
                                        @case('shipped') bg-indigo-100 text-indigo-700 @break
                                        @case('completed') bg-emerald-100 text-emerald-700 @break
                                        @case('cancelled') bg-red-100 text-red-600 @break
                                        @default bg-gray-100 text-gray-500
                                    @endswitch
                                ">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </div>

                                        <p class="text-xs text-gray-400 mt-1">
                                            {{ $order->created_at->format('d M Y, H:i') }}
                                            @if ($order->items_count ?? false)
                                                · {{ $order->items_count }} item(s)
                                            @endif
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <div class="text-right">
                                            <div class="text-sm font-bold text-gray-900">
                                                RM {{ number_format($order->total, 2) }}
                                            </div>
                                            @if ($order->payment_method_name ?? null)
                                                <div class="text-[11px] text-gray-400">
                                                    {{ $order->payment_method_name }}
                                                </div>
                                            @endif
                                        </div>

                                        {{-- 管理员查看订单详情 --}}
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                            class="inline-flex items-center px-3 py-1.5 rounded-full border border-gray-200
                                      text-xs font-semibold text-gray-700 hover:border-[#D4AF37]/60 hover:text-[#8f6a10] transition">
                                            View
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    {{-- 🚫 没有订单：用一个简单空状态（你也可以沿用之前那套 Locked 文案） --}}
                    <div
                        class="flex-1 flex flex-col items-center justify-center text-center space-y-3 py-10 border-2 border-dashed border-gray-50 rounded-2xl bg-gray-50/30">
                        <div class="p-3 bg-white rounded-full shadow-sm border border-gray-100 text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-500">No orders found</p>
                            <p class="text-xs text-gray-400 max-w-xs mx-auto mt-1">
                                This customer has not placed any orders yet.
                            </p>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
