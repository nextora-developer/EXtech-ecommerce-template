<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getCart(Request $request): Cart
    {
        $user = $request->user();

        if ($user) {
            return Cart::firstOrCreate(['user_id' => $user->id]);
        }

        $sid = $request->session()->getId();
        return Cart::firstOrCreate(['session_id' => $sid]);
    }

    public function index(Request $request)
    {
        $cart = $this->getCart($request)->load('items.product');

        $subtotal = $cart->items->sum(fn($i) => $i->qty * $i->unit_price_cents);

        return view('cart.index', compact('cart', 'subtotal'));
    }

    public function add(Request $request, Product $product)
    {
        if (!$product->is_active) abort(404);

        $cart = $this->getCart($request);

        $item = $cart->items()->firstOrCreate(
            ['product_id' => $product->id],
            ['qty' => 0, 'unit_price_cents' => $product->price_cents]
        );

        $item->qty += 1;
        $item->unit_price_cents = $product->price_cents; // keep updated on add
        $item->save();

        return redirect()->route('cart.index')->with('success', 'Added to cart');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate(['qty' => 'required|integer|min:1|max:99']);

        $cart = $this->getCart($request);
        $item = $cart->items()->where('product_id', $product->id)->firstOrFail();

        $item->qty = (int) $request->qty;
        $item->save();

        return redirect()->route('cart.index');
    }

    public function remove(Request $request, Product $product)
    {
        $cart = $this->getCart($request);
        $cart->items()->where('product_id', $product->id)->delete();

        return redirect()->route('cart.index');
    }
}
