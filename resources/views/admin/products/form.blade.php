@extends('admin.layouts.app')

@section('content')
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-semibold text-gray-900 tracking-tight">
                {{ $product->exists ? 'Edit Product' : 'New Product' }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Manage product details, descriptions, images, pricing & stock.
            </p>
        </div>

        <a href="{{ route('admin.products.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200
                  text-sm font-semibold text-gray-600 hover:bg-gray-50 transition shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            <span>Back</span>
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-red-500">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"
                    clip-rule="evenodd" />
            </svg>
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white border border-[#D4AF37]/18 rounded-2xl p-6 shadow-[0_18px_40px_rgba(0,0,0,0.06)] space-y-10">

        <form id="product-form" method="POST" enctype="multipart/form-data"
            action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
            class="space-y-10">

            @csrf
            @if ($product->exists)
                @method('PUT')
            @endif

            {{-- SECTION 1: Basic Info --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-1.5 h-6 bg-[#D4AF37] rounded-full"></span>
                    <h2 class="font-bold text-gray-900">Basic Information</h2>
                </div>

                {{-- ROW 1 --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="text-xs uppercase font-black tracking-widest text-gray-400">
                            Product Name
                        </label>
                        <input name="name" value="{{ old('name', $product->name) }}"
                            class="mt-1.5 w-full rounded-xl border-gray-200
                                      focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 font-medium"
                            placeholder="e.g. Gold Mug" required>
                    </div>

                    <div>
                        <label class="text-xs uppercase font-black tracking-widest text-gray-400">
                            Slug (optional)
                        </label>
                        <input name="slug" value="{{ old('slug', $product->slug) }}"
                            class="mt-1.5 w-full rounded-xl border-gray-200
                                      focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 font-medium"
                            placeholder="Auto-generated if empty">
                    </div>

                    <div>
                        <label class="text-xs uppercase font-black tracking-widest text-gray-400">
                            Category
                        </label>
                        <select name="category_id"
                            class="mt-1.5 w-full rounded-xl border-gray-200
                                       focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 font-medium">
                            <option value="">— None —</option>
                            @foreach ($categories as $c)
                                <option value="{{ $c->id }}" @selected(old('category_id', $product->category_id) == $c->id)>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- SECTION 2: Descriptions --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-1.5 h-6 bg-[#D4AF37] rounded-full"></span>
                    <h2 class="font-bold text-gray-900">Descriptions</h2>
                </div>

                {{-- ROW 2 --}}
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <div class="lg:col-span-4">
                        <label class="text-xs uppercase font-black tracking-widest text-gray-400">
                            Short Description
                        </label>
                        <textarea name="short_description" rows="4"
                            class="mt-1.5 w-full rounded-xl border-gray-200
                                         focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 text-sm"
                            placeholder="Short product description (max 255 chars)">{{ old('short_description', $product->short_description) }}</textarea>
                    </div>
                </div>

                {{-- ROW 3 --}}
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mt-4">
                    <div class="lg:col-span-4">
                        <label class="text-xs uppercase font-black tracking-widest text-gray-400">
                            Long Description
                        </label>

                        <input id="description" type="hidden" name="description"
                            value="{{ old('description', $product->description) }}">

                        <trix-editor input="description" class="trix-content border rounded-xl w-full mt-1.5"></trix-editor>
                    </div>
                </div>
            </div>

            {{-- SECTION 3: Additional Info (Specs) --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-1.5 h-6 bg-[#D4AF37] rounded-full"></span>
                    <h2 class="font-bold text-gray-900">Additional Info (Specs)</h2>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    @php
                        // 取旧输入（验证失败返回）或现有规格
                        $specs = old('specs', $product->specs ?? []);
                    @endphp

                    <div class="lg:col-span-4">
                        <div id="specs-wrapper" class="space-y-2">

                            @if (!empty($specs))
                                @foreach ($specs as $index => $row)
                                    <div class="flex gap-2 spec-row">
                                        <input type="text" name="specs[{{ $index }}][name]"
                                            class="form-input w-1/3" placeholder="Label (e.g. Material)"
                                            value="{{ $row['name'] ?? '' }}">

                                        <input type="text" name="specs[{{ $index }}][value]"
                                            class="form-input flex-1" placeholder="Value (e.g. 100% Cotton)"
                                            value="{{ $row['value'] ?? '' }}">

                                        <button type="button" class="px-2 text-xs text-red-500"
                                            onclick="removeSpecRow(this)">
                                            Remove
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                {{-- 默认至少一行 --}}
                                <div class="flex gap-2 spec-row">
                                    <input type="text" name="specs[0][name]" class="form-input w-1/3"
                                        placeholder="Label (e.g. Material)">

                                    <input type="text" name="specs[0][value]" class="form-input flex-1"
                                        placeholder="Value (e.g. 100% Cotton)">

                                    <button type="button" class="px-2 text-xs text-red-500" onclick="removeSpecRow(this)">
                                        Remove
                                    </button>
                                </div>
                            @endif

                        </div>

                        <button type="button"
                            class="mt-2 inline-flex items-center px-3 py-1 text-xs border rounded-lg hover:bg-gray-50"
                            onclick="addSpecRow()">
                            + Add Spec
                        </button>
                    </div>
                </div>
            </div>

            {{-- SECTION 4: Product Images --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-1.5 h-6 bg-[#D4AF37] rounded-full"></span>
                    <h2 class="font-bold text-gray-900">Product Images</h2>
                </div>

                <div class="border rounded-xl p-4">
                    <label class="text-xs uppercase font-black tracking-widest text-gray-400 mb-2 block">
                        Gallery
                    </label>

                    <div class="flex flex-col gap-3">
                        {{-- Preview --}}
                        <div id="imagePreviewContainer" class="flex flex-wrap gap-3">
                            @if (!empty($product->images) && count($product->images))
                                @foreach ($product->images as $image)
                                    <div
                                        class="h-24 w-24 rounded-lg bg-gray-100 border overflow-hidden flex items-center justify-center">
                                        <img src="{{ asset('storage/' . $image->path) }}"
                                            class="h-full w-full object-cover" alt="Preview">
                                    </div>
                                @endforeach
                            @elseif ($product->image)
                                <div
                                    class="h-24 w-24 rounded-lg bg-gray-100 border overflow-hidden flex items-center justify-center">
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                        class="h-full w-full object-cover" alt="Preview">
                                </div>
                            @else
                                <div class="h-24 w-24 rounded-lg bg-gray-100 border overflow-hidden flex items-center justify-center"
                                    id="imagePlaceholder">
                                    <svg class="h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 16.5V6.75A2.25 2.25 0 015.25 4.5h13.5A2.25 2.25 0 0121 6.75v9.75M3 16.5l4.5-4.5a2.25 2.25 0 013.182 0l4.318 4.318a2.25 2.25 0 003.182 0L21 13.5" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- Upload buttons --}}
                        <div>
                            <label
                                class="inline-flex items-center px-3 py-2 rounded-lg border border-gray-300 bg-white cursor-pointer text-sm">
                                Choose files
                                <input id="imageInput" type="file" name="images[]" class="hidden" accept="image/*"
                                    multiple>
                            </label>

                            <button type="button" id="imageClearBtn"
                                class="px-3 py-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-sm">
                                Clear
                            </button>
                        </div>

                        @error('images.*')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- SECTION 5: Pricing & Variants --}}
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-[#D4AF37] rounded-full"></span>
                        <h2 class="font-bold text-gray-900">Pricing & Stock</h2>
                    </div>

                    <label class="inline-flex items-center gap-2 text-sm cursor-pointer">
                        <input type="checkbox" name="has_variants" value="1" class="rounded border-gray-300"
                            @checked(old('has_variants', $product->has_variants ?? false))>
                        <span class="text-gray-700">Use variations</span>
                    </label>
                </div>

                <div class="border rounded-xl p-5 space-y-6">
                    {{-- Simple price / stock --}}
                    <div id="simplePriceStock" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs uppercase font-black tracking-widest text-gray-400">
                                Price
                            </label>
                            <input name="price" value="{{ old('price', $product->price) }}"
                                class="mt-1.5 w-full rounded-xl border-gray-200
                                          focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 text-sm"
                                placeholder="e.g. 29.90">
                        </div>

                        <div>
                            <label class="text-xs uppercase font-black tracking-widest text-gray-400">
                                Stock
                            </label>
                            <input name="stock" value="{{ old('stock', $product->stock) }}"
                                class="mt-1.5 w-full rounded-xl border-gray-200
                                          focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 text-sm"
                                placeholder="e.g. 50">
                        </div>
                    </div>

                    {{-- Variants Wrapper --}}
                    <div id="variantsWrapper" class="hidden">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">Variations setup</p>
                                    <p class="text-xs text-gray-500">
                                        Example: Variation 1 = Color (Red, Orange), Variation 2 = Size (6, 8)
                                    </p>
                                </div>
                                <button type="button" id="addVariationGroupBtn"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#D4AF37] text-white text-sm hover:bg-[#c29c2f]">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                    <span>Add variation</span>
                                </button>
                            </div>

                            <div id="variationGroups" class="space-y-3">
                                {{-- 默认先给一个 Variation 1 --}}
                                <div class="variation-group border rounded-lg p-3 space-y-3 bg-gray-50" data-index="0">
                                    <div class="flex items-center justify-between">
                                        <p class="font-medium text-sm">
                                            Variation <span class="variation-order">1</span>
                                        </p>
                                        <button type="button" class="text-xs text-red-500 hover:underline"
                                            data-remove-variation-group>
                                            Remove
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div>
                                            <label class="form-label text-xs">Name</label>
                                            <input data-vg-name class="form-input py-1.5 text-sm"
                                                placeholder="e.g. Color">
                                        </div>
                                        <div>
                                            <label class="form-label text-xs">Options (comma separated)</label>
                                            <input data-vg-values class="form-input py-1.5 text-sm"
                                                placeholder="e.g. Red, Orange">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Template: Variation group --}}
                            <template id="variationGroupTemplate">
                                <div class="variation-group border rounded-lg p-3 space-y-3 bg-gray-50"
                                    data-index="__INDEX__">
                                    <div class="flex items-center justify-between">
                                        <p class="font-medium text-sm">
                                            Variation <span class="variation-order"></span>
                                        </p>
                                        <button type="button" class="text-xs text-red-500 hover:underline"
                                            data-remove-variation-group>
                                            Remove
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div>
                                            <label class="form-label text-xs">Name</label>
                                            <input data-vg-name class="form-input py-1.5 text-sm" placeholder="e.g. Size">
                                        </div>
                                        <div>
                                            <label class="form-label text-xs">Options (comma separated)</label>
                                            <input data-vg-values class="form-input py-1.5 text-sm"
                                                placeholder="e.g. S, M, L">
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <div class="flex justify-end">
                                <button type="button" id="generateFromVariationsBtn"
                                    class="px-3 py-1.5 rounded-xl bg-[#D4AF37]/15 text-[#8f6a10] text-sm border border-[#D4AF37]/30
                                               hover:bg-[#D4AF37]/20 transition">
                                    Generate variation list
                                </button>
                            </div>
                        </div>

                        {{-- Variation list --}}
                        <div class="space-y-2 mt-4">
                            <p class="font-medium text-gray-900 text-sm">Variation list</p>
                            <div class="overflow-x-auto border rounded-xl">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-50">
                                        <tr class="text-left text-gray-500">
                                            <th class="py-2 px-3 w-32">SKU</th>
                                            <th class="py-2 px-3 w-56">Variant label</th>
                                            <th class="py-2 px-3 w-56">Variant value</th>
                                            <th class="py-2 px-3 w-32">Price</th>
                                            <th class="py-2 px-3 w-28">Stock</th>
                                            <th class="py-2 px-3 w-12 text-right"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="variantsBody" class="divide-y">
                                        @php
                                            $oldVariants = old('variants');
                                        @endphp

                                        @if ($oldVariants)
                                            @foreach ($oldVariants as $i => $variant)
                                                <tr class="bg-white">
                                                    <td class="py-2 px-3">
                                                        <input name="variants[{{ $i }}][sku]"
                                                            value="{{ $variant['sku'] ?? '' }}"
                                                            class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 text-sm"
                                                            placeholder="Optional">
                                                    </td>
                                                    <td class="py-2 px-3">
                                                        <input name="variants[{{ $i }}][label]"
                                                            value="{{ $variant['label'] ?? '' }}"
                                                            class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 text-sm"
                                                            placeholder="e.g. Color / Size">
                                                    </td>
                                                    <td class="py-2 px-3">
                                                        <input name="variants[{{ $i }}][value]"
                                                            value="{{ $variant['value'] ?? '' }}"
                                                            class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 text-sm"
                                                            placeholder="e.g. Red / 6">
                                                    </td>
                                                    <td class="py-2 px-3">
                                                        <input type="number" step="0.01" min="0"
                                                            name="variants[{{ $i }}][price]"
                                                            value="{{ $variant['price'] ?? '' }}"
                                                            class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 text-sm text-right"
                                                            placeholder="29.90">
                                                    </td>
                                                    <td class="py-2 px-3">
                                                        <input type="number" min="0"
                                                            name="variants[{{ $i }}][stock]"
                                                            value="{{ $variant['stock'] ?? '' }}"
                                                            class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 text-sm text-right"
                                                            placeholder="10">
                                                    </td>
                                                    <td class="py-2 px-3 text-right align-middle">
                                                        <button type="button"
                                                            class="text-xs text-red-500 hover:underline"
                                                            data-remove-variant>
                                                            Remove
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @elseif(isset($product) && $product->exists && $product->variants->isNotEmpty())
                                            @foreach ($product->variants as $i => $variant)
                                                <tr class="bg-white">
                                                    <td class="py-2 px-3">
                                                        <input name="variants[{{ $i }}][sku]"
                                                            value="{{ $variant->sku }}"
                                                            class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 text-sm"
                                                            placeholder="Optional">
                                                    </td>
                                                    <td class="py-2 px-3">
                                                        <input name="variants[{{ $i }}][label]"
                                                            value="{{ $variant->label }}"
                                                            class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 text-sm"
                                                            placeholder="e.g. Color / Size">
                                                    </td>
                                                    <td class="py-2 px-3">
                                                        <input name="variants[{{ $i }}][value]"
                                                            value="{{ $variant->value }}"
                                                            class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 text-sm"
                                                            placeholder="e.g. Red / 6">
                                                    </td>
                                                    <td class="py-2 px-3">
                                                        <input type="number" step="0.01" min="0"
                                                            name="variants[{{ $i }}][price]"
                                                            value="{{ $variant->price }}"
                                                            class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 text-sm text-right"
                                                            placeholder="29.90">
                                                    </td>
                                                    <td class="py-2 px-3">
                                                        <input type="number" min="0"
                                                            name="variants[{{ $i }}][stock]"
                                                            value="{{ $variant->stock }}"
                                                            class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 text-sm text-right"
                                                            placeholder="10">
                                                    </td>
                                                    <td class="py-2 px-3 text-right align-middle">
                                                        <button type="button"
                                                            class="text-xs text-red-500 hover:underline"
                                                            data-remove-variant>
                                                            Remove
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            {{-- 手动新增一行 variant 用的 template --}}
                            <template id="variantRowTemplate">
                                <tr class="bg-white">
                                    <td class="py-2 px-3">
                                        <input data-name="sku"
                                            class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 text-sm"
                                            placeholder="Optional">
                                    </td>
                                    <td class="py-2 px-3">
                                        <input data-name="label"
                                            class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 text-sm"
                                            placeholder="e.g. Color / Size">
                                    </td>
                                    <td class="py-2 px-3">
                                        <input data-name="value"
                                            class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 text-sm"
                                            placeholder="e.g. Red / 6">
                                    </td>
                                    <td class="py-2 px-3">
                                        <input data-name="price" type="number" step="0.01" min="0"
                                            class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 text-sm text-right"
                                            placeholder="29.90">
                                    </td>
                                    <td class="py-2 px-3">
                                        <input data-name="stock" type="number" min="0"
                                            class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 text-sm text-right"
                                            placeholder="10">
                                    </td>
                                    <td class="py-2 px-3 text-right align-middle">
                                        <button type="button" class="text-xs text-red-500 hover:underline"
                                            data-remove-variant>
                                            Remove
                                        </button>
                                    </td>
                                </tr>
                            </template>

                            <div class="flex justify-end pt-2">
                                <button type="button" id="addVariantRow"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#D4AF37] text-white text-sm hover:bg-[#c29c2f]">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                    <span>Add row manually</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 6: Toggles + Actions --}}
            <div class="pt-4 border-t border-gray-100 flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4 flex-wrap">
                    {{-- Digital toggle --}}
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_digital" value="1" class="sr-only peer"
                            @checked(old('is_digital', $product->is_digital ?? false))>
                        <div
                            class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-blue-500 relative
                                   after:content-['']
                                   after:absolute after:top-[2px] after:left-[2px]
                                   after:bg-white after:h-5 after:w-5 after:rounded-full
                                   peer-checked:after:translate-x-full after:transition-all">
                        </div>
                        <span class="text-sm text-gray-600">Digital Product</span>
                    </label>

                    {{-- Active toggle --}}
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                            @checked(old('is_active', $product->is_active ?? true))>
                        <div
                            class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#D4AF37] relative
                                   after:content-['']
                                   after:absolute after:top-[2px] after:left-[2px]
                                   after:bg-white after:h-5 after:w-5 after:rounded-full
                                   peer-checked:after:translate-x-full after:transition-all">
                        </div>
                        <span class="text-sm text-gray-600">Active</span>
                    </label>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('admin.products.index') }}"
                        class="px-6 py-2.5 rounded-xl border border-gray-200 text-sm font-bold text-gray-500 hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-8 py-2.5 rounded-xl bg-[#D4AF37] text-white text-sm font-bold hover:bg-[#c29c2f] transition shadow-lg shadow-[#D4AF37]/20">
                        Save Product
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection



@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // =========================
            // Image preview logic
            // =========================
            const input = document.getElementById('imageInput');
            const previewContainer = document.getElementById('imagePreviewContainer');
            const fileNameText = document.getElementById('imageFileName');
            const fileMetaText = document.getElementById('imageFileMeta');
            const clearBtn = document.getElementById('imageClearBtn');

            function resetPreview() {
                input.value = '';
                previewContainer.innerHTML = '';

                const placeholderDiv = document.createElement('div');
                placeholderDiv.className =
                    'h-24 w-24 rounded-lg bg-gray-100 border overflow-hidden flex items-center justify-center';
                placeholderDiv.innerHTML = `
                <svg class="h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 16.5V6.75A2.25 2.25 0 015.25 4.5h13.5A2.25 2.25 0 0121 6.75v9.75M3 16.5l4.5-4.5a2.25 2.25 0 013.182 0l4.318 4.318a2.25 2.25 0 003.182 0L21 13.5" />
                </svg>
            `;
                previewContainer.appendChild(placeholderDiv);

                fileNameText.textContent = 'No image selected';
                fileMetaText.textContent = 'PNG / JPG, up to 2MB each. You can select multiple files.';
            }

            input.addEventListener('change', (e) => {
                const files = Array.from(e.target.files || []);

                if (!files.length) {
                    resetPreview();
                    return;
                }

                previewContainer.innerHTML = '';

                files.forEach((file) => {
                    const reader = new FileReader();

                    reader.onload = (event) => {
                        const wrapper = document.createElement('div');
                        wrapper.className =
                            'h-24 w-24 rounded-lg bg-gray-100 border overflow-hidden flex items-center justify-center';

                        const img = document.createElement('img');
                        img.src = event.target.result;
                        img.alt = 'Preview';
                        img.className = 'h-full w-full object-cover';

                        wrapper.appendChild(img);
                        previewContainer.appendChild(wrapper);
                    };

                    reader.readAsDataURL(file);
                });

                fileNameText.textContent = `${files.length} image(s) selected`;
                fileMetaText.textContent = files.map(f => f.name).join(', ');
            });

            clearBtn.addEventListener('click', () => {
                resetPreview();
            });


            // =========================
            // Variants: show/hide
            // =========================
            const hasVariantsCheckbox = document.querySelector('input[name="has_variants"]');
            const simplePriceStock = document.getElementById('simplePriceStock');
            const variantsWrapper = document.getElementById('variantsWrapper');

            const toggleVariantUI = () => {
                if (!hasVariantsCheckbox || !simplePriceStock || !variantsWrapper) return;

                if (hasVariantsCheckbox.checked) {
                    simplePriceStock.classList.add('opacity-40', 'pointer-events-none');
                    variantsWrapper.classList.remove('hidden');
                } else {
                    simplePriceStock.classList.remove('opacity-40', 'pointer-events-none');
                    variantsWrapper.classList.add('hidden');
                }
            };

            if (hasVariantsCheckbox) {
                toggleVariantUI();
                hasVariantsCheckbox.addEventListener('change', toggleVariantUI);
            }

            // =========================
            // Variants: add/remove rows 手动
            // =========================
            const variantsBody = document.getElementById('variantsBody');
            const addVariantBtn = document.getElementById('addVariantRow');
            const variantTemplate = document.getElementById('variantRowTemplate');

            const bindRemoveVariantButtons = () => {
                if (!variantsBody) return;
                variantsBody.querySelectorAll('[data-remove-variant]').forEach(btn => {
                    btn.onclick = () => {
                        const row = btn.closest('tr');
                        if (row) row.remove();
                    };
                });
            };

            if (addVariantBtn && variantTemplate && variantsBody) {
                addVariantBtn.addEventListener('click', () => {
                    const index = variantsBody.children.length;
                    const clone = variantTemplate.content.cloneNode(true);

                    clone.querySelectorAll('[data-name]').forEach((input) => {
                        const base = input.getAttribute('data-name');
                        input.name = `variants[${index}][${base}]`;
                    });

                    variantsBody.appendChild(clone);
                    bindRemoveVariantButtons();
                });
            }

            bindRemoveVariantButtons();

            // =========================
            // Variation Groups (Shopee style)
            // =========================
            const variationGroupsWrapper = document.getElementById('variationGroups');
            const variationGroupTemplate = document.getElementById('variationGroupTemplate');
            const addGroupBtn = document.getElementById('addVariationGroupBtn');
            const generateBtn = document.getElementById('generateFromVariationsBtn');

            const refreshVariationOrderLabels = () => {
                if (!variationGroupsWrapper) return;
                const groups = variationGroupsWrapper.querySelectorAll('.variation-group');
                groups.forEach((g, idx) => {
                    const span = g.querySelector('.variation-order');
                    if (span) span.textContent = idx + 1;
                });
            };

            const bindRemoveVariationGroups = () => {
                if (!variationGroupsWrapper) return;
                variationGroupsWrapper.querySelectorAll('[data-remove-variation-group]').forEach(btn => {
                    btn.onclick = () => {
                        const card = btn.closest('.variation-group');
                        if (card) card.remove();
                        refreshVariationOrderLabels();
                    };
                });
            };

            if (addGroupBtn && variationGroupTemplate && variationGroupsWrapper) {
                addGroupBtn.addEventListener('click', () => {
                    const existing = variationGroupsWrapper.querySelectorAll('.variation-group').length;

                    // 限制最多 2 个（像 Shopee）
                    if (existing >= 2) {
                        alert('最多只能有 2 个 Variation（例如 Color 和 Size）。');
                        return;
                    }

                    const index = existing;
                    const html = variationGroupTemplate.innerHTML.replace(/__INDEX__/g, index);
                    const wrapper = document.createElement('div');
                    wrapper.innerHTML = html.trim();
                    const node = wrapper.firstChild;

                    variationGroupsWrapper.appendChild(node);
                    refreshVariationOrderLabels();
                    bindRemoveVariationGroups();
                });

                bindRemoveVariationGroups();
                refreshVariationOrderLabels();
            }

            // 笛卡儿积：生成所有组合
            const cartesian = (arrays) => {
                if (!arrays.length) return [];
                return arrays.reduce((acc, curr) => {
                    const res = [];
                    acc.forEach(a => {
                        curr.forEach(b => {
                            res.push(a.concat([b]));
                        });
                    });
                    return res;
                }, [
                    []
                ]);
            };

            // 点击生成 Variation List
            if (generateBtn && variationGroupsWrapper && variantTemplate && variantsBody) {
                generateBtn.addEventListener('click', () => {
                    const groups = [];
                    variationGroupsWrapper.querySelectorAll('.variation-group').forEach(group => {
                        const nameInput = group.querySelector('[data-vg-name]');
                        const valuesInput = group.querySelector('[data-vg-values]');
                        const name = nameInput?.value.trim();
                        const values = valuesInput?.value.trim();

                        if (!name || !values) return;

                        const vals = values.split(',')
                            .map(v => v.trim())
                            .filter(Boolean);

                        if (!vals.length) return;

                        groups.push({
                            name,
                            values: vals
                        });
                    });

                    if (!groups.length) {
                        alert('请先设置至少一个 Variation（Name + Options）。');
                        return;
                    }

                    // 生成所有组合
                    const valueArrays = groups.map(g => g.values);
                    const combos = cartesian(valueArrays);

                    // 清空旧的 variants
                    variantsBody.innerHTML = '';

                    combos.forEach((combo, idx) => {
                        const clone = variantTemplate.content.cloneNode(true);

                        const label = groups.map(g => g.name).join(' / '); // Color / Size
                        const value = combo.join(' / '); // Red / 6

                        clone.querySelectorAll('[data-name]').forEach((input) => {
                            const base = input.getAttribute('data-name');
                            input.name = `variants[${idx}][${base}]`;

                            if (base === 'label') input.value = label;
                            if (base === 'value') input.value = value;
                        });

                        variantsBody.appendChild(clone);
                    });

                    bindRemoveVariantButtons();
                    alert('已根据 Variations 生成组合列表，请填写价格和库存。');
                });
            }
        });
    </script>

    <script>
        function addSpecRow() {
            const wrapper = document.getElementById('specs-wrapper');
            const index = wrapper.querySelectorAll('.spec-row').length;

            wrapper.insertAdjacentHTML('beforeend', `
            <div class="flex gap-2 spec-row">
                <input type="text"
                    name="specs[${index}][name]"
                    class="form-input w-1/3"
                    placeholder="Label (e.g. Material)">

                <input type="text"
                    name="specs[${index}][value]"
                    class="form-input flex-1"
                    placeholder="Value (e.g. 100% Cotton)">

                <button type="button"
                    class="px-2 text-xs text-red-500"
                    onclick="removeSpecRow(this)">
                    Remove
                </button>
            </div>
        `);
        }

        function removeSpecRow(btn) {
            const row = btn.closest('.spec-row');
            if (!row) return;
            row.remove();
        }
    </script>
@endpush
