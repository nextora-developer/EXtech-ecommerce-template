<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Shop</h1>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @forelse($products as $p)
                <a href="{{ route('shop.show', $p->slug) }}" class="border rounded p-3 bg-white">
                    <div class="font-semibold">{{ $p->name }}</div>
                    <div class="text-sm text-gray-600">
                        RM {{ number_format($p->price, 2) }}
                    </div>
                </a>
            @empty
                <div class="text-gray-500">No products found.</div>
            @endforelse
        </div>

        <div class="mt-6">{{ $products->links() }}</div>
    </div>
</x-app-layout>
