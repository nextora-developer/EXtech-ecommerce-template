<x-guest-layout>
    @if (session('status'))
        <div
            class="mb-6 px-4 py-3 rounded-xl bg-amber-50 text-amber-800 border border-amber-200 text-sm animate-fade-in">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd"></path>
                </svg>
                {{ session('status') }}
            </div>
        </div>
    @endif

    <div class="max-w-md mx-auto">
        <form method="POST" action="{{ route('login') }}"
            class="bg-white/80 backdrop-blur-sm border border-[#D4AF37]/20 shadow-xl rounded-3xl px-8 py-10">

            @csrf

            <div class="text-center mb-8">

                <!-- 小副标题（品牌名） -->
                <p class="text-sm font-semibold tracking-[0.25em] text-[#8f6a10] uppercase mb-2">
                    Extech Commerce
                </p>

                <!-- 主标题 -->
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Sign in to your account
                </h2>

            </div>


            <div class="mb-5">
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2 ml-1">
                    Email Address
                </label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    placeholder="name@company.com"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#D4AF37] focus:ring-4 focus:ring-[#D4AF37]/10 transition-all duration-200 outline-none text-gray-800" />
                @error('email')
                    <p class="text-xs text-red-500 mt-1.5 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <div class="flex justify-between items-center mb-2 ml-1">
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                        Password
                    </label>
                    {{-- @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-[#8f6a10] hover:text-[#D4AF37]">
                            Forgot password?
                        </a>
                    @endif --}}
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    placeholder="••••••••"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#D4AF37] focus:ring-4 focus:ring-[#D4AF37]/10 transition-all duration-200 outline-none text-gray-800" />
                @error('password')
                    <p class="text-xs text-red-500 mt-1.5 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center mb-6 ml-1">
                <input id="remember_me" type="checkbox" name="remember"
                    class="rounded border-gray-300 text-[#D4AF37] focus:ring-[#D4AF37] transition duration-150 ease-in-out">
                <label for="remember_me" class="ml-2 block text-sm text-gray-600">Remember me</label>
            </div>

            <div class="space-y-4">
                <button type="submit"
                    class="w-full inline-flex items-center justify-center px-6 py-3.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#8f6a10] text-white shadow-lg shadow-gold-500/20 hover:shadow-[#D4AF37]/40 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 text-base font-bold">
                    Sign In
                </button>

                <a href="{{ route('home') }}"
                    class="w-full inline-flex items-center justify-center px-5 py-3 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 text-sm font-semibold">
                    ← Back to Shop
                </a>
            </div>

            @if (Route::has('register'))
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-600">
                        New here?
                        <a href="{{ route('register') }}"
                            class="text-[#8f6a10] font-bold hover:text-[#D4AF37] transition-colors">
                            Create an account
                        </a>
                    </p>
                </div>
            @endif
        </form>
    </div>
</x-guest-layout>
