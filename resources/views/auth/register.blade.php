<x-guest-layout>

    <form method="POST" action="{{ route('register') }}"
        class="bg-white border border-[#D4AF37]/25 shadow-md rounded-2xl px-6 py-8 max-w-md mx-auto">

        @csrf

        <h2 class="text-center text-2xl font-semibold text-[#0A0A0C] mb-6">
            Create an Account
        </h2>

        <!-- Name -->
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-[#0A0A0C] mb-1">
                Full Name
            </label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                autocomplete="name"
                class="w-full rounded-xl border border-gray-300 focus:border-[#D4AF37] focus:ring-[#D4AF37] text-gray-800" />
            @error('name')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-[#0A0A0C] mb-1">
                Email Address
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                autocomplete="username"
                class="w-full rounded-xl border border-gray-300 focus:border-[#D4AF37] focus:ring-[#D4AF37] text-gray-800" />
            @error('email')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-[#0A0A0C] mb-1">
                Password
            </label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                class="w-full rounded-xl border border-gray-300 focus:border-[#D4AF37] focus:ring-[#D4AF37] text-gray-800" />
            @error('password')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-[#0A0A0C] mb-1">
                Confirm Password
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                autocomplete="new-password"
                class="w-full rounded-xl border border-gray-300 focus:border-[#D4AF37] focus:ring-[#D4AF37] text-gray-800" />
            @error('password_confirmation')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Buttons -->
        <div class="flex flex-col gap-4 mt-6">

            {{-- Register (Full Width) --}}
            <button type="submit"
                class="w-full inline-flex items-center justify-center px-6 py-3 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#8f6a10] text-white shadow hover:brightness-110 transition text-base font-semibold">
                Register
            </button>

            {{-- Back --}}
            <a href="{{ url()->previous() }}"
                class="w-full inline-flex items-center justify-center px-5 py-3 rounded-xl border border-[#D4AF37]/40 text-[#8f6a10] hover:border-[#D4AF37] hover:bg-[#FFF9E6] transition text-sm font-medium">
                ← Back
            </a>

        </div>


        <p class="mt-6 text-center text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" class="text-[#8f6a10] font-semibold hover:underline">
                Sign in
            </a>
        </p>

    </form>

</x-guest-layout>
