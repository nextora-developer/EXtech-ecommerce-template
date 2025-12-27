<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Login</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#0b0b0b] text-white flex items-center justify-center relative overflow-hidden">

    <!-- Gold glow background -->
    <div class="absolute inset-0">
        <div class="absolute -top-40 -left-40 w-[500px] h-[500px] rounded-full
            bg-[radial-gradient(circle,rgba(212,175,55,0.18),transparent_60%)]">
        </div>

        <div class="absolute bottom-[-200px] right-[-200px] w-[600px] h-[600px] rounded-full
            bg-[radial-gradient(circle,rgba(212,175,55,0.12),transparent_65%)]">
        </div>
    </div>

    <!-- Login Card -->
    <div class="relative z-10 w-full max-w-md">
        <div class="rounded-2xl border border-[rgba(212,175,55,0.35)]
            bg-[rgba(20,20,20,0.75)]
            backdrop-blur-xl
            shadow-[0_0_0_1px_rgba(212,175,55,0.15),0_25px_60px_rgba(0,0,0,0.75)]
            p-8">

            <!-- Header -->
            <div class="mb-8 text-center">
                <div class="text-sm tracking-widest text-[#D4AF37]/80 mb-2">
                    ADMIN PANEL
                </div>

                <h1 class="text-3xl font-semibold text-[#D4AF37]">
                    Welcome Back
                </h1>

                <p class="mt-2 text-sm text-white/50">
                    Sign in to manage Shop
                </p>
            </div>

            <!-- Errors -->
            @if ($errors->any())
                <div class="mb-5 rounded-lg border border-red-500/40 bg-red-500/10 p-3 text-sm text-red-300">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block text-xs uppercase tracking-wide text-white/60 mb-1">
                        Email Address
                    </label>
                    <input
                        type="email"
                        name="email"
                        required
                        class="w-full rounded-lg bg-black/40 border border-white/10
                            px-4 py-2.5
                            text-white placeholder-white/30
                            focus:border-[#D4AF37]
                            focus:ring-0"
                        placeholder="admin@admin.com"
                    >
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-xs uppercase tracking-wide text-white/60 mb-1">
                        Password
                    </label>
                    <input
                        type="password"
                        name="password"
                        required
                        class="w-full rounded-lg bg-black/40 border border-white/10
                            px-4 py-2.5
                            text-white placeholder-white/30
                            focus:border-[#D4AF37]
                            focus:ring-0"
                        placeholder="••••••••"
                    >
                </div>

                <!-- Remember / Back -->
                <div class="flex items-center justify-between text-sm">
                    <label class="inline-flex items-center gap-2 text-white/60">
                        <input type="checkbox" name="remember"
                               class="rounded border-white/20 bg-black/40 text-[#D4AF37] focus:ring-0">
                        Remember me
                    </label>

                    <a href="{{ route('home') }}" class="text-[#D4AF37]/80 hover:text-[#D4AF37] underline">
                        Back to shop
                    </a>
                </div>

                <!-- Submit -->
                <button
                    class="w-full mt-2 py-3 rounded-lg
                        bg-gradient-to-r from-[#D4AF37] to-[#B8962E]
                        text-black font-semibold tracking-wide
                        hover:brightness-110
                        active:scale-[0.99]
                        transition">
                    Login as Admin
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-8 text-center text-xs text-white/30">
                © {{ date('Y') }} CompanyName · Internal Access Only
            </div>
        </div>
    </div>

</body>
</html>
