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
                    <input type="checkbox" name="has_variants" value="1" class="rounded border-gray-300"
                        @checked(old('has_variants', $product->has_variants ?? false))>
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

        {{-- 1. Variation Groups --}}
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
                        <button type="button" class="text-xs text-red-500 hover:underline" data-remove-variation-group>
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
                            <input data-vg-values class="form-input py-1.5 text-sm" placeholder="e.g. Red, Orange">
                        </div>
                    </div>
                </div>
            </div>

            {{-- 模板：新增 Variation Group 用 --}}
            <template id="variationGroupTemplate">
                <div class="variation-group border rounded-lg p-3 space-y-3 bg-gray-50" data-index="__INDEX__">
                    <div class="flex items-center justify-between">
                        <p class="font-medium text-sm">
                            Variation <span class="variation-order"></span>
                        </p>
                        <button type="button" class="text-xs text-red-500 hover:underline" data-remove-variation-group>
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
                            <input data-vg-values class="form-input py-1.5 text-sm" placeholder="e.g. S, M, L">
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

        {{-- 2. Variation List --}}
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
                                        <button type="button" class="text-xs text-red-500 hover:underline"
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
                                        <input name="variants[{{ $i }}][sku]" value="{{ $variant->sku }}"
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
                                        <button type="button" class="text-xs text-red-500 hover:underline"
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
                        <button type="button" class="text-xs text-red-500 hover:underline" data-remove-variant>
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
