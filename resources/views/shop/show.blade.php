<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">
        <a class="underline text-sm" href="{{ route('shop.index') }}">← Back to Shop</a>

        <h1 class="text-2xl font-bold mt-3">{{ $product->name }}</h1>
        <div class="text-gray-600 mt-1">
            RM {{ number_format($product->price, 2) }}
        </div>

        <div class="mt-4 text-gray-700">
            {{ $product->description ?? 'No description' }}
        </div>

        <form class="mt-6" method="POST" action="{{ route('cart.add', $product) }}">
            @csrf
            <button class="px-4 py-2 rounded bg-black text-white">
                Add to Cart
            </button>
        </form>
    </div>
</x-app-layout>
