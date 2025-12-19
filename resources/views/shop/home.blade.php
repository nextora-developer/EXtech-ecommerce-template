<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">BRIF Shop</h1>

        <a class="underline" href="{{ route('shop.index') }}">Go to Shop</a>

        <h2 class="text-lg font-semibold mt-6 mb-3">Featured</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @forelse($featured as $p)
                <a href="{{ route('shop.show', $p->slug) }}" class="border rounded p-3 bg-white">
                    <div class="font-semibold">{{ $p->name }}</div>
                    <div class="text-sm text-gray-600">
                        RM {{ number_format($p->price_cents / 100, 2) }}
                    </div>
                </a>
            @empty
                <div class="text-gray-500">No products yet.</div>
            @endforelse
        </div>
    </div>
</x-app-layout>
