<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Ecommerce') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    {{-- Global Footer --}}
    <footer class="bg-[#FAF9F6] border-t border-gray-100">
        <div class="max-w-7xl5 mx-auto px-6 lg:px-12 py-16 lg:py-20">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12 lg:gap-8">

                {{-- Brand & About --}}
                <div class="lg:col-span-1">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="h-9 w-9 rounded-xl bg-gradient-to-br from-[#D4AF37] to-[#8f6a10] flex items-center justify-center text-[10px] font-bold text-white shadow-sm">
                            EX
                        </div>
                        <span class="text-xl font-bold tracking-tight text-gray-900">Shop</span>
                    </div>
                    <p class="text-sm text-gray-500 leading-relaxed mb-8 max-w-xs">
                        Curating high-quality essentials for the modern Malaysian lifestyle. Excellence in every detail,
                        delivered with care.
                    </p>

                    <div class="flex items-center gap-4">
                        {{-- Instagram --}}
                        <a href="#"
                            class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-100 text-gray-400 hover:text-[#D4AF37] hover:border-[#D4AF37]/30 hover:shadow-sm transition-all duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </a>

                        {{-- Facebook --}}
                        <a href="#"
                            class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-100 text-gray-400 hover:text-[#D4AF37] hover:border-[#D4AF37]/30 hover:shadow-sm transition-all duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z" />
                            </svg>
                        </a>

                        {{-- WhatsApp --}}
                        <a href="#"
                            class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-100 text-gray-400 hover:text-[#D4AF37] hover:border-[#D4AF37]/30 hover:shadow-sm transition-all duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.438 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z" />
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h4 class="text-gray-900 font-bold text-sm uppercase tracking-wider mb-6">Explore</h4>
                    <ul class="space-y-4">
                        @foreach (['Shop All', 'New Arrivals', 'Best Sellers', 'Contact Us'] as $link)
                            <li>
                                <a href="#"
                                    class="text-sm text-gray-500 hover:text-[#8f6a10] hover:translate-x-1 inline-block transition-all duration-200">
                                    {{ $link }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Policy --}}
                <div>
                    <h4 class="text-gray-900 font-bold text-sm uppercase tracking-wider mb-6">Support</h4>
                    <ul class="space-y-4">
                        @foreach (['Privacy Policy', 'Shipping & Delivery', 'Returns & Refunds', 'Terms of Service'] as $link)
                            <li>
                                <a href="#"
                                    class="text-sm text-gray-500 hover:text-[#8f6a10] hover:translate-x-1 inline-block transition-all duration-200">
                                    {{ $link }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- My Account --}}
                <div>
                    <h4 class="text-gray-900 font-bold text-sm uppercase tracking-wider mb-6">Account</h4>
                    <ul class="space-y-4">
                        <li><a href="{{ route('account.index') }}"
                                class="text-sm text-gray-500 hover:text-[#8f6a10] hover:translate-x-1 inline-block transition-all duration-200">Profile
                                Details</a></li>
                        <li><a href="{{ route('account.orders.index') }}"
                                class="text-sm text-gray-500 hover:text-[#8f6a10] hover:translate-x-1 inline-block transition-all duration-200">Order
                                History</a></li>
                        <li><a href="#"
                                class="text-sm text-gray-500 hover:text-[#8f6a10] hover:translate-x-1 inline-block transition-all duration-200">Wishlist</a>
                        </li>
                        <li><a href="#"
                                class="text-sm text-gray-500 hover:text-[#8f6a10] hover:translate-x-1 inline-block transition-all duration-200">Addresses</a>
                        </li>
                    </ul>
                </div>

                {{-- Newsletter --}}
                <div class="lg:col-span-1">
                    <h4 class="text-gray-900 font-bold text-sm uppercase tracking-wider mb-6">Newsletter</h4>
                    <p class="text-sm text-gray-500 mb-6">Join our mailing list for exclusive offers and updates.</p>

                    <form action="#" class="relative group">
                        <input type="email" placeholder="Email address"
                            class="w-full bg-white border border-gray-100 rounded-2xl px-5 py-3.5 text-sm focus:ring-2 focus:ring-[#D4AF37]/20 focus:border-[#D4AF37]/50 transition-all outline-none">
                        <button
                            class="absolute right-1.5 top-1.5 bottom-1.5 px-6 bg-gray-900 text-white text-xs font-bold rounded-xl hover:bg-black transition-colors">
                            Join
                        </button>
                    </form>

                    <p class="mt-4 text-xs text-gray-400">By subscribing, you agree to our Privacy Policy.</p>
                </div>

            </div>

            {{-- Bottom Bar --}}
            <div
                class="border-t border-gray-100 mt-16 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-xs text-gray-400 font-medium">
                    © {{ date('Y') }} Shop. Built with pride in Malaysia.
                </p>

                {{-- Payment Icons (Optional Placeholder) --}}
                <div class="flex items-center gap-4 opacity-50 grayscale hover:grayscale-0 transition-all">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Secure Payments
                        via</span>
                    <div class="flex gap-2 text-gray-500">
                        {{-- Standard payment icons could go here --}}
                        <span class="text-xs border border-gray-200 px-2 py-1 rounded">FPX</span>
                        <span class="text-xs border border-gray-200 px-2 py-1 rounded">VISA</span>
                        <span class="text-xs border border-gray-200 px-2 py-1 rounded">MASTERCARD</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    {{-- Back to Top Button --}}
    <button id="backToTopBtn"
        class="hidden fixed right-4 bottom-4 z-50
           w-11 h-11 rounded-full
           bg-[#D4AF37] text-white
           flex items-center justify-center
           shadow-lg shadow-[#D4AF37]/40
           hover:bg-[#c49a2f] transition-all duration-300">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>



    <script>
        function refreshCartCount() {
            console.log('Refreshing cart count…');
            const badge = document.querySelector('[data-cart-count]');
            if (!badge) return;

            fetch("{{ route('cart.count') }}", {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (typeof data.count !== 'undefined') {
                        badge.textContent = data.count;
                    }
                })
                .catch(() => {});
        }

        document.addEventListener('DOMContentLoaded', refreshCartCount);

        window.addEventListener('pageshow', function(event) {
            const navEntries = performance.getEntriesByType('navigation');
            const navType = navEntries[0] ? navEntries[0].type : null;

            if (event.persisted || navType === 'back_forward') {
                refreshCartCount();
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const btn = document.getElementById("backToTopBtn");

            // 显示 / 隐藏按钮
            window.addEventListener("scroll", () => {
                if (window.scrollY > 300) {
                    btn.classList.remove("hidden");
                } else {
                    btn.classList.add("hidden");
                }
            });

            // 回到顶部（平滑滚动）
            btn.addEventListener("click", () => {
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            });
        });
    </script>


    @stack('scripts')
</body>

</html>
