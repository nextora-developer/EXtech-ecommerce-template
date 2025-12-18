@extends('admin.layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Categories</h1>
            <p class="text-sm text-gray-500">Manage product categories</p>
        </div>
        <a href="{{ route('admin.categories.create') }}"
            class="px-4 py-2 rounded-xl bg-[#D4AF37]/15 border border-[#D4AF37]/30 text-[#8f6a10] font-semibold hover:bg-[#D4AF37]/20 transition">
            + New Category
        </a>
    </div>

    <form method="GET" class="bg-white rounded-2xl p-4 border border-[#D4AF37]/18 mb-4">
        <div class="flex flex-col md:flex-row gap-2">
            <input name="keyword" value="{{ request('keyword') }}" placeholder="Search name / slug"
                class="flex-1 rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">

            <select name="status" class="rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                <option value="">All</option>
                <option value="active" @selected(request('status') === 'active')>Active</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
            </select>

            <button class="px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 transition">
                Filter
            </button>

            <a href="{{ route('admin.categories.index') }}"
                class="px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 transition">
                Reset
            </a>
        </div>
    </form>

    <div
        class="bg-white rounded-2xl border border-[#D4AF37]/18 overflow-hidden
            shadow-[0_18px_40px_rgba(0,0,0,0.06)]">
        <table class="w-full text-sm">
            <thead class="bg-gray-50/60 text-gray-600">
                <tr>
                    <th class="p-4 text-left font-medium w-[90px]">Icon</th>
                    <th class="p-4 text-left font-medium">Name</th>
                    <th class="p-4 text-left font-medium">Slug</th>
                    <th class="p-4 text-left font-medium">Sort</th>
                    <th class="p-4 text-left font-medium">Status</th>
                    <th class="p-4 text-right font-medium">Action</th>
                </tr>
            </thead>
            <tbody class="text-gray-800">
                @forelse($categories as $c)
                    <tr class="border-t border-gray-100 hover:bg-[#D4AF37]/10 transition">
                        <td class="p-4 align-middle">
                            @if ($c->icon)
                                <img src="{{ asset('storage/' . $c->icon) }}"
                                    class="h-10 w-10 rounded-lg object-cover border border-gray-200 bg-white"
                                    alt="Icon">
                            @else
                                <div
                                    class="h-10 w-10 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-center">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 16.5V6.75A2.25 2.25 0 015.25 4.5h13.5A2.25 2.25 0 0121 6.75v9.75" />
                                    </svg>
                                </div>
                            @endif
                        </td>
                        <td class="p-4 font-semibold">{{ $c->name }}</td>
                        <td class="p-4 text-gray-500">{{ $c->slug }}</td>
                        <td class="p-4">{{ $c->sort_order }}</td>
                        <td class="p-4">
                            <span
                                class="px-2 py-1 text-xs rounded
                            {{ $c->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }}">
                                {{ $c->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <a href="{{ route('admin.categories.edit', $c) }}"
                                class="text-[#8f6a10] font-semibold hover:underline mr-3">
                                Edit
                            </a>

                            <form action="{{ route('admin.categories.destroy', $c) }}" method="POST" class="inline"
                                onsubmit="return confirm('Delete this category?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 font-semibold hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="p-6 text-gray-500" colspan="5">No categories yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t border-gray-100">
            {{ $categories->links() }}
        </div>
    </div>
@endsection
