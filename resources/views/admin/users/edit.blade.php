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

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="bg-white rounded-2xl border p-5">
        @csrf
        @method('PUT')

        {{-- Row 1: Name / Email / Phone / Password --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="text-xs text-gray-500">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                    class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
            </div>

            <div>
                <label class="text-xs text-gray-500">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                    class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
            </div>

            <div>
                <label class="text-xs text-gray-500">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                    class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
            </div>

            <div>
                <label class="text-xs text-gray-500">Password (optional)</label>
                <input type="password" name="password"
                    class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30"
                    placeholder="Leave blank">
            </div>
        </div>

        {{-- Row 2: 左 Addresses ｜ 右 Active --}}
        <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-5">

            {{-- LEFT: Addresses，占 2 栏 --}}
            <div class="md:col-span-3">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-semibold text-gray-700">Addresses</h2>

                    <a href="{{ route('admin.addresses.create', $user) }}"
                        class="text-xs px-3 py-1 rounded-lg border border-gray-300 bg-white hover:bg-gray-50">
                        + Add address
                    </a>
                </div>

                @if ($user->addresses->isEmpty())
                    <p class="text-sm text-gray-500">No addresses yet.</p>
                @else
                    <div class="space-y-3 text-sm">
                        @foreach ($user->addresses as $address)
                            <div class="flex justify-between border rounded-xl p-4 bg-gray-50">
                                <div>
                                    <p class="font-medium">{{ $address->recipient_name }}</p>
                                    <p class="text-xs text-gray-600">
                                        {{ $address->address_line1 }}
                                        @if ($address->address_line2)
                                            , {{ $address->address_line2 }}
                                        @endif
                                        , {{ $address->postcode }} {{ $address->city }},
                                        {{ $address->state }}, {{ $address->country }}
                                    </p>
                                </div>

                                <div class="text-xs text-right space-y-1">
                                    <a href="{{ route('admin.addresses.edit', $address) }}"
                                        class="text-gray-600 hover:text-[#8f6a10]">Edit</a>

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
                @endif
            </div>

            {{-- RIGHT: Active，小卡片 --}}
            {{-- <div class="border rounded-2xl p-4 bg-gray-50 flex flex-col justify-between"> --}}
            <div class="border rounded-2xl p-4 bg-gray-50 flex flex-col justify-between">

                <div>
                    <p class="text-sm font-semibold text-gray-800">Active</p>
                    <p class="text-xs text-gray-500">If disabled, user cannot log in.</p>
                </div>

                <label class="relative inline-flex items-center cursor-pointer mt-3 self-start md:self-end">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                        @checked(old('is_active', $user->is_active ?? true))>
                    <div
                        class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#D4AF37]
                        after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                        after:bg-white after:h-5 after:w-5 after:rounded-full
                        after:transition-all peer-checked:after:translate-x-full">
                    </div>
                </label>
            </div>

        </div>

        {{-- 底部按钮 --}}
        <div class="mt-6 flex gap-3">
            <button class="px-5 py-2 rounded-xl bg-[#D4AF37] text-white text-sm font-semibold hover:bg-[#c29c2f]">
                Save changes
            </button>
            <a href="{{ route('admin.users.show', $user) }}"
                class="px-5 py-2 rounded-xl border border-gray-300 text-sm hover:bg-gray-50">
                Cancel
            </a>
        </div>
    </form>
@endsection
