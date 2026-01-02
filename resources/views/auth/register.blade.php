<x-guest-layout>
    <div class="max-w-md mx-auto">
        <form method="POST" action="{{ route('register') }}"
            class="bg-white/80 backdrop-blur-sm border border-[#D4AF37]/20 shadow-xl rounded-3xl px-8 py-10">

            @csrf

            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                    Create Account
                </h2>
                <p class="text-gray-500 mt-2 text-sm">Join us and start your journey today</p>
            </div>

            <div class="space-y-5">
                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2 ml-1">
                        Full Name
                    </label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        placeholder="John Doe"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#D4AF37] focus:ring-4 focus:ring-[#D4AF37]/10 transition-all duration-200 outline-none text-gray-800" />
                    @error('name')
                        <p class="text-xs text-red-500 mt-1.5 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2 ml-1">
                        Email Address
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        placeholder="name@company.com"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#D4AF37] focus:ring-4 focus:ring-[#D4AF37]/10 transition-all duration-200 outline-none text-gray-800" />
                    @error('email')
                        <p class="text-xs text-red-500 mt-1.5 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-5">
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2 ml-1">
                            Password
                        </label>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                            placeholder="••••••••"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#D4AF37] focus:ring-4 focus:ring-[#D4AF37]/10 transition-all duration-200 outline-none text-gray-800" />
                        @error('password')
                            <p class="text-xs text-red-500 mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2 ml-1">
                            Confirm Password
                        </label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            placeholder="••••••••"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#D4AF37] focus:ring-4 focus:ring-[#D4AF37]/10 transition-all duration-200 outline-none text-gray-800" />
                    </div>
                </div>
            </div>

            <div class="mt-8 space-y-4">
                <button type="submit"
                    class="w-full inline-flex items-center justify-center px-6 py-3.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#8f6a10] text-white shadow-lg shadow-gold-500/20 hover:shadow-[#D4AF37]/40 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 text-sm font-bold">
                    Create Account
                </button>

                <a href="{{ url()->previous() }}"
                    class="w-full inline-flex items-center justify-center px-5 py-3 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 text-sm font-semibold">
                    ← Back
                </a>
            </div>

            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-[#8f6a10] font-bold hover:text-[#D4AF37] transition-colors">
                        Sign in instead
                    </a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>