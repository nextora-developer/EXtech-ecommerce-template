<x-guest-layout>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-[#FFF9E6] text-[#8f6a10] border border-[#D4AF37]/40 text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}"
        class="bg-white border border-[#D4AF37]/25 shadow-md rounded-2xl px-6 py-8 max-w-md mx-auto">

        @csrf

        <h2 class="text-center text-2xl font-semibold text-[#0A0A0C] mb-6">
            Sign in to Your Account
        </h2>

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-[#0A0A0C] mb-1">
                Email
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                autocomplete="username"
                class="w-full rounded-xl border border-gray-300 focus:border-[#D4AF37] focus:ring-[#D4AF37] text-gray-800" />
            @error('email')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-[#0A0A0C] mb-1">
                Password
            </label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="w-full rounded-xl border border-gray-300 focus:border-[#D4AF37] focus:ring-[#D4AF37] text-gray-800" />
            @error('password')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Buttons -->
        <div class="flex flex-col gap-4 mt-6">

            {{-- Sign In (Full Width Button) --}}
            <button type="submit"
                class="w-full inline-flex items-center justify-center px-6 py-3 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#8f6a10] text-white shadow hover:brightness-110 transition text-base font-semibold">
                Sign In
            </button>

            {{-- Back button placed below --}}
            <a href="{{ url()->previous() }}"
                class="w-full inline-flex items-center justify-center px-5 py-3 rounded-xl border border-[#D4AF37]/40 text-[#8f6a10] hover:border-[#D4AF37] hover:bg-[#FFF9E6] transition text-sm font-medium">
                ← Back
            </a>

        </div>


        @if (Route::has('register'))
            <p class="mt-6 text-center text-sm text-gray-600">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-[#8f6a10] font-semibold hover:underline">
                    Register now
                </a>
            </p>
        @endif

    </form>

</x-guest-layout>
