@extends('admin.layouts.app')

@section('content')
<div class="p-6">

    <h1 class="text-2xl font-semibold mb-6">Top Customers</h1>

    <div class="border bg-white rounded-2xl p-6">

        @if($topCustomers->isEmpty())
            <p class="text-sm text-gray-500">No customer data available.</p>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left border-b">
                        <th class="py-2">Customer</th>
                        <th class="py-2">Total Spent (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topCustomers as $c)
                        <tr class="border-b">
                            <td class="py-2">{{ $c->name }}</td>
                            <td class="py-2">RM {{ number_format($c->total_spent ?? 0, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    </div>

</div>
@endsection
