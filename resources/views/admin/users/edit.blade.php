@extends('admin.layouts.app')

@section('content')
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-semibold text-gray-900 tracking-tight">Edit Account</h1>
            <p class="text-sm text-gray-500 mt-1">Modify credentials and manage saved delivery locations.</p>
        </div>

        <a href="{{ route('admin.users.show', $user) }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200
                   text-sm font-semibold text-gray-600 hover:bg-gray-50 transition shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            <span>Back</span>
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-red-500">
                <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
            </svg>
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white border border-[#D4AF37]/18 rounded-2xl p-6 shadow-[0_18px_40px_rgba(0,0,0,0.06)]">
        
        <form id="user-form" method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="flex items-center gap-2 mb-4">
                <span class="w-1.5 h-6 bg-[#D4AF37] rounded-full"></span>
                <h2 class="font-bold text-gray-900">Basic Credentials</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="text-xs uppercase font-black tracking-widest text-gray-400">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="mt-1.5 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 font-medium">
                </div>

                <div>
                    <label class="text-xs uppercase font-black tracking-widest text-gray-400">IC Number</label>
                    <input type="text" name="ic_number" value="{{ old('ic_number', $user->ic_number) }}"
                        class="mt-1.5 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 font-medium"
                        placeholder="Optional">
                </div>

                <div>
                    <label class="text-xs uppercase font-black tracking-widest text-gray-400 text-[#8f6a10]">Change Password</label>
                    <input type="password" name="password"
                        class="mt-1.5 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30"
                        placeholder="Leave blank to keep current">
                </div>

                <div>
                    <label class="text-xs uppercase font-black tracking-widest text-gray-400">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="mt-1.5 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 font-medium">
                </div>

                <div>
                    <label class="text-xs uppercase font-black tracking-widest text-gray-400">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="mt-1.5 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 font-medium">
                </div>

                <div class="flex items-end">
                    <label class="flex items-center gap-3 cursor-pointer mb-3">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                            @checked(old('is_active', $user->is_active ?? true))>
                        <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#D4AF37]
                            after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                            after:bg-white after:h-5 after:w-5 after:rounded-full
                            after:transition-all peer-checked:after:translate-x-full">
                        </div>
                        <span class="text-xs font-bold text-gray-600 uppercase tracking-widest">Account Active</span>
                    </label>
                </div>
            </div>
        </form>

        {{-- Address Management --}}
        <div class="mt-10 border-t border-gray-100 pt-8">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-6 bg-[#D4AF37] rounded-full"></span>
                    <h2 class="font-bold text-gray-900 text-lg">Saved Locations</h2>
                </div>

                <a href="{{ route('admin.addresses.create', $user) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#D4AF37]/10 text-[#8f6a10] border border-[#D4AF37]/30 text-sm font-bold hover:bg-[#D4AF37]/20 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>New Address</span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($user->addresses as $address)
                    <div class="group flex justify-between border border-gray-100 rounded-2xl p-5 bg-gray-50/50 hover:bg-white hover:border-[#D4AF37]/40 transition shadow-sm">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <p class="font-bold text-gray-900 leading-tight">{{ $address->recipient_name }}</p>
                                @if ($address->is_default)
                                    <span class="px-2 py-0.5 text-[9px] font-black rounded bg-[#D4AF37] text-white uppercase tracking-tighter">Default</span>
                                @endif
                            </div>

                            <p class="text-sm text-gray-500 leading-relaxed">
                                {{ $address->address_line1 }}@if ($address->address_line2), {{ $address->address_line2 }} @endif <br>
                                {{ $address->postcode }} {{ $address->city }}, {{ $address->state }}
                            </p>
                        </div>

                        <div class="flex flex-col items-end justify-between ml-4">
                            <a href="{{ route('admin.addresses.edit', $address) }}"
                                class="text-xs font-black uppercase text-gray-400 hover:text-[#8f6a10] tracking-widest transition">Edit</a>

                            <form action="{{ route('admin.addresses.destroy', $address) }}" method="POST"
                                onsubmit="return confirm('Archive this address?');">
                                @csrf
                                @method('DELETE')
                                <button class="text-xs font-black uppercase text-red-300 hover:text-red-600 tracking-widest transition">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Footer Actions --}}
        <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-between">
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">
                Last activity: {{ $user->updated_at?->diffForHumans() ?? 'Never' }}
            </p>
            
            <div class="flex gap-3">
                <a href="{{ route('admin.users.show', $user) }}"
                    class="px-6 py-2.5 rounded-xl border border-gray-200 text-sm font-bold text-gray-500 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" form="user-form"
                    class="px-8 py-2.5 rounded-xl bg-[#D4AF37] text-white text-sm font-bold hover:bg-[#c29c2f] transition shadow-lg shadow-[#D4AF37]/20">
                    Save Profile Changes
                </button>
            </div>
        </div>
    </div>
@endsection