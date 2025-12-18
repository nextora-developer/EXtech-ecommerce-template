@extends('admin.layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold">User details</h1>
            <p class="text-sm text-gray-500">View user profile & activity</p>
        </div>

        <a href="{{ route('admin.users.edit', $user) }}"
            class="px-4 py-2 rounded-xl bg-[#D4AF37]/15 border border-[#D4AF37]/30 text-[#8f6a10] font-semibold">
            Edit user
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Left: basic info --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-2xl border p-4">
                <h2 class="text-sm font-semibold text-gray-700 mb-3">Profile</h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-6 text-sm">
                    <div>
                        <dt class="text-gray-500">Name</dt>
                        <dd class="font-medium text-gray-900">{{ $user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Email</dt>
                        <dd class="font-medium text-gray-900">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Registered at</dt>
                        <dd class="text-gray-800">
                            {{ $user->created_at?->format('Y-m-d H:i') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Last updated</dt>
                        <dd class="text-gray-800">
                            {{ $user->updated_at?->format('Y-m-d H:i') }}
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- 这里以后可以放 Recent Orders --}}
            <div class="bg-white rounded-2xl border p-4">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-semibold text-gray-700">Recent orders</h2>
                    <p class="text-xs text-gray-400">Coming soon</p>
                </div>
                <p class="text-sm text-gray-500">
                    You can link orders to this user later and show a summary here.
                </p>
            </div>
        </div>

        {{-- Right: status --}}
        <div class="space-y-4">
            <div class="bg-white rounded-2xl border p-4">
                <h2 class="text-sm font-semibold text-gray-700 mb-3">Status</h2>

                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Account status</span>
                    <span
                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                        {{ $user->is_active ?? true ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                        {{ $user->is_active ?? true ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
@endsection
