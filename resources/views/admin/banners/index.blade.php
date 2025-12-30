@extends('admin.layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Banners</h1>
            <p class="text-sm text-gray-500">Manage homepage hero banners</p>
        </div>
        <a href="{{ route('admin.banners.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#D4AF37] text-white font-semibold hover:bg-[#c29c2f]">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"
                class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>New Banner</span>
        </a>
    </div>

    {{-- Filter --}}
    <form method="GET" class="bg-white rounded-2xl p-4 border border-[#D4AF37]/18 mb-4">
        <div class="flex flex-col md:flex-row gap-2">
            <input name="keyword" value="{{ request('keyword') }}" placeholder="Search title / link"
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

            <a href="{{ route('admin.banners.index') }}"
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
                    <th class="p-4 text-left w-[130px]">Banner</th>
                    <th class="p-4 text-left">Title</th>
                    <th class="p-4 text-left">Link</th>
                    <th class="p-4 text-left w-[80px]">Sort</th>
                    <th class="p-4 text-left w-[90px]">Status</th>
                    <th class="p-4 text-right w-[140px]">Action</th>
                </tr>
            </thead>
            <tbody class="text-gray-800">
                @forelse($banners as $b)
                    <tr class="border-t border-gray-100 hover:bg-[#D4AF37]/10 transition">
                        <td class="p-4 align-middle">
                            @if ($b->image_path)
                                <div class="h-16 w-28 rounded-lg overflow-hidden border border-gray-200 bg-white">
                                    <img src="{{ asset('storage/' . $b->image_path) }}" class="w-full h-full object-cover"
                                        alt="Banner">
                                </div>
                            @else
                                <div
                                    class="h-16 w-28 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 16.5V6.75A2.25 2.25 0 015.25 4.5h13.5A2.25 2.25 0 0121 6.75v9.75M3 16.5l3.75-3.75a2.25 2.25 0 013.182 0L15 18m-3-3l1.5-1.5a2.25 2.25 0 013.182 0L21 18M10.125 9.75h.008v.008h-.008V9.75z" />
                                    </svg>
                                </div>
                            @endif
                        </td>
                        <td class="p-4 font-semibold">
                            {{ $b->title ?: '—' }}
                        </td>
                        <td class="p-4 text-gray-500 max-w-xs truncate">
                            {{ $b->link_url ?: '—' }}
                        </td>
                        <td class="p-4">
                            {{ $b->sort_order }}
                        </td>
                        <td class="p-4">
                            <span
                                class="px-2 py-1 text-xs rounded
                            {{ $b->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }}">
                                {{ $b->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <a href="{{ route('admin.banners.edit', $b) }}"
                                class="text-[#8f6a10] font-semibold hover:underline mr-3">
                                Edit
                            </a>

                            <form action="{{ route('admin.banners.destroy', $b) }}" method="POST" class="inline"
                                onsubmit="return confirm('Delete this banner?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 font-semibold hover:underline">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="p-6 text-gray-500" colspan="6">No banners yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t border-gray-100">
            {{ $banners->links() }}
        </div>
    </div>
@endsection
