@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Shipping</h1>
            <p class="text-sm text-gray-500 mt-1">Manage shipping fees for different zones</p>
        </div>

        {{-- 以后要支持更多 zone 时可以开放新增按钮 --}}
        {{-- <a href="{{ route('admin.shipping.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gray-900 text-white text-sm font-semibold
                  hover:bg-gray-800 transition-all shadow-md hover:shadow-lg active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            <span>New Shipping Rate</span>
        </a> --}}
    </div>

    {{-- 暂时不做 filter，zone 多了可以再加 --}}

    <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-50">
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-[220px]">
                            Name
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-[160px]">
                            Code
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-[140px]">
                            Rate (RM)
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Last Updated
                        </th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50 text-gray-800">
                    @forelse($rates as $rate)
                        <tr class="group hover:bg-[#D4AF37]/5 transition-colors">
                            {{-- Name --}}
                            <td class="px-6 py-4 font-bold text-gray-900 group-hover:text-[#8f6a10]">
                                {{ $rate->name ?: '—' }}
                            </td>

                            {{-- Code --}}
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full
                                           text-[11px] font-bold bg-gray-50 text-gray-500
                                           border border-gray-100 uppercase tracking-wider">
                                    {{ $rate->code }}
                                </span>
                            </td>

                            {{-- Rate --}}
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-900">
                                    {{ number_format($rate->rate, 2) }}
                                </span>
                            </td>

                            {{-- Last Updated --}}
                            <td class="px-6 py-4 text-gray-500">
                                {{ $rate->updated_at ? $rate->updated_at->format('d M Y H:i A') : '—' }}
                            </td>

                            {{-- Action --}}
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.shipping.edit', $rate) }}"
                                        class="p-2 rounded-lg text-gray-400 hover:text-[#8f6a10]
                  hover:bg-[#D4AF37]/10 transition-all"
                                        title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414
                             a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center text-gray-400">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-200" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    <p class="font-medium">No shipping rates yet</p>
                                    <p class="text-xs mt-1 text-gray-300">You can start by adding your first zone rate</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- 以后有 pagination 再打开 --}}
        {{-- <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
            {{ $rates->links() }}
        </div> --}}
    </div>
@endsection
