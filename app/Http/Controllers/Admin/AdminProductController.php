<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable'],
        ]);

        $baseSlug = Str::slug($data['name']);
        $slug = $baseSlug;
        $i = 2;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i++;
        }

        Product::create([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'price_cents' => (int) round(((float)$data['price']) * 100),
            'stock' => (int) $data['stock'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product created');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable'],
        ]);

        if ($product->name !== $data['name']) {
            $baseSlug = Str::slug($data['name']);
            $slug = $baseSlug;
            $i = 2;
            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $baseSlug . '-' . $i++;
            }
            $product->slug = $slug;
        }

        $product->category_id = $data['category_id'];
        $product->name = $data['name'];
        $product->description = $data['description'] ?? null;
        $product->price_cents = (int) round(((float)$data['price']) * 100);
        $product->stock = (int) $data['stock'];
        $product->is_active = $request->boolean('is_active');
        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Product updated');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product deleted');
    }
}
