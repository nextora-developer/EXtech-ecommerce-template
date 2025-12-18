<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $q = Product::query()->with('category');

        if ($request->filled('keyword')) {
            $kw = $request->string('keyword');
            $q->where(function ($qq) use ($kw) {
                $qq->where('name', 'like', "%{$kw}%")
                    ->orWhere('slug', 'like', "%{$kw}%");
            });
        }

        if ($request->filled('status')) {
            $q->where('is_active', $request->string('status') === 'active');
        }

        if ($request->filled('category_id')) {
            $q->where('category_id', $request->integer('category_id'));
        }

        $products = $q->latest()->paginate(15)->withQueryString();
        $categories = Category::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.products.form', [
            'product'    => new Product(),
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['nullable', 'string', 'max:255', 'unique:products,slug'],
            'description' => ['nullable', 'string'],

            'has_variants' => ['nullable', 'boolean'],

            // 没有 variants 时必须填 price；
            // 有 variants 时可以不用填 price
            'price'  => ['nullable', 'numeric', 'min:0', 'required_without:variants'],
            'stock'  => ['nullable', 'integer', 'min:0'],

            // variants 是一个 array
            'variants'              => ['nullable', 'array', 'required_without:price'],
            'variants.*.sku'        => ['nullable', 'string', 'max:100'],
            'variants.*.label'      => ['nullable', 'string', 'max:255'],
            'variants.*.value'      => ['nullable', 'string', 'max:255'],
            'variants.*.price'      => ['nullable', 'numeric', 'min:0'],
            'variants.*.stock'      => ['nullable', 'integer', 'min:0'],

            'image'     => ['nullable', 'image', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        // slug auto
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        // checkbox normalize
        $data['is_active']    = (bool) ($data['is_active'] ?? false);
        $data['has_variants'] = (bool) ($data['has_variants'] ?? false);

        // 先拿出来 variants，剩下的是 products 表的数据
        $variantsInput = $data['variants'] ?? [];
        unset($data['variants']);

        // upload image
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // 如果使用 variants，可以把主 stock 当总和（可选）
        if ($data['has_variants']) {
            $totalStock = 0;
            foreach ($variantsInput as $v) {
                $totalStock += (int) ($v['stock'] ?? 0);
            }
            $data['stock'] = $totalStock;
            // 主 price 可以留空或当「参考价」
            // $data['price'] = $data['price'] ?? null;
        } else {
            // 没有 variants：price 和 stock 在 validation 已经 required_without 处理
            $data['stock'] = $data['stock'] ?? 0;
        }

        // 先创建产品
        $product = Product::create($data);

        // 再存 variants（如果有）
        if ($data['has_variants'] && !empty($variantsInput)) {
            foreach ($variantsInput as $variant) {

                // 全空就跳过
                if (
                    ($variant['sku'] ?? '')   === '' &&
                    ($variant['label'] ?? '') === '' &&
                    ($variant['value'] ?? '') === '' &&
                    ($variant['price'] ?? '') === '' &&
                    ($variant['stock'] ?? '') === ''
                ) {
                    continue;
                }

                $options = [
                    'label' => $variant['label'] ?? null,
                    'value' => $variant['value'] ?? null,
                ];

                $product->variants()->create([
                    'sku'      => $variant['sku'] ?? null,
                    'options'  => $options,  // 👈 存 JSON
                    'price'    => isset($variant['price']) && $variant['price'] !== '' ? $variant['price'] : null,
                    'stock'    => isset($variant['stock']) && $variant['stock'] !== '' ? (int) $variant['stock'] : 0,
                    'is_active' => true,
                ]);
            }
            $this->syncOptionsFromVariants($product, $variantsInput);
        } else {
            // 没有 variants 的话，确保把旧的 options 清掉（新商品一般没有旧的）
            $this->syncOptionsFromVariants($product, []);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created.');
    }

    private function syncOptionsFromVariants(Product $product, array $variantsInput): void
    {
        // 先从 variantsInput 里面整理出：
        // $groupValues['Color'] = ['Black', 'White']
        // $groupValues['Size']  = ['S', 'M', 'L']
        $groupValues = [];

        foreach ($variantsInput as $variant) {
            $label = $variant['label'] ?? null;
            $value = $variant['value'] ?? null;

            if (!$label || !$value) {
                continue;
            }

            // 用 / 分隔： "Color / Size" + "Black / M"
            $labels = array_map('trim', explode('/', $label));
            $values = array_map('trim', explode('/', $value));

            foreach ($labels as $index => $groupName) {
                $groupName = trim($groupName);
                $val = $values[$index] ?? null;
                $val = $val ? trim($val) : null;

                if ($groupName === '' || $val === null || $val === '') {
                    continue;
                }

                // 用 [groupName][value] 做去重
                $groupValues[$groupName][$val] = true;
            }
        }

        // 如果没有任何可用的 group/value，直接清掉旧 options 就好
        // （避免残留）
        // 先删掉旧的 options & values
        $oldOptionIds = $product->options()->pluck('id')->all();
        if (!empty($oldOptionIds)) {
            ProductOptionValue::whereIn('product_option_id', $oldOptionIds)->delete();
            ProductOption::whereIn('id', $oldOptionIds)->delete();
        }

        if (empty($groupValues)) {
            return;
        }

        // 重建新的 options & values
        $optionSort = 0;

        foreach ($groupValues as $groupName => $values) {
            $option = $product->options()->create([
                'name'       => Str::slug($groupName), // e.g. "warna-saiz"
                'label'      => $groupName,            // e.g. "Warna" / "Saiz"
                'sort_order' => $optionSort++,
            ]);

            $valueSort = 0;

            foreach (array_keys($values) as $val) {
                $option->values()->create([
                    'value'      => $val,
                    'sort_order' => $valueSort++,
                ]);
            }
        }
    }


    public function edit(Product $product)
    {
        $product->load('variants');

        $categories = Category::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['nullable', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($product->id)],
            'description' => ['nullable', 'string'],

            'has_variants' => ['nullable', 'boolean'],

            // 没有 variants 时必须填 price；有 variants 时可以不用填 price
            'price'  => ['nullable', 'numeric', 'min:0', 'required_without:variants'],
            'stock'  => ['nullable', 'integer', 'min:0'],

            // variants 数组
            'variants'              => ['nullable', 'array', 'required_without:price'],
            'variants.*.sku'        => ['nullable', 'string', 'max:100'],
            'variants.*.label'      => ['nullable', 'string', 'max:255'],
            'variants.*.value'      => ['nullable', 'string', 'max:255'],
            'variants.*.price'      => ['nullable', 'numeric', 'min:0'],
            'variants.*.stock'      => ['nullable', 'integer', 'min:0'],

            'image'     => ['nullable', 'image', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        // slug auto
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        // checkbox normalize
        $data['is_active']    = (bool) ($data['is_active'] ?? false);
        $data['has_variants'] = (bool) ($data['has_variants'] ?? false);

        // 拆出 variants，剩下是 products 表字段
        $variantsInput = $data['variants'] ?? [];
        unset($data['variants']);

        // 上传图片（如果有的话，可以顺便删旧的，看你要不要）
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // 处理 stock（和 store() 一样）
        if ($data['has_variants']) {
            $totalStock = 0;
            foreach ($variantsInput as $v) {
                $totalStock += (int) ($v['stock'] ?? 0);
            }
            $data['stock'] = $totalStock;
        } else {
            $data['stock'] = $data['stock'] ?? 0;
        }

        // 先更新 product 本体
        $product->update($data);

        // 先把旧 variants 清掉，重新建
        $product->variants()->delete();

        if ($data['has_variants'] && !empty($variantsInput)) {

            foreach ($variantsInput as $variant) {
                // 完全空的行就跳过
                if (
                    ($variant['sku'] ?? '')   === '' &&
                    ($variant['label'] ?? '') === '' &&
                    ($variant['value'] ?? '') === '' &&
                    ($variant['price'] ?? '') === '' &&
                    ($variant['stock'] ?? '') === ''
                ) {
                    continue;
                }

                $options = [
                    'label' => $variant['label'] ?? null,
                    'value' => $variant['value'] ?? null,
                ];

                $product->variants()->create([
                    'sku'      => $variant['sku'] ?? null,
                    'options'  => $options, // 👈 把 label/value 放进 JSON
                    'price'    => isset($variant['price']) && $variant['price'] !== '' ? $variant['price'] : null,
                    'stock'    => isset($variant['stock']) && $variant['stock'] !== '' ? (int) $variant['stock'] : 0,
                    'is_active' => true,
                ]);
            }

            // 同步 product_options / product_option_values
            $this->syncOptionsFromVariants($product, $variantsInput);
        } else {
            // 没有 variants，清空旧 options
            $this->syncOptionsFromVariants($product, []);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated.');
    }


    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted.');
    }

    public function toggle(Product $product)
    {
        $product->update([
            'is_active' => ! $product->is_active,
        ]);

        return back()->with('success', 'Product status updated.');
    }
}
