<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ShopController extends Controller
{
    // Homepage
    public function home()
    {
        $featured = Product::where('is_active', true)
            ->latest()
            ->limit(8)
            ->get();

        return view('shop.home', compact('featured'));
    }

    // Shop listing
    public function index()
    {
        $products = Product::where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('shop.index', compact('products'));
    }

    // Product detail (route model binding by slug already in your routes)
    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        return view('shop.show', compact('product'));
    }
}
