@extends('admin.layouts.app')

@section('content')
    <form id="productForm" method="POST" enctype="multipart/form-data"
        action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}">
        @csrf
        @if ($product->exists)
            @method('PUT')
        @endif

        <div
            class="sticky top-0 z-30 flex items-center justify-between bg-gray-50/90 backdrop-blur-sm py-4 mb-6 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    {{ $product->exists ? 'Edit Product' : 'Create Product' }}
                </h1>
                <p class="text-sm text-gray-500">Manage your product details, media, and inventory.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.products.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-[#D4AF37] hover:bg-[#c29c2f] text-white font-bold rounded-xl shadow-md transition-all active:scale-95">
                    Save Product
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800">General Information</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                            <input name="name" value="{{ old('name', $product->name) }}"
                                class="w-full rounded-xl border-gray-300 focus:border-[#D4AF37] focus:ring focus:ring-[#D4AF37]/20 transition"
                                placeholder="e.g. Signature Gold Mug" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">URL Slug</label>
                                <div class="flex">
                                    <span
                                        class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">/product/</span>
                                    <input name="slug" value="{{ old('slug', $product->slug) }}"
                                        class="w-full rounded-r-xl border-gray-300 focus:border-[#D4AF37] focus:ring-[#D4AF37]/20 transition text-sm"
                                        placeholder="auto-generated">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                <select name="category_id"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#D4AF37] focus:ring-[#D4AF37]/20 transition text-sm">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $c)
                                        <option value="{{ $c->id }}" @selected(old('category_id', $product->category_id) == $c->id)>{{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800">Detailed Description</h2>
                    <input id="description" type="hidden" name="description"
                        value="{{ old('description', $product->description) }}">
                    <trix-editor input="description"
                        class="trix-content min-h-[250px] border border-gray-300 rounded-xl p-2 focus:ring-1 focus:ring-[#D4AF37]"></trix-editor>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Product Variants</h2>
                            <p class="text-xs text-gray-500">Configure multiple options like size, color, or material.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="has_variants" value="1" class="sr-only peer"
                                id="variantToggle" @checked(old('has_variants', $product->has_variants))>
                            <div
                                class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-[#D4AF37] after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all">
                            </div>
                            <span class="ml-3 text-sm font-medium text-gray-700">Enable Variants</span>
                        </label>
                    </div>

                    <div id="variantSection"
                        class="{{ old('has_variants', $product->has_variants) ? '' : 'hidden' }} space-y-6">
                        <div id="variationGroups" class="space-y-4">
                        </div>

                        <button type="button" onclick="addVariationGroup()"
                            class="text-[#D4AF37] hover:text-[#b3922d] text-sm font-bold flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round" />
                            </svg>
                            Add another option
                        </button>

                        <div class="pt-4 border-t">
                            <div id="variantsTable" class="overflow-x-auto rounded-xl border border-gray-100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">

                <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                    <h2 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Organization & Status</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">Published</span>
                            <input type="checkbox" name="is_active" @checked(old('is_active', $product->is_active ?? true))
                                class="rounded text-[#D4AF37] focus:ring-[#D4AF37]">
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">Digital Product</span>
                            <input type="checkbox" name="is_digital" @checked(old('is_digital', $product->is_digital ?? false))
                                class="rounded text-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800">Media</h2>
                    <div class="grid grid-cols-2 gap-3 mb-4" id="previewGrid">
                    </div>
                    <label
                        class="group relative flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-2xl cursor-pointer hover:border-[#D4AF37] hover:bg-gray-50 transition-all">
                        <div
                            class="flex flex-col items-center justify-center pt-5 pb-6 text-gray-400 group-hover:text-[#D4AF37]">
                            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="text-xs">PNG, JPG up to 10MB</p>
                        </div>
                        <input type="file" name="images[]" class="hidden" multiple accept="image/*" id="imageInput">
                    </label>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800">Specifications</h2>
                    <div id="specs-wrapper" class="space-y-2">
                    </div>
                    <button type="button" onclick="addSpecRow()"
                        class="mt-4 w-full py-2 text-xs font-bold text-gray-500 border border-gray-200 rounded-lg hover:bg-gray-50">
                        + Add Attribute
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection
