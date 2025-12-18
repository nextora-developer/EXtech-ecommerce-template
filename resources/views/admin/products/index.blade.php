@extends('admin.layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold">Products</h1>
            <p class="text-sm text-gray-500">Manage products and pricing</p>
        </div>
        <a href="{{ route('admin.products.create') }}"
            class="px-4 py-2 rounded-xl bg-[#D4AF37]/15 border border-[#D4AF37]/30 text-[#8f6a10] font-semibold">
            + New Product
        </a>
    </div>

    <form method="GET" class="bg-white rounded-2xl p-4 border mb-4">
        <div class="flex gap-2">
            <input name="keyword" value="{{ request('keyword') }}" placeholder="Search name / slug"
                class="flex-1 rounded-xl border-gray-200">
            <select name="status" class="rounded-xl border-gray-200">
                <option value="">All</option>
                <option value="active" @selected(request('status') === 'active')>Active</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
            </select>
            <button class="px-4 rounded-xl border">Filter</button>
        </div>
    </form>

    <div class="bg-white rounded-2xl border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-5 py-3 text-left">Image</th>
                    <th class="px-5 py-3 text-left">Name</th>
                    <th class="px-5 py-3 text-left">Price</th>
                    <th class="px-5 py-3 text-left">Stock</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-right">Action</th> {{-- ✅ add this --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $p)
                    <tr class="border-t hover:bg-[#D4AF37]/10">
                        <td class="px-5 py-4 align-middle">
                            @if ($p->image)
                                <img src="{{ asset('storage/' . $p->image) }}" class="h-10 w-10 rounded object-cover">
                            @else
                                <div class="h-10 w-10 bg-gray-100 rounded"></div>
                            @endif
                        </td>
                        <td class="px-5 py-4 align-middle font-medium">{{ $p->name }}</td>
                        <td class="px-5 py-4 align-middle whitespace-nowrap">
                            @if ($p->has_variants && $p->variants->count())
                                @php
                                    // 拿有填 price 的 variants
                                    $variantPrices = $p->variants->whereNotNull('price');
                                    $min = $variantPrices->min('price');
                                    $max = $variantPrices->max('price');
                                @endphp

                                @if ($min === null)
                                    {{-- 有 variants 但是都没有填价钱 --}}
                                    RM 0.00
                                @elseif ($min == $max)
                                    {{-- 所有 variants 同一个价钱 --}}
                                    RM {{ number_format($min, 2) }}
                                @else
                                    {{-- 显示价钱范围 --}}
                                    RM {{ number_format($min, 2) }} – {{ number_format($max, 2) }}
                                @endif
                            @else
                                {{-- 没有 variants，用 product 本身的 price --}}
                                RM {{ number_format($p->price ?? 0, 2) }}
                            @endif
                        </td>
                        <td class="px-5 py-4 align-middle whitespace-nowrap">{{ $p->stock }}</td>
                        <td class="px-5 py-4 align-middle">
                            <form method="POST" action="{{ route('admin.products.toggle', $p) }}">
                                @csrf
                                @method('PATCH')

                                <button type="submit" class="inline-flex items-center gap-3">
                                    <span class="text-sm {{ $p->is_active ? 'text-green-700' : 'text-gray-600' }}">
                                        {{ $p->is_active ? 'Active' : 'Inactive' }}
                                    </span>

                                    <span
                                        class="relative inline-flex h-6 w-11 items-center rounded-full transition
                                            {{ $p->is_active ? 'bg-[#D4AF37]' : 'bg-gray-200' }}">
                                        <span
                                            class="inline-block h-5 w-5 transform rounded-full bg-white transition
                                            {{ $p->is_active ? 'translate-x-5' : 'translate-x-1' }}"></span>
                                    </span>
                                </button>
                            </form>
                        </td>

                        <td class="px-5 py-4 align-middle text-right whitespace-nowrap">
                            <a href="{{ route('admin.products.edit', $p) }}" class="text-[#8f6a10] font-semibold">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">{{ $products->links() }}</div>
    </div>
@endsection
