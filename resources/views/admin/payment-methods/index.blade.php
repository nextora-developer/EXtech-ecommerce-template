@extends('admin.layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Payment Methods</h1>
            <p class="text-sm text-gray-500">Manage bank transfer accounts & payment gateways</p>
        </div>

        <a href="{{ route('admin.payment-methods.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#D4AF37] text-white font-semibold hover:bg-[#c29c2f]">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"
                class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>New Payment Method</span>
        </a>
    </div>

    {{-- Filter --}}
    <form method="GET" class="bg-white rounded-2xl p-4 border border-[#D4AF37]/18 mb-4">
        <div class="flex flex-col md:flex-row gap-2">

            <input name="keyword" value="{{ request('keyword') }}" placeholder="Search name / code / bank"
                class="flex-1 rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">

            <select name="status" class="rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                <option value="">All</option>
                <option value="active" @selected(request('status') === 'active')>Active</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
            </select>

            <button class="px-4 py-2 rounded-xl bg-[#D4AF37]/15 text-[#8f6a10] border border-[#D4AF37]/30
                           hover:bg-[#D4AF37]/20 transition font-semibold">
                Filter
            </button>

            <a href="{{ route('admin.payment-methods.index') }}"
                class="px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 transition">
                Reset
            </a>
        </div>
    </form>

    {{-- Table --}}
    <div
        class="bg-white rounded-2xl border border-[#D4AF37]/18 overflow-hidden
        shadow-[0_18px_40px_rgba(0,0,0,0.06)]">

        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="p-4 text-left w-[160px]">Method</th>
                    <th class="p-4 text-left w-[160px]">Code</th>
                    <th class="p-4 text-left w-[190px]">Bank</th>
                    <th class="p-4 text-left w-[110px]">Default</th>
                    <th class="p-4 text-left w-[90px]">Status</th>
                    <th class="p-4 text-right w-[140px]">Action</th>
                </tr>
            </thead>

            <tbody class="text-gray-800">

                @forelse($paymentMethods as $m)
                    <tr class="border-t border-gray-100 hover:bg-[#D4AF37]/10 transition">

                        {{-- Name --}}
                        <td class="p-4 font-semibold">
                            {{ $m->name }}
                        </td>

                        {{-- Code --}}
                        <td class="p-4 text-gray-500">
                            {{ $m->code }}
                        </td>

                        {{-- Bank Info (only for bank transfer) --}}
                        <td class="p-4">
                            @if ($m->bank_name)
                                <div class="font-medium">{{ $m->bank_name }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ $m->bank_account_name }} — {{ $m->bank_account_number }}
                                </div>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>

                        {{-- Default --}}
                        <td class="p-4">
                            @if ($m->is_default)
                                <span class="px-2 py-1 text-xs rounded bg-[#D4AF37]/20 text-[#8f6a10] font-semibold">
                                    Default
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs rounded bg-gray-200 text-gray-700">
                                    —
                                </span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="p-4">
                            <span
                                class="px-2 py-1 text-xs rounded
                                    {{ $m->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }}">
                                {{ $m->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>

                        {{-- Action --}}
                        <td class="p-4 text-right">
                            <a href="{{ route('admin.payment-methods.edit', $m) }}"
                                class="text-[#8f6a10] font-semibold hover:underline mr-3">
                                Edit
                            </a>

                            <form action="{{ route('admin.payment-methods.destroy', $m) }}" method="POST" class="inline"
                                onsubmit="return confirm('Delete this payment method?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 font-semibold hover:underline">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td class="p-6 text-gray-500" colspan="6">
                            No payment methods yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t border-gray-100">
            {{ $paymentMethods->links() }}
        </div>
    </div>
@endsection
