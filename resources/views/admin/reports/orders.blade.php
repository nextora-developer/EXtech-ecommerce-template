@extends('admin.layouts.app')

@section('content')
<div class="p-6">

    <h1 class="text-2xl font-semibold mb-6">Order Status Report</h1>

    <canvas id="orderChart" height="80"></canvas>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('orderChart');

new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($statusCounts->keys() ?? []) !!},
        datasets: [{
            data: {!! json_encode($statusCounts->values() ?? []) !!},
        }],
    },
});
</script>
@endsection
