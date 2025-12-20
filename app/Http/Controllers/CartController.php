<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        // 根据 user 或 session 拿 cart（简单示范，你可以自己优化）
        $cart = Cart::query()
            ->when(auth()->check(), fn($q) => $q->where('user_id', auth()->id()))
            ->when(!auth()->check(), fn($q) => $q->where('session_id', $request->session()->getId()))
            ->with('items.product')
            ->first();

        $items = $cart?->items ?? collect();

        $subtotal = $items->sum(fn($item) => $item->unit_price * $item->qty);

        return view('cart.index', [
            'items'    => $items,
            'subtotal' => $subtotal,
        ]);
    }

    public function add(Request $request, Product $product)
    {
        // find or create cart
        $cart = Cart::firstOrCreate([
            'user_id'    => auth()->id(),
            'session_id' => $request->session()->getId(),
        ]);

        $qty = max(1, (int) $request->input('quantity', 1));

        // 核心：判断 variant
        if ($product->has_variants) {

            // 暂时我们这样获取 variant：
            $variant = $product->variants()->first(); // 先拿一个 variant

            if (!$variant) {
                return back()->with('error', 'Variant not found.');
            }

            $unitPrice = $variant->price;
        } else {
            $unitPrice = $product->price;
        }

        if (is_null($unitPrice)) {
            return back()->with('error', 'Please set a price or variant price.');
        }

        // store
        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->qty += $qty;
            $item->save();
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'qty'        => $qty,
                'unit_price' => $unitPrice,
            ]);
        }

        return redirect()->route('cart.index');
    }



    public function update(Request $request, CartItem $item)
    {
        $action = $request->input('action');

        if ($action === 'increase') {
            $item->qty++;
        } elseif ($action === 'decrease') {
            if ($item->qty > 1) {
                $item->qty--;
            }
            // 如果你想 qty 到 0 就删掉，也可以在这里判断：
            // else {
            //     $item->delete();
            //     return redirect()->route('cart.index');
            // }
        }

        $item->save();

        return redirect()->route('cart.index');
    }


    public function remove(CartItem $item)
    {
        $item->delete();

        return redirect()->route('cart.index')
            ->with('success', 'Item removed.');
    }
}
