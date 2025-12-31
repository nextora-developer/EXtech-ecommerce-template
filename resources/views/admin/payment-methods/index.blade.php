@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Payment Methods</h1>
            <p class="text-sm text-gray-500 mt-1">Manage bank transfer accounts & payment gateways</p>
        </div>

        <a href="{{ route('admin.payment-methods.create') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gray-900 text-white text-sm font-semibold
                  hover:bg-gray-800 transition-all shadow-md hover:shadow-lg active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>New Payment Method</span>
        </a>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm mb-6 transition-all">
        <form method="GET" class="flex flex-col md:flex-row gap-3">

            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input name="keyword" value="{{ request('keyword') }}" placeholder="Search name, code or bank…"
                    class="block w-full pl-10 pr-3 py-2.5 bg-gray-50 border-transparent rounded-xl text-sm
                              focus:bg-white focus:ring-2 focus:ring-[#D4AF37]/20 focus:border-[#D4AF37] transition-all">
            </div>

            <select name="status"
                class="min-w-[140px] py-2.5 bg-gray-50 border-transparent rounded-xl text-sm
                       focus:bg-white focus:ring-2 focus:ring-[#D4AF37]/20 focus:border-[#D4AF37] transition-all">
                <option value="">All Status</option>
                <option value="active" @selected(request('status') === 'active')>Active</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
            </select>

            <button
                class="px-6 py-2.5 rounded-xl bg-[#D4AF37]/10 text-[#8f6a10]
                       border border-[#D4AF37]/20 hover:bg-[#D4AF37] hover:text-white
                       transition-all font-bold text-sm">
                Filter
            </button>

            <a href="{{ route('admin.payment-methods.index') }}"
                class="px-4 py-2.5 rounded-xl border border-gray-100 text-gray-500 hover:bg-gray-50
                      transition-all text-sm flex items-center justify-center">
                Reset
            </a>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-50">
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-[300px]">
                            Method
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-[200px]">
                            Code
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-[200px]">
                            Bank
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-[150px]">
                            Default
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-[150px]">
                            Status
                        </th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50">
                    @forelse($paymentMethods as $m)
                        <tr class="group hover:bg-[#D4AF37]/5 transition-colors">

                            {{-- Name --}}
                            <td class="px-6 py-4 font-bold text-gray-900 group-hover:text-[#8f6a10]">
                                {{ $m->name }}
                            </td>

                            {{-- Code --}}
                            <td class="px-6 py-4 text-gray-700">
                                {{ $m->code }}
                            </td>

                            {{-- Bank Info --}}
                            <td class="px-6 py-4">
                                @if ($m->bank_name)
                                    <div class="font-medium text-gray-900">{{ $m->bank_name }}</div>
                                    <div class="text-xs text-gray-400">
                                        {{ $m->bank_account_name }} — {{ $m->bank_account_number }}
                                    </div>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>

                            {{-- Default --}}
                            <td class="px-6 py-4">
                                @if ($m->is_default)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full
                                                 text-[11px] font-bold bg-amber-50 text-amber-700
                                                 border border-amber-200 uppercase tracking-wider">
                                        Default
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full
                                                 text-[11px] font-bold bg-gray-50 text-gray-400
                                                 border border-gray-100 uppercase tracking-wider">
                                        —
                                    </span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4">
                                @if ($m->is_active)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full
                                                 text-[11px] font-bold bg-emerald-50 text-emerald-600
                                                 border border-emerald-100 uppercase tracking-wider">
                                        Active
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full
                                                 text-[11px] font-bold bg-gray-50 text-gray-400
                                                 border border-gray-100 uppercase tracking-wider">
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            {{-- Action --}}
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.payment-methods.edit', $m) }}"
                                        class="p-2 rounded-lg text-gray-400 hover:text-[#8f6a10]
                  hover:bg-[#D4AF37]/10 transition-all"
                                        title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414
                             a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.payment-methods.destroy', $m) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Delete this payment method?')">
                                        @csrf @method('DELETE')
                                        <button class="p-2 rounded-lg text-red-500 hover:bg-red-50 transition-all"
                                            title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7
                                 m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center text-gray-400">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-200" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    <p class="font-medium">No payment methods yet</p>
                                    <p class="text-xs mt-1 text-gray-300">Try adjusting your filters or create one</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
            {{ $paymentMethods->links() }}
        </div>
    </div>
@endsection
