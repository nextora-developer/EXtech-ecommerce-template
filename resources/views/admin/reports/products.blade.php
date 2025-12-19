@extends('admin.layouts.app')

@section('content')
<div class="p-6">

    <h1 class="text-2xl font-semibold mb-6">Product Performance</h1>

    <div class="border bg-white rounded-2xl p-6">
        <h2 class="text-lg font-semibold mb-3">Top Selling Products</h2>

        @if($topProducts->isEmpty())
            <p class="text-sm text-gray-500">No product data available.</p>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left border-b">
                        <th class="py-2">Product</th>
                        <th class="py-2">Sold Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topProducts as $p)
                        <tr class="border-b">
                            <td class="py-2">{{ $p->name }}</td>
                            <td class="py-2">{{ $p->sold_qty ?? 0 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>
@endsection
