<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use App\Models\ProductImage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

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

        $products = $q->latest()->paginate(10)->withQueryString();
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
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],

            'has_variants' => ['nullable', 'boolean'],

            // 没有 variants 时必须填 price；有 variants 时可以不用填 price
            'price'  => ['nullable', 'numeric', 'min:0', 'required_without:variants'],
            'stock'  => ['nullable', 'integer', 'min:0'],

            // variants 是一个 array
            'variants'              => ['nullable', 'array', 'required_without:price'],
            'variants.*.sku'        => ['nullable', 'string', 'max:100'],
            'variants.*.label'      => ['nullable', 'string', 'max:255'],
            'variants.*.value'      => ['nullable', 'string', 'max:255'],
            'variants.*.price'      => ['nullable', 'numeric', 'min:0'],
            'variants.*.stock'      => ['nullable', 'integer', 'min:0'],

            // ⭐ Shopee-style 规格（Additional Info）
            'specs'              => ['nullable', 'array'],
            'specs.*.name'       => ['nullable', 'string', 'max:255'],
            'specs.*.value'      => ['nullable', 'string', 'max:1000'],

            // 多图上传
            'images'     => ['nullable', 'array'],
            'images.*'   => ['nullable', 'image', 'max:2048'],

            // 旧的单图字段（form 不用的话也没关系，保留兼容）
            'image'     => ['nullable', 'image', 'max:2048'],

            'is_active' => ['nullable', 'boolean'],
            'is_digital' => ['nullable', 'boolean'],
        ]);

        // slug auto
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        // checkbox normalize
        $data['is_active']   = $request->boolean('is_active');
        $data['has_variants'] = $request->boolean('has_variants');
        $data['is_digital']   = $request->boolean('is_digital');

        // 先拿出来 variants & specs & images，剩下的是 products 表的数据
        $variantsInput = $data['variants'] ?? [];
        $specsInput    = $data['specs'] ?? [];

        unset($data['variants']);

        $imagesInput = $request->file('images', []); // 这里直接从 request 拿 file

        // 如果你已经完全不用旧的 image 字段，这里可以不处理 $data['image']

        // ⭐ 处理 specs：过滤掉全空的行
        $specs = collect($specsInput)
            ->filter(function ($row) {
                return filled($row['name'] ?? null) || filled($row['value'] ?? null);
            })
            ->values()
            ->all();

        // 如果使用 variants，可以把主 stock 当总和（可选）
        if ($data['has_variants']) {
            $totalStock = 0;
            foreach ($variantsInput as $v) {
                $totalStock += (int) ($v['stock'] ?? 0);
            }
            $data['stock'] = $totalStock;
        } else {
            // 没有 variants：price 和 stock 在 validation 已经 required_without 处理
            $data['stock'] = $data['stock'] ?? 0;
        }

        // ⭐ 把处理好的 specs 塞回 data
        $data['specs'] = $specs;

        // 先创建产品（先不处理 image 字段）
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
                    'sku'       => $variant['sku'] ?? null,
                    'options'   => $options,  // 👈 存 JSON
                    'price'     => isset($variant['price']) && $variant['price'] !== '' ? $variant['price'] : null,
                    'stock'     => isset($variant['stock']) && $variant['stock'] !== '' ? (int) $variant['stock'] : 0,
                    'is_active' => true,
                ]);
            }
            $this->syncOptionsFromVariants($product, $variantsInput);
        } else {
            // 没有 variants 的话，确保把旧的 options 清掉（新商品一般没有旧的）
            $this->syncOptionsFromVariants($product, []);
        }

        // 处理多图上传：存去 product_images，并设第一张为封面
        if (!empty($imagesInput)) {
            foreach ($imagesInput as $index => $file) {
                if (!$file) {
                    continue;
                }

                $path = $file->store('products', 'public');

                $image = new ProductImage([
                    'path'       => $path,
                    'is_primary' => $index === 0,  // 第一张当封面
                    'sort_order' => $index,
                ]);

                $product->images()->save($image);

                // 如果是封面，同步到 products.image 字段
                if ($index === 0) {
                    $product->update(['image' => $path]);
                }
            }
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
        // 多 load 一个 images
        $product->load('variants', 'images');

        $categories = Category::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['nullable', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($product->id)],
            'short_description' => ['nullable', 'string', 'max:255'],
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

            // ⭐ Shopee-style 规格（Additional Info）
            'specs'              => ['nullable', 'array'],
            'specs.*.name'       => ['nullable', 'string', 'max:255'],
            'specs.*.value'      => ['nullable', 'string', 'max:1000'],

            // 多图上传
            'images'     => ['nullable', 'array'],
            'images.*'   => ['nullable', 'image', 'max:2048'],

            // 旧的 image 字段
            'image'     => ['nullable', 'image', 'max:2048'],

            'is_active' => ['nullable', 'boolean'],
            'is_digital' => ['nullable', 'boolean'],
        ]);

        // slug auto
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        // checkbox normalize
        $data['is_active']   = $request->boolean('is_active');
        $data['has_variants'] = $request->boolean('has_variants');
        $data['is_digital']   = $request->boolean('is_digital');

        // 拆出 variants / specs，其余为 products 字段
        $variantsInput = $data['variants'] ?? [];
        $specsInput    = $data['specs'] ?? [];

        unset($data['variants']);

        $imagesInput = $request->file('images', []);

        // ⭐ 处理 specs：过滤空行
        $specs = collect($specsInput)
            ->filter(
                fn($row) =>
                filled($row['name'] ?? null) || filled($row['value'] ?? null)
            )
            ->values()
            ->all();

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

        // ⭐ 保存 specs
        $data['specs'] = $specs;

        // 先更新 product 本体（不动 image 字段，后面根据新图片再 update）
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
                    'sku'       => $variant['sku'] ?? null,
                    'options'   => $options, // 👈 把 label/value 放进 JSON
                    'price'     => isset($variant['price']) && $variant['price'] !== '' ? $variant['price'] : null,
                    'stock'     => isset($variant['stock']) && $variant['stock'] !== '' ? (int) $variant['stock'] : 0,
                    'is_active' => true,
                ]);
            }

            // 同步 product_options / product_option_values
            $this->syncOptionsFromVariants($product, $variantsInput);
        } else {
            // 没有 variants，清空旧 options
            $this->syncOptionsFromVariants($product, []);
        }

        // =========================
        // 图片处理（有新图片就整组替换）
        // =========================
        if (!empty($imagesInput)) {

            // 1) 先删旧图片文件 + DB 记录
            foreach ($product->images as $img) {
                if ($img->path) {
                    Storage::disk('public')->delete($img->path);
                }
            }

            // 删掉 product_images 记录
            $product->images()->delete();

            // 如果 products.image 也有封面路径，一起删
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
                $product->image = null;
                $product->save();
            }

            // 2) 再存新的图片
            $primaryPath = null;

            foreach ($imagesInput as $index => $file) {
                if (!$file) {
                    continue;
                }

                $path = $file->store('products', 'public');

                $product->images()->create([
                    'path'       => $path,
                    'is_primary' => $index === 0,  // 第一张当封面
                    'sort_order' => $index,        // 从 0 开始排
                ]);

                if ($index === 0) {
                    $primaryPath = $path;
                }
            }

            // 同步封面到 products.image 字段
            if ($primaryPath) {
                $product->update(['image' => $primaryPath]);
            }
        }


        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated.');
    }


    public function destroy(Product $product)
    {
        // 顺便把图片文件删掉（避免 storage 爆掉）
        foreach ($product->images as $img) {
            if ($img->path) {
                Storage::disk('public')->delete($img->path);
            }
        }

        // 如果 products.image 也有存封面路径，可以一起删（重复删也不会出错）
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // 删掉 images 记录（如果没有在 migration 里做 onDelete('cascade')）
        $product->images()->delete();

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
