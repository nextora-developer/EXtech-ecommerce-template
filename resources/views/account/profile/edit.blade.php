<x-app-layout>
    <div class="bg-[#f7f7f9] py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="text-sm text-gray-500 mb-4">
                <a href="{{ route('home') }}" class="hover:text-[#8f6a10]">Home</a>
                <span class="mx-1">/</span>
                <span class="text-gray-400">Profile</span>
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
