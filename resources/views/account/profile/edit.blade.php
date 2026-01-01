<x-app-layout>
    <div class="bg-[#f7f7f9] min-h-screen py-10">
        <div class="max-w-7xl5 mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-xs font-medium uppercase tracking-widest text-gray-400 mb-8">
                <a href="{{ route('home') }}" class="hover:text-[#8f6a10] transition-colors">Home</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span class="text-gray-900">Profile Setting</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

                {{-- 左侧 Sidebar --}}
                <aside class="lg:col-span-1">
                    @include('account.partials.sidebar')
                </aside>

                {{-- 右侧 Profile 内容 --}}
                <main class="lg:col-span-3 space-y-5">

                    {{-- <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                        <h2 class="text-lg font-semibold text-[#0A0A0C]">
                            Profile
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Update your account information and password.
                        </p>
                    </section> --}}

                    <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                        <div class="space-y-6">
                            @include('account.profile.partials.update-profile-information-form')
                        </div>
                    </section>

                    <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                        <div class="space-y-6">

                            @include('account.profile.partials.update-password-form')
                        </div>
                    </section>

                </main>
            </div>
        </div>
    </div>
</x-app-layout>
