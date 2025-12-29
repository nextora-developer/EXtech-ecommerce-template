@extends('admin.layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold">Edit user</h1>
            <p class="text-sm text-gray-500">Update user profile & status</p>
        </div>

        <a href="{{ route('admin.users.show', $user) }}" class="text-sm text-gray-600 hover:text-[#8f6a10]">
            ← Back to details
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border p-5 shadow-sm">

        {{-- 📝 User form 只包住输入字段 + Active --}}
        <form id="user-form" method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            {{-- Row 1: Basic Info --}}
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="text-xs text-gray-500">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                </div>

                <div>
                    <label class="text-xs text-gray-500">IC Number</label>
                    <input type="text" name="ic_number" value="{{ old('ic_number', $user->ic_number) }}"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30"
                        placeholder="Optional">
                </div>

                <div>
                    <label class="text-xs text-gray-500">Password (optional)</label>
                    <input type="password" name="password"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30"
                        placeholder="Leave blank">
                </div>
            </div>

            {{-- Row 2 --}}
            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="text-xs text-gray-500">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30"
                        placeholder="e.g. you@example.com">
                </div>

                <div>
                    <label class="text-xs text-gray-500">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30"
                        placeholder="e.g. 012-3456789">
                </div>
            </div>

        </form>

        {{-- 🏠 Addresses（不在 user form 里面，所以可以有自己的 delete form） --}}
        <div class="mt-6 border rounded-2xl p-4 bg-gray-50">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-base font-semibold text-gray-700">Addresses</h2>

                <a href="{{ route('admin.addresses.create', $user) }}"
                    class="text-sm px-3 py-2 rounded-lg
          bg-[#D4AF37] text-white font-semibold
          hover:bg-[#C2982F] transition">
                    + Add address
                </a>

            </div>

            @foreach ($user->addresses as $address)
                <div class="flex justify-between border rounded-xl p-4 bg-white mb-3">
                    <div>
                        <p class="font-medium flex items-center gap-2">
                            {{ $address->recipient_name }}

                            @if ($address->is_default)
                                <span
                                    class="px-2 py-0.5 text-xs rounded-lg
                     bg-[#FDF3D7] text-[#8f6a10]
                     border border-[#E6C97A]">
                                    Default
                                </span>
                            @endif
                        </p>

                        <p class="text-sm text-gray-600">
                            {{ $address->address_line1 }}
                            @if ($address->address_line2)
                                , {{ $address->address_line2 }}
                            @endif
                            , {{ $address->postcode }} {{ $address->city }},
                            {{ $address->state }}, {{ $address->country }}
                        </p>
                    </div>

                    <div class="text-sm text-right space-y-1">
                        <a href="{{ route('admin.addresses.edit', $address) }}"
                            class="text-gray-600 hover:text-[#8f6a10]">Edit</a>

                        {{-- ✅ 这个是 address 自己的 DELETE form，不在 user form 里面 --}}
                        <form action="{{ route('admin.addresses.destroy', $address) }}" method="POST"
                            onsubmit="return confirm('Delete this address?');">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:text-red-800">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ⬇️ 底部按钮：在同一个 card 里，但在 form 外，用 form="user-form" 提交 --}}
        <div class="mt-6 flex justify-end gap-3">
            {{-- Active 也放在这个 form 里面 --}}
            <div class="flex justify-end">
                <label class="flex items-center gap-2 cursor-pointer">
                    <span class="text-sm font-medium text-gray-700">Active</span>

                    <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                        @checked(old('is_active', $user->is_active ?? true))>

                    <div
                        class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#D4AF37]
                    after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                    after:bg-white after:h-5 after:w-5 after:rounded-full
                    after:transition-all peer-checked:after:translate-x-full">
                    </div>
                </label>
            </div>

            <button type="submit" form="user-form"
                class="px-5 py-2 rounded-xl bg-[#D4AF37] text-white text-sm font-semibold hover:bg-[#c29c2f]">
                Save
            </button>

            <a href="{{ route('admin.users.show', $user) }}"
                class="px-5 py-2 rounded-xl border border-gray-300 text-sm hover:bg-gray-50">
                Cancel
            </a>
        </div>
    </div>
@endsection
