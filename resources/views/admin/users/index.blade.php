@extends('admin.layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold">Users</h1>
            <p class="text-sm text-gray-500">Manage registered users & customers</p>
        </div>

        {{-- 如果你之后要做 Create User 就启用 --}}
        {{-- <a href="{{ route('admin.users.create') }}"
            class="px-4 py-2 rounded-xl bg-[#D4AF37]/15 border border-[#D4AF37]/30 text-[#8f6a10] font-semibold">
            + New User
        </a> --}}
    </div>

    {{-- Filter --}}
    <form method="GET" class="bg-white rounded-2xl p-4 border mb-4">
        <div class="flex gap-2">
            <input name="keyword" value="{{ request('keyword') }}" placeholder="Search name / email"
                class="flex-1 rounded-xl border-gray-200">

            <select name="status" class="rounded-xl border-gray-200">
                <option value="">All</option>
                <option value="active" @selected(request('status') === 'active')>Active</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
            </select>

            <button class="px-4 rounded-xl border">Filter</button>
        </div>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    {{-- <th class="px-5 py-3 text-left">ID</th> --}}
                    <th class="px-5 py-3 text-left">Name</th>
                    <th class="px-5 py-3 text-left">Email</th>
                    <th class="px-5 py-3 text-left">Phone</th>
                    <th class="px-5 py-3 text-left">Registered</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-right">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($users as $u)
                    <tr class="border-t hover:bg-[#D4AF37]/10">
                        {{-- <td class="px-5 py-4 align-middle font-medium">
                            {{ $u->id }}
                        </td> --}}

                        <td class="px-5 py-4 align-middle">
                            {{ $u->name }}
                        </td>

                        <td class="px-5 py-4 align-middle whitespace-nowrap">
                            {{ $u->email }}
                        </td>

                        <td class="px-5 py-4 align-middle">{{ $u->phone ?? '-' }}</td>


                        <td class="px-5 py-4 align-middle text-gray-500 whitespace-nowrap">
                            {{ $u->created_at?->format('Y-m-d H:i') }}
                        </td>

                        <td class="px-5 py-4 align-middle">
                            <span class="text-sm {{ $u->is_active ?? true ? 'text-green-700' : 'text-gray-500' }}">
                                {{ $u->is_active ?? true ? 'Active' : 'Inactive' }}
                            </span>
                        </td>

                        <td class="px-5 py-4 align-middle text-right whitespace-nowrap">
                            <a href="{{ route('admin.users.show', $u) }}" class="text-[#8f6a10] font-semibold mr-3">
                                View
                            </a>
                            <a href="{{ route('admin.users.edit', $u) }}"
                                class="text-sm text-gray-600 hover:text-[#8f6a10]">
                                Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-gray-500">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4">
            {{ $users->links() }}
        </div>
    </div>
@endsection
