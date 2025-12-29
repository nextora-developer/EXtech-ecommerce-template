@extends('admin.layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Shipping</h1>
            <p class="text-sm text-gray-500">Manage shipping fees for different zones</p>
        </div>
        {{-- 如果暂时不需要新增，可以先隐藏这个按钮，或者以后要支持更多 zone 再打开 --}}
        {{-- <a href="{{ route('admin.shipping.create') }}"
            class="px-4 py-2 rounded-xl bg-[#D4AF37]/15 border border-[#D4AF37]/30 text-[#8f6a10] font-semibold hover:bg-[#D4AF37]/20 transition">
            + New Shipping Rate
        </a> --}}
    </div>

    {{-- Filter（简单一点，先不做 filter，如果以后 zone 多了再加） --}}

    <div
        class="bg-white rounded-2xl border border-[#D4AF37]/18 overflow-hidden
            shadow-[0_18px_40px_rgba(0,0,0,0.06)]">

        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="p-4 text-left w-[220px]">Name</th>
                    <th class="p-4 text-left w-[160px]">Code</th>
                    <th class="p-4 text-left w-[140px]">Rate (RM)</th>
                    <th class="p-4 text-left">Last Updated</th>
                    <th class="p-4 text-right w-[120px]">Action</th>
                </tr>
            </thead>
            <tbody class="text-gray-800">
                @forelse($rates as $rate)
                    <tr class="border-t border-gray-100 hover:bg-[#D4AF37]/10 transition">
                        <td class="p-4 font-semibold">
                            {{ $rate->name ?: '—' }}
                        </td>

                        <td class="p-4 text-gray-500">
                            <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-700">
                                {{ $rate->code }}
                            </span>
                        </td>

                        <td class="p-4">
                            <span class="font-semibold">
                                {{ number_format($rate->rate, 2) }}
                            </span>
                        </td>

                        <td class="p-4 text-gray-500">
                            {{ $rate->updated_at ? $rate->updated_at->format('Y-m-d H:i') : '—' }}
                        </td>

                        <td class="p-4 text-right">
                            <a href="{{ route('admin.shipping.edit', $rate) }}"
                                class="text-[#8f6a10] font-semibold hover:underline">
                                Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="p-6 text-gray-500" colspan="5">
                            No shipping rates yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- 如果你用 pagination 再打开这块 --}}
        {{-- <div class="p-4 border-t border-gray-100">
            {{ $rates->links() }}
        </div> --}}
    </div>
@endsection
