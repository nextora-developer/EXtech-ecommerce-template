<header>
    <h2 class="text-lg font-semibold text-[#0A0A0C]">
        {{ __('Profile Information') }}
    </h2>

    <p class="mt-1 text-sm text-gray-500">
        {{ __("Update your account's profile information and email address.") }}
    </p>
</header>

{{-- 用于重发验证邮件 --}}
<form id="send-verification" method="post" action="{{ route('verification.send') }}" class="mt-4">
    @csrf
</form>

<form method="post" action="{{ route('account.profile.update') }}" class="mt-5 space-y-5">
    @csrf
    @method('patch')

    {{-- Name + Email --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        {{-- Name --}}
        <div>
            <label for="name" class="block text-sm text-gray-500 mb-1">
                {{ __('Name') }}
            </label>

            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                autocomplete="name" placeholder="Enter your full name"
                class="w-full rounded-xl border-gray-200 text-base px-3 py-3
                   focus:border-[#D4AF37] focus:ring-[#D4AF37]/30" />

            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- IC Number --}}
        <div>
            <label for="ic_number" class="block text-sm text-gray-500 mb-1">
                {{ __('IC Number') }}
            </label>

            <input id="ic_number" name="ic_number" type="text" value="{{ old('ic_number', $user->ic_number) }}"
                placeholder="e.g. 990101-01-1234"
                class="w-full rounded-xl border-gray-200 text-base px-3 py-3
                   focus:border-[#D4AF37] focus:ring-[#D4AF37]/30" />

            @error('ic_number')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>


    {{-- Phone + Email --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        {{-- Phone --}}
        <div>
            <label for="phone" class="block text-sm text-gray-500 mb-1">
                {{ __('Phone Number') }}
            </label>

            <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}"
                placeholder="e.g. 0182222507"
                class="w-full rounded-xl border-gray-200 text-base px-3 py-3
                   focus:border-[#D4AF37] focus:ring-[#D4AF37]/30" />

            @error('phone')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm text-gray-500 mb-1">
                {{ __('Email') }}
            </label>

            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                autocomplete="username" placeholder="Enter your email address"
                class="w-full rounded-xl border-gray-200 text-base px-3 py-3
                   focus:border-[#D4AF37] focus:ring-[#D4AF37]/30" />

            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror


            {{-- 邮箱未验证提示 --}}
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="mt-2 text-sm text-gray-700 space-y-1">
                    <p>
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="underline font-medium text-[#8f6a10] hover:text-[#D4AF37] rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4AF37]/60 text-sm">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- 底部按钮 --}}
    <div class="flex items-center gap-4 pt-3">
        <button type="submit"
            class="px-7 py-3 rounded-full bg-[#D4AF37] text-white text-base font-semibold shadow hover:brightness-110 transition">
            {{ __('Save') }}
        </button>

        @if (session('status') === 'profile-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-[#8f6a10]">
                {{ __('Saved.') }}
            </p>
        @endif
    </div>
</form>
