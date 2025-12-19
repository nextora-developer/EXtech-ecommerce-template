<x-guest-layout>

    <form method="POST" action="{{ route('password.email') }}"
        class="bg-white border border-[#D4AF37]/25 shadow-md rounded-2xl px-6 py-8 max-w-md mx-auto">

        @csrf

        <h2 class="text-center text-2xl font-semibold text-[#0A0A0C] mb-3">
            Forgot Your Password?
        </h2>

        <p class="mb-6 text-sm text-gray-600 text-center">
            No problem — enter your email and we’ll send you a password reset link.
        </p>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 px-4 py-3 rounded-lg bg-[#FFF9E6] text-[#8f6a10] border border-[#D4AF37]/40 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <!-- Email Address -->
        <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-[#0A0A0C] mb-1">
                Email Address
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full rounded-xl border border-gray-300 focus:border-[#D4AF37] focus:ring-[#D4AF37] text-gray-800" />
            @error('email')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Buttons -->
        <div class="flex items-center justify-between">

            {{-- Back --}}
            <a href="{{ url()->previous() }}"
                class="inline-flex items-center px-4 py-2 rounded-xl border border-[#D4AF37]/40 text-[#8f6a10] hover:border-[#D4AF37] hover:bg-[#FFF9E6] transition text-sm font-medium">
                ← Back
            </a>

            {{-- Submit --}}
            <button type="submit"
                class="inline-flex items-center px-6 py-2 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#8f6a10] text-white shadow hover:brightness-110 transition text-sm font-semibold">
                Send Reset Link
            </button>
        </div>

        <p class="mt-6 text-center text-sm text-gray-600">
            Remember your password?
            <a href="{{ route('login') }}" class="text-[#8f6a10] font-semibold hover:underline">
                Sign in
            </a>
        </p>

    </form>

</x-guest-layout>
