<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Login | Secure Access</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="min-h-screen bg-gradient-to-br from-[#d4af37] via-[#f6e8b1] to-white
 text-gray-900 flex items-center justify-center relative overflow-hidden selection:bg-[#D4AF37]/30">

    <div class="relative z-10 w-full max-w-md px-4">

        <!-- White Theme Card -->
        <div
            class="group rounded-3xl 
            border border-amber-300/30 
            bg-white/80 backdrop-blur-xl
            shadow-[0_20px_40px_rgba(212,175,55,0.18)]
            p-8 transition-all duration-500
            hover:border-amber-400 hover:shadow-amber-300/30">

            <!-- Header -->
            <div class="mb-10 text-center">
                <div
                    class="inline-flex items-center justify-center w-12 h-12 mb-4 rounded-xl
                        bg-gradient-to-br from-[#D4AF37] to-[#B8962E]
                        shadow-lg shadow-[#D4AF37]/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                    Admin <span class="text-[#D4AF37]">Portal</span>
                </h1>

                <p class="mt-2 text-sm text-gray-600">
                    Secure authentication required
                </p>
            </div>

            <!-- Error Box -->
            @if ($errors->any())
                <div
                    class="mb-6 rounded-xl border border-red-300/40 bg-red-50 p-4
                    text-sm text-red-700 flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"></path>
                    </svg>
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-600 mb-2 ml-1">
                        Username
                    </label>
                    <input type="email" name="email" required
                        class="w-full rounded-xl bg-white border border-amber-300/40 
                        px-4 py-3 text-gray-900 placeholder-gray-400
                        focus:border-[#D4AF37] focus:ring-4 focus:ring-[#D4AF37]/15
                        transition-all outline-none"
                        placeholder="admin@internal.com">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-600 mb-2 ml-1">
                        Password
                    </label>
                    <input type="password" name="password" required
                        class="w-full rounded-xl bg-white border border-amber-300/40 
                        px-4 py-3 text-gray-900 placeholder-gray-400
                        focus:border-[#D4AF37] focus:ring-4 focus:ring-[#D4AF37]/15
                        transition-all outline-none"
                        placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between text-sm font-medium">
                    <label
                        class="inline-flex items-center gap-2 text-gray-600 cursor-pointer hover:text-gray-900 transition-colors">
                        <input type="checkbox" name="remember"
                            class="rounded border-amber-300/60 text-[#D4AF37] focus:ring-0">
                        Remember
                    </label>

                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-[#D4AF37] transition-colors">
                        ← Exit to Shop
                    </a>
                </div>

                <button
                    class="w-full py-4 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#B8962E]
                    text-white font-bold uppercase tracking-widest text-sm
                    shadow-lg shadow-[#D4AF37]/20
                    hover:shadow-[#D4AF37]/30 hover:-translate-y-0.5
                    active:scale-[0.98] transition-all duration-200">
                    Authorize Access
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-5 pt-6 border-t border-amber-200/40 text-center">
                <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500">
                    System Core v2.4.0 <span class="mx-2">•</span> Secure Layer Active
                </p>
            </div>
        </div>
    </div>

</body>


</html>
