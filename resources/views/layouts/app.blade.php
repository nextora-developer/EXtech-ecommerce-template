<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Ecommerce') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    <footer class="relative overflow-hidden bg-[#FAF9F6] border-t border-gray-100">
        {{-- Soft premium background glow --}}
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-32 -left-40 w-[520px] h-[520px] rounded-full bg-[#D4AF37]/10 blur-3xl"></div>
            <div class="absolute -bottom-40 -right-40 w-[620px] h-[620px] rounded-full bg-black/5 blur-3xl"></div>
            <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-gray-200/70 to-transparent">
            </div>
        </div>

        <div class="relative max-w-7xl5 mx-auto px-6 lg:px-12 py-16 lg:py-20">

            {{-- Top Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-y-12 lg:gap-y-0 lg:gap-x-12">

                {{-- Brand --}}
                <div class="lg:col-span-4">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="h-10 w-10 rounded-2xl bg-gradient-to-br from-[#D4AF37] to-[#8f6a10] flex items-center justify-center text-[10px] font-extrabold text-white shadow-sm">
                            EX
                        </div>
                        <div class="flex flex-col leading-tight">
                            <span class="text-xl font-bold tracking-tight text-gray-900">Shop</span>
                            <span class="text-xs text-gray-400 tracking-wide">Premium Essentials</span>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 leading-relaxed max-w-sm">
                        Curating high-quality essentials for the modern Malaysian lifestyle.
                        Excellence in every detail, delivered with care.
                    </p>

                    {{-- Small brand badge --}}
                    <div class="mt-6">
                        <span
                            class="inline-flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest
                               text-[#8f6a10] bg-white/70 border border-[#D4AF37]/20 px-3 py-1 rounded-full">
                            Malaysia • Crafted with care
                        </span>
                    </div>
                </div>

                {{-- Explore --}}
                <div class="lg:col-span-2 lg:col-start-5">
                    <h4 class="text-gray-900 font-extrabold text-sm uppercase tracking-wider">
                        Explore
                    </h4>
                    <div class="h-px w-10 bg-gray-200 mt-4 mb-6"></div>

                    <ul class="space-y-4">
                        @foreach (['Shop All', 'New Arrivals', 'Best Sellers', 'Contact Us'] as $link)
                            <li>
                                <a href="#"
                                    class="text-sm text-gray-500 hover:text-gray-900 transition relative inline-block
                                      after:content-[''] after:absolute after:left-0 after:-bottom-1
                                      after:h-px after:w-0 after:bg-[#D4AF37] hover:after:w-full
                                      after:transition-all after:duration-300">
                                    {{ $link }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Support --}}
                <div class="lg:col-span-2">
                    <h4 class="text-gray-900 font-extrabold text-sm uppercase tracking-wider">
                        Support
                    </h4>
                    <div class="h-px w-10 bg-gray-200 mt-4 mb-6"></div>

                    <ul class="space-y-4">
                        @foreach (['Privacy Policy', 'Shipping & Delivery', 'Returns & Refunds', 'Terms of Service'] as $link)
                            <li>
                                <a href="#"
                                    class="text-sm text-gray-500 hover:text-gray-900 transition relative inline-block
                                      after:content-[''] after:absolute after:left-0 after:-bottom-1
                                      after:h-px after:w-0 after:bg-[#D4AF37] hover:after:w-full
                                      after:transition-all after:duration-300">
                                    {{ $link }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Account --}}
                <div class="lg:col-span-2">
                    <h4 class="text-gray-900 font-extrabold text-sm uppercase tracking-wider">
                        Account
                    </h4>
                    <div class="h-px w-10 bg-gray-200 mt-4 mb-6"></div>

                    <ul class="space-y-4">
                        <li>
                            <a href="{{ route('account.index') }}"
                                class="text-sm text-gray-500 hover:text-gray-900 transition relative inline-block
                                  after:content-[''] after:absolute after:left-0 after:-bottom-1
                                  after:h-px after:w-0 after:bg-[#D4AF37] hover:after:w-full
                                  after:transition-all after:duration-300">
                                Profile Details
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('account.orders.index') }}"
                                class="text-sm text-gray-500 hover:text-gray-900 transition relative inline-block
                                  after:content-[''] after:absolute after:left-0 after:-bottom-1
                                  after:h-px after:w-0 after:bg-[#D4AF37] hover:after:w-full
                                  after:transition-all after:duration-300">
                                Order History
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="text-sm text-gray-500 hover:text-gray-900 transition relative inline-block
                                  after:content-[''] after:absolute after:left-0 after:-bottom-1
                                  after:h-px after:w-0 after:bg-[#D4AF37] hover:after:w-full
                                  after:transition-all after:duration-300">
                                Wishlist
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="text-sm text-gray-500 hover:text-gray-900 transition relative inline-block
                                  after:content-[''] after:absolute after:left-0 after:-bottom-1
                                  after:h-px after:w-0 after:bg-[#D4AF37] hover:after:w-full
                                  after:transition-all after:duration-300">
                                Addresses
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Social Media --}}
                <div class="lg:col-span-2">
                    <h4 class="text-gray-900 font-extrabold text-sm uppercase tracking-wider">
                        Social Media
                    </h4>
                    <div class="h-px w-10 bg-gray-200 mt-4 mb-6"></div>

                    <ul class="space-y-4">
                        <li>
                            <a href="#"
                                class="text-sm text-gray-500 hover:text-gray-900 transition relative inline-block
                                  after:content-[''] after:absolute after:left-0 after:-bottom-1
                                  after:h-px after:w-0 after:bg-[#D4AF37] hover:after:w-full
                                  after:transition-all after:duration-300">
                                Instagram
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="text-sm text-gray-500 hover:text-gray-900 transition relative inline-block
                                  after:content-[''] after:absolute after:left-0 after:-bottom-1
                                  after:h-px after:w-0 after:bg-[#D4AF37] hover:after:w-full
                                  after:transition-all after:duration-300">
                                Facebook
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="text-sm text-gray-500 hover:text-gray-900 transition relative inline-block
                                  after:content-[''] after:absolute after:left-0 after:-bottom-1
                                  after:h-px after:w-0 after:bg-[#D4AF37] hover:after:w-full
                                  after:transition-all after:duration-300">
                                WhatsApp Support
                            </a>
                        </li>

                        <li class="text-sm text-gray-400">
                            <a href="mailto:support@yourshop.com" class="hover:text-gray-600 transition">
                                support@yourshop.com
                            </a>
                        </li>
                    </ul>
                </div>

            </div>

            {{-- Bottom Bar --}}
            <div
                class="mt-14 pt-8 border-t border-gray-200/70 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-gray-400 font-medium">
                    © {{ date('Y') }} Shop. Built with pride in Malaysia.
                </p>

                <div class="flex items-center gap-4">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        Secure Payments
                    </span>

                    <div class="flex items-center gap-4">
                        <img src="/images/payments/fpx.png" alt="FPX"
                            class="h-5 opacity-70 grayscale hover:opacity-100 hover:grayscale-0 transition" />

                        <img src="/images/payments/visa.png" alt="Visa"
                            class="h-5 opacity-70 grayscale hover:opacity-100 hover:grayscale-0 transition" />

                        <img src="/images/payments/mastercard.png" alt="Mastercard"
                            class="h-5 opacity-70 grayscale hover:opacity-100 hover:grayscale-0 transition" />
                    </div>
                </div>
            </div>

        </div>
    </footer>





    {{-- Back to Top Button --}}
    <button id="backToTopBtn"
        class="hidden fixed right-4 bottom-24 z-50
           w-12 h-12 rounded-full
           bg-[#D4AF37] text-white
           flex items-center justify-center
           shadow-lg shadow-[#D4AF37]/40
           hover:bg-[#c49a2f] transition-all duration-300">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>

    <a href="https://wa.me/601156898898" target="_blank"
        class="fixed right-4 bottom-4 z-50
          w-12 h-12 rounded-full
          bg-[#25D366] text-white
          flex items-center justify-center
          shadow-lg shadow-[#25D366]/40
          hover:bg-[#1DA851] transition-all duration-300 animate-bounce">

        <!-- WhatsApp Icon -->

        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
            class="bi bi-whatsapp" viewBox="0 0 16 16">
            <path
                d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232" />
        </svg>
    </a>


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

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '{{ session('success') }}',
                toast: true,
                position: 'bottom-right',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
            });
        </script>
    @endif

    @stack('scripts')
</body>

</html>
