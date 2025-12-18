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

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="bg-white rounded-2xl border p-5 max-w-2xl">
        @csrf
        @method('PUT')

        <div class="space-y-4">
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
                <label class="text-xs text-gray-500">Password (optional)</label>
                <input type="password" name="password"
                    class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30"
                    placeholder="Leave blank to keep current password">
            </div>

            <div class="flex items-center justify-between border rounded-xl p-3">
                <div>
                    <p class="text-sm font-medium text-gray-900">Active</p>
                    <p class="text-xs text-gray-500">If disabled, user cannot log in.</p>
                </div>

                <label class="relative inline-flex items-center cursor-pointer">
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

        <div class="mt-5 flex gap-3">
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
