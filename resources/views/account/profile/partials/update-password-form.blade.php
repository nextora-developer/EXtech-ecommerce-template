<header>
    <h2 class="text-lg font-semibold text-[#0A0A0C]">
        {{ __('Update Password') }}
    </h2>

    <p class="mt-1 text-sm text-gray-500">
        {{ __('Ensure your account uses a strong and secure password.') }}
    </p>
</header>

<form method="post" action="{{ route('password.update') }}" class="mt-5 space-y-5">
    @csrf
    @method('put')

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        {{-- Current password --}}
        <div>
            <label for="update_password_current_password" class="block text-sm text-gray-500 mb-1">
                {{ __('Current Password') }}
            </label>

            <input id="update_password_current_password" name="current_password" type="password"
                autocomplete="current-password"
                class="w-full rounded-xl border-gray-200 text-base px-3 py-3
                   focus:border-[#D4AF37] focus:ring-[#D4AF37]/30" />

            @error('current_password', 'updatePassword')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>


        <div>
            <label for="update_password_password" class="block text-sm text-gray-500 mb-1">
                {{ __('New Password') }}
            </label>

            <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                class="w-full rounded-xl border-gray-200 text-base px-3 py-3
                       focus:border-[#D4AF37] focus:ring-[#D4AF37]/30" />

            @error('password', 'updatePassword')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm text-gray-500 mb-1">
                {{ __('Confirm Password') }}
            </label>

            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                autocomplete="new-password"
                class="w-full rounded-xl border-gray-200 text-base px-3 py-3
                       focus:border-[#D4AF37] focus:ring-[#D4AF37]/30" />

            @error('password_confirmation', 'updatePassword')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- buttons --}}
    <div class="flex items-center gap-4 pt-3">
        <button type="submit"
            class="px-7 py-3 rounded-full bg-[#D4AF37] text-white text-base font-semibold shadow hover:brightness-110 transition">
            {{ __('Save') }}
        </button>

        @if (session('status') === 'password-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-[#8f6a10]">
                {{ __('Saved.') }}
            </p>
        @endif
    </div>
</form>
