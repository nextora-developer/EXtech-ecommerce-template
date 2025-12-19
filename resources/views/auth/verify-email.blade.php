<x-guest-layout>

    <form method="POST" action="{{ route('verification.send') }}"
        class="bg-white border border-[#D4AF37]/25 shadow-md rounded-2xl px-6 py-8 max-w-md mx-auto">

        @csrf

        <h2 class="text-center text-2xl font-semibold text-[#0A0A0C] mb-4">
            Verify Your Email
        </h2>

        <p class="text-sm text-gray-600 text-center mb-6">
            Thanks for signing up! Before continuing, please verify your email by clicking the link we’ve just sent.
            Didn’t get it? You can request another below.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div
                class="mb-4 px-4 py-3 rounded-lg bg-[#FFF9E6] text-[#8f6a10] border border-[#D4AF37]/40 text-sm text-center">
                A new verification link has been sent to your email.
            </div>
        @endif

        <!-- Buttons -->
        <div class="flex items-center justify-between">

            {{-- Back --}}
            <a href="{{ url()->previous() }}"
                class="inline-flex items-center px-4 py-2 rounded-xl border border-[#D4AF37]/40 text-[#8f6a10] hover:border-[#D4AF37] hover:bg-[#FFF9E6] transition text-sm font-medium">
                ← Back
            </a>

            {{-- Resend --}}
            <button type="submit"
                class="inline-flex items-center px-6 py-2 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#8f6a10] text-white shadow hover:brightness-110 transition text-sm font-semibold">
                Resend Email
            </button>
        </div>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}" class="mt-6 text-center">
            @csrf
            <button type="submit" class="text-sm text-[#8f6a10] hover:underline font-medium">
                Log Out
            </button>
        </form>

    </form>

</x-guest-layout>
