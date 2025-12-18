@extends('admin.layouts.app')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">
        {{ $product->exists ? 'Edit Product' : 'New Product' }}
    </h1>

    <form method="POST" enctype="multipart/form-data"
        action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
        class="bg-white rounded-2xl border border-gray-200 p-6 w-full mx-auto">

        @csrf
        @if ($product->exists)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT: Main info --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Product name --}}
                <div>
                    <label class="form-label">Product name</label>
                    <input name="name" value="{{ old('name', $product->name) }}" class="form-input"
                        placeholder="e.g. BRIF Gold Mug">
                </div>

                {{-- Category --}}
                <div>
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-input">
                        <option value="">— None —</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}" @selected(old('category_id', $product->category_id) == $c->id)>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Slug --}}
                <div>
                    <label class="form-label">
                        Slug <span class="text-gray-400">(optional)</span>
                    </label>
                    <input name="slug" value="{{ old('slug', $product->slug) }}" class="form-input"
                        placeholder="auto-generated if empty">
                </div>

                {{-- Description --}}
                <div>
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4" class="form-input" placeholder="Short product description">{{ old('description', $product->description) }}</textarea>
                </div>

                {{-- Price / Stock / Variants --}}
                {{-- Price / Stock / Variants --}}
                {{-- Price / Stock / Variants --}}
                <div class="space-y-5">

                    {{-- 简单价格 & 是否使用 variants --}}
                    <div class="border rounded-xl p-4 space-y-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="font-medium text-gray-900">Pricing & Stock</p>
                                <p class="text-xs text-gray-500">
                                    Use a single price & stock, or enable variations (e.g. Color + Size).
                                </p>
                            </div>

                            <div class="text-right">
                                <label class="inline-flex items-center gap-2 text-sm">
                                    <input type="checkbox" name="has_variants" value="1"
                                        class="rounded border-gray-300" @checked(old('has_variants', $product->has_variants ?? false))>
                                    <span>Use variations</span>
                                </label>
                            </div>
                        </div>

                        {{-- 简单模式：没有 variants 时 --}}
                        <div id="simplePriceStock" class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            <div>
                                <label class="form-label">Price</label>
                                <input name="price" value="{{ old('price', $product->price) }}" class="form-input"
                                    placeholder="e.g. 29.90">
                            </div>

                            <div>
                                <label class="form-label">Stock</label>
                                <input name="stock" value="{{ old('stock', $product->stock) }}" class="form-input"
                                    placeholder="e.g. 50">
                            </div>
                        </div>
                    </div>

                    {{-- Variations 区块（Shopee 风格） --}}
                    <div id="variantsWrapper" class="border rounded-xl p-4 space-y-4 hidden">

                        {{-- 1. Variation Groups（最多 2 个，像 Shopee 的 Variation1 / Variation2） --}}
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">Variations setup</p>
                                    <p class="text-xs text-gray-500">
                                        Example: Variation 1 = Color (Red, Orange), Variation 2 = Size (6, 8)
                                    </p>
                                </div>
                                <button type="button" id="addVariationGroupBtn"
                                    class="px-3 py-1.5 rounded-lg text-sm border border-gray-300 hover:bg-gray-50">
                                    + Add variation group
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
                                            <input data-vg-name class="form-input py-1.5 text-sm" placeholder="e.g. Color">
                                        </div>
                                        <div>
                                            <label class="form-label text-xs">Options (comma separated)</label>
                                            <input data-vg-values class="form-input py-1.5 text-sm"
                                                placeholder="e.g. Red, Orange">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 模板：新增 Variation Group 用 --}}
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
                                    class="px-3 py-1.5 rounded-lg text-xs border border-[#D4AF37]
                               text-[#D4AF37] hover:bg-[#D4AF37]/5">
                                    Generate variation list
                                </button>
                            </div>
                        </div>

                        {{-- 2. Variation List（组合后的列表，像 Shopee 下半部） --}}
                        <div class="space-y-2">
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
                                    class="px-3 py-1.5 rounded-lg text-sm border border-gray-300 hover:bg-gray-50">
                                    + Add row manually
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            {{-- RIGHT: Media & status --}}
            <div class="space-y-5">

                {{-- Image upload card --}}
                <div class="border rounded-xl p-4">
                    <label class="form-label mb-2">Product Image</label>

                    <div class="flex items-center gap-4">
                        {{-- Preview --}}
                        <div
                            class="h-24 w-24 rounded-lg bg-gray-100 border overflow-hidden flex items-center justify-center">
                            <img id="imagePreview" src="{{ $product->image ? asset('storage/' . $product->image) : '' }}"
                                class="h-full w-full object-cover {{ $product->image ? '' : 'hidden' }}"
                                alt="Preview" />

                            {{-- Placeholder icon --}}
                            <div id="imagePlaceholder" class="{{ $product->image ? 'hidden' : '' }}">
                                <svg class="h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 16.5V6.75A2.25 2.25 0 015.25 4.5h13.5A2.25 2.25 0 0121 6.75v9.75M3 16.5l4.5-4.5a2.25 2.25 0 013.182 0l4.318 4.318a2.25 2.25 0 003.182 0L21 13.5" />
                                </svg>
                            </div>
                        </div>

                        {{-- Info + Buttons --}}
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900" id="imageFileName">
                                {{ $product->image ? 'Current image uploaded' : 'No image selected' }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1" id="imageFileMeta">
                                {{ $product->image ? 'You can replace it below' : 'PNG / JPG up to 2MB' }}
                            </div>

                            <div class="mt-3 flex items-center gap-2">
                                <label
                                    class="inline-flex items-center px-3 py-2 rounded-lg border border-gray-300 bg-white
                              hover:bg-gray-50 cursor-pointer text-sm">
                                    Choose file
                                    <input id="imageInput" type="file" name="image" class="hidden"
                                        accept="image/*">
                                </label>

                                <button type="button" id="imageClearBtn"
                                    class="px-3 py-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-sm">
                                    Clear
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- Active toggle --}}
                <div class="border rounded-xl p-4 flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-900">Active</p>
                        <p class="text-sm text-gray-500">Visible in shop</p>
                    </div>

                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                            @checked(old('is_active', $product->is_active))>
                        <div
                            class="w-11 h-6 bg-gray-200 rounded-full peer
                                peer-checked:bg-[#D4AF37]
                                after:content-['']
                                after:absolute after:top-[2px] after:left-[2px]
                                after:bg-white after:h-5 after:w-5 after:rounded-full
                                after:transition-all
                                peer-checked:after:translate-x-full">
                        </div>
                    </label>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="mt-6 flex gap-3">
            <button
                class="px-5 py-2 rounded-xl bg-[#D4AF37]/20
                       text-[#8f6a10] font-semibold hover:bg-[#D4AF37]/30 transition">
                Save
            </button>
            <a href="{{ route('admin.products.index') }}"
                class="px-5 py-2 rounded-xl border border-gray-300 hover:bg-gray-50">
                Cancel
            </a>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // =========================
            // Image preview logic
            // =========================
            const input = document.getElementById('imageInput');
            const preview = document.getElementById('imagePreview');
            const placeholder = document.getElementById('imagePlaceholder');
            const fileName = document.getElementById('imageFileName');
            const fileMeta = document.getElementById('imageFileMeta');
            const clearBtn = document.getElementById('imageClearBtn');

            const formatBytes = (bytes) => {
                if (!bytes) return '';
                const sizes = ['B', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(1024));
                return (bytes / Math.pow(1024, i)).toFixed(i === 0 ? 0 : 1) + ' ' + sizes[i];
            };

            if (input) {
                input.addEventListener('change', () => {
                    const file = input.files && input.files[0];
                    if (!file) return;

                    if (fileName) fileName.textContent = file.name;
                    if (fileMeta) fileMeta.textContent =
                        `${formatBytes(file.size)} • ${file.type || 'image'}`;

                    if (preview && placeholder) {
                        const url = URL.createObjectURL(file);
                        preview.src = url;
                        preview.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                    }
                });
            }

            if (clearBtn) {
                clearBtn.addEventListener('click', () => {
                    if (input) input.value = '';
                    if (preview && placeholder) {
                        preview.src = '';
                        preview.classList.add('hidden');
                        placeholder.classList.remove('hidden');
                    }
                    if (fileName) fileName.textContent = 'No file selected';
                    if (fileMeta) fileMeta.textContent = 'PNG/JPG up to 2MB';
                });
            }

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
@endpush
