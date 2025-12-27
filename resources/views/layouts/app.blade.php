<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

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
    <footer class="bg-[#F9F4E5] border-t border-[#E5D9B6]">
        <div class="max-w-7xl5 mx-auto px-6 lg:px-12 py-14">

            <div class="grid grid-cols-1 md:grid-cols-5 gap-10">

                {{-- Brand --}}
                <div>
                    <h3 class="text-[#8f6a10] font-semibold text-xl mb-4">BRIF Shop</h3>
                    <p class="text-sm text-gray-700 leading-relaxed mb-4">
                        Curated essentials, trusted quality and a smooth shopping experience.
                    </p>

                    <div class="flex space-x-4 mt-4">

                        {{-- Instagram --}}
                        <a href="#" class="text-gray-700 hover:text-[#D4AF37] transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M7.75 2a5.75 5.75 0 00-5.75 5.75v8.5A5.75 5.75 0 007.75 22h8.5A5.75 5.75 0 0022 16.25v-8.5A5.75 5.75 0 0016.25 2h-8.5zm8.5 2a3.75 3.75 0 013.75 3.75v8.5a3.75 3.75 0 01-3.75 3.75h-8.5a3.75 3.75 0 01-3.75-3.75v-8.5A3.75 3.75 0 017.75 4h8.5zm-4.25 3a5 5 0 100 10 5 5 0 000-10zm0 2a3 3 0 110 6 3 3 0 010-6zm5.25-.5a1 1 0 110 2 1 1 0 010-2z" />
                            </svg>
                        </a>

                        {{-- Facebook --}}
                        <a href="#" class="text-gray-700 hover:text-[#D4AF37] transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M13.5 9H16V6h-2.5c-2.6 0-4.5 1.8-4.5 4.4V13H7v3h2v6h3v-6h2.3l.7-3H12V10c0-.9.3-1 1.5-1z" />
                            </svg>
                        </a>

                        {{-- WhatsApp --}}
                        <a href="#" class="text-gray-700 hover:text-[#D4AF37] transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2a9.9 9.9 0 00-8.48 15.2L2 22l4.95-1.48A9.9 9.9 0 1012 2zm0 18a8 8 0 01-4.08-1.12l-.29-.17-2.93.88.9-2.85-.19-.3A8 8 0 1112 20zm4.43-5.6c-.24-.12-1.43-.7-1.65-.78s-.38-.12-.54.12-.62.78-.76.94-.28.18-.52.06a6.54 6.54 0 01-1.92-1.18 7.2 7.2 0 01-1.34-1.67c-.14-.24 0-.37.1-.5s.24-.28.36-.42.16-.24.24-.4.04-.3-.02-.42-.54-1.3-.74-1.78-.4-.42-.54-.43h-.46a.88.88 0 00-.64.3 2.7 2.7 0 00-.84 2c0 1.18.84 2.32.96 2.48s1.66 2.64 4.03 3.6a6.9 6.9 0 003.02.72 2.6 2.6 0 001.72-.75 2.2 2.2 0 00.5-1.36c0-.24-.02-.44-.08-.6s-.22-.16-.46-.28z" />
                            </svg>
                        </a>

                    </div>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h4 class="text-gray-900 font-semibold text-base mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li><a class="hover:text-[#D4AF37]">Shop</a></li>
                        <li><a class="hover:text-[#D4AF37]">Categories</a></li>
                        <li><a class="hover:text-[#D4AF37]">Featured</a></li>
                        <li><a class="hover:text-[#D4AF37]">Contact</a></li>
                    </ul>
                </div>

                {{-- Policy --}}
                <div>
                    <h4 class="text-gray-900 font-semibold text-base mb-4">Policy</h4>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li><a class="hover:text-[#D4AF37]">Privacy Policy</a></li>
                        <li><a class="hover:text-[#D4AF37]">Corporate Policy</a></li>
                        <li><a class="hover:text-[#D4AF37]">Return Policy</a></li>
                        <li><a class="hover:text-[#D4AF37]">Shipping & Delivery</a></li>
                        <li><a class="hover:text-[#D4AF37]">Returns & Refunds</a></li>
                    </ul>
                </div>

                {{-- My Account --}}
                <div>
                    <h4 class="text-gray-900 font-semibold text-base mb-4">My Account</h4>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li><a class="hover:text-[#D4AF37]">Account</a></li>
                        <li><a class="hover:text-[#D4AF37]">Orders</a></li>
                        <li><a class="hover:text-[#D4AF37]">Favorite</a></li>
                        <li><a class="hover:text-[#D4AF37]">Address</a></li>
                        <li><a class="hover:text-[#D4AF37]">Profile</a></li>
                    </ul>
                </div>


                {{-- Newsletter --}}
                <div>
                    <h4 class="text-gray-900 font-semibold text-base mb-4">Newsletter</h4>
                    <p class="text-sm text-gray-700 mb-4">Get updates and new arrivals.</p>
                    <div class="flex">
                        <input type="email" placeholder="Your email"
                            class="flex-1 px-3 py-2 border border-[#D4AF37]/50 rounded-l-lg text-sm focus:outline-none focus:border-[#D4AF37]">
                        <button class="px-4 bg-[#D4AF37] text-white rounded-r-lg text-sm hover:bg-[#b8942f]">
                            Join
                        </button>
                    </div>
                </div>

            </div>

            <div class="border-t border-[#E5D9B6] mt-10 pt-5 text-center">
                <p class="text-xs text-gray-700">
                    © {{ date('Y') }} BRIF Shop. All rights reserved.
                </p>
            </div>
        </div>
    </footer>




    @stack('scripts')
</body>

</html>
