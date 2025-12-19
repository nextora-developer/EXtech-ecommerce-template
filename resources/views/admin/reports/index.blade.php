@extends('admin.layouts.app') {{-- 这里用你现在 admin 的主 layout 名称 --}}

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-semibold mb-2">Reports</h1>
        <p class="text-sm text-gray-500">Basic report overview coming soon.</p>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="border rounded-2xl bg-white p-4">
                <p class="text-xs text-gray-500 mb-1">Total sales</p>
                <p class="text-lg font-semibold">RM 0.00</p>
            </div>
            <div class="border rounded-2xl bg-white p-4">
                <p class="text-xs text-gray-500 mb-1">Total orders</p>
                <p class="text-lg font-semibold">0</p>
            </div>
            <div class="border rounded-2xl bg-white p-4">
                <p class="text-xs text-gray-500 mb-1">Total customers</p>
                <p class="text-lg font-semibold">0</p>
            </div>
        </div>
    </div>
@endsection
