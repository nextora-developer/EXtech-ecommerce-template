@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Products</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your inventory, pricing, and variants</p>
        </div>

        <a href="{{ route('admin.products.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gray-900 text-white text-sm font-semibold
                  hover:bg-gray-800 transition-all shadow-md active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            <span>Add Product</span>
        </a>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm mb-6 transition-all">
        <form method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input name="keyword" value="{{ request('keyword') }}"
                       placeholder="Search product name…"
                       class="block w-full pl-10 pr-3 py-2.5 bg-gray-50 border-transparent rounded-xl text-sm
                              focus:bg-white focus:ring-2 focus:ring-[#D4AF37]/20 focus:border-[#D4AF37] transition-all">
            </div>

            <div class="flex gap-2">
                <select name="status"
                        class="py-2.5 bg-gray-50 border-transparent rounded-xl text-sm
                               focus:bg-white focus:ring-2 focus:ring-[#D4AF37]/20 focus:border-[#D4AF37] transition-all">
                    <option value="">All Status</option>
                    <option value="active"   @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                </select>

                <button
                    class="px-6 py-2.5 rounded-xl bg-[#D4AF37]/10 text-[#8f6a10]
                           border border-[#D4AF37]/20 hover:bg-[#D4AF37] hover:text-white
                           transition-all font-bold text-sm">
                    Filter
                </button>

                <a href="{{ route('admin.products.index') }}"
                   class="px-4 py-2.5 rounded-xl border border-gray-100 text-gray-500 hover:bg-gray-50
                          transition-all text-sm flex items-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-50">
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Price Range</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50">
                    @foreach ($products as $p)
                        <tr class="group hover:bg-[#D4AF37]/5 transition-colors">
                            {{-- Product + thumbnail --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="h-14 w-14 rounded-xl border-2 border-white shadow-sm bg-gray-100 overflow-hidden">
                                        @if ($p->image)
                                            <img src="{{ asset('storage/'.$p->image) }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-300" fill="none" viewBox="0 0 24 24"
                                                     stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <div>
                                        <div class="font-bold text-gray-900 group-hover:text-[#8f6a10] transition-colors line-clamp-1">
                                            {{ $p->name }}
                                        </div>
                                        <div class="text-[11px] text-gray-400 font-mono mt-0.5">
                                            {{ $p->slug }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Price --}}
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900">
                                    @if ($p->has_variants && $p->variants->count())
                                        @php
                                            $prices = $p->variants->whereNotNull('price');
                                            $min = $prices->min('price');
                                            $max = $prices->max('price');
                                        @endphp

                                        @if ($min === null)
                                            RM 0.00
                                        @elseif ($min == $max)
                                            RM {{ number_format($min,2) }}
                                        @else
                                            <span class="text-[11px] text-gray-400 block font-normal uppercase">From</span>
                                            RM {{ number_format($min,2) }} – {{ number_format($max,2) }}
                                        @endif
                                    @else
                                        RM {{ number_format($p->price ?? 0,2) }}
                                    @endif
                                </div>
                            </td>

                            {{-- Stock --}}
                            <td class="px-6 py-4">
                                @if ($p->stock <= 5)
                                    <div class="flex flex-col gap-1">
                                        <span class="text-sm font-bold {{ $p->stock == 0 ? 'text-red-600' : 'text-orange-600' }}">
                                            {{ $p->stock }}
                                            <span class="text-[10px] uppercase font-medium">units left</span>
                                        </span>
                                        <div class="w-20 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                            <div class="h-full {{ $p->stock == 0 ? 'bg-red-500' : 'bg-orange-500' }}"
                                                 style="width: {{ max(5, ($p->stock / 10) * 100) }}%"></div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-sm font-bold text-gray-700">{{ $p->stock }}</span>
                                    <span class="text-[10px] text-gray-400 uppercase ml-1">In Stock</span>
                                @endif
                            </td>

                            {{-- Toggle --}}
                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('admin.products.toggle',$p) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="flex items-center group/toggle">
                                        <div class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors
                                                    {{ $p->is_active ? 'bg-emerald-500' : 'bg-gray-200' }}">
                                            <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition-transform
                                                         {{ $p->is_active ? 'translate-x-5' : 'translate-x-1' }}"></span>
                                        </div>
                                        <span class="ml-2 text-xs font-bold uppercase tracking-wider
                                                     {{ $p->is_active ? 'text-emerald-600' : 'text-gray-400 group-hover/toggle:text-gray-600' }}">
                                            {{ $p->is_active ? 'Active' : 'Hidden' }}
                                        </span>
                                    </button>
                                </form>
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- View --}}
                                    {{-- <a href="{{ route('admin.products.show',$p) }}"
                                       class="p-2 rounded-lg text-gray-400 hover:text-[#8f6a10]
                                              hover:bg-[#D4AF37]/10 transition-all"
                                       title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7
                                                     -1.274 4.057-5.065 7-9.542 7
                                                     -4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a> --}}

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.products.edit',$p) }}"
                                       class="p-2 rounded-lg text-gray-400 hover:text-[#8f6a10]
                                              hover:bg-[#D4AF37]/10 transition-all"
                                       title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414
                                                     a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
            {{ $products->links() }}
        </div>
    </div>
@endsection
