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
        // 找 / 建 cart
        $cart = Cart::firstOrCreate([
            'user_id'    => auth()->id(),
            'session_id' => $request->session()->getId(),
        ]);

        $qty = max(1, (int) $request->input('quantity', 1));

        $variantId    = null;
        $variantLabel = null;
        $unitPrice    = $product->price;

        if ($product->has_variants) {
            $variantId = $request->input('variant_id');

            if (!$variantId) {
                return back()->with('error', 'Please select a variant before adding to cart.');
            }

            $variant = $product->variants()->where('id', $variantId)->firstOrFail();

            $unitPrice = $variant->price;

            // 这边如果你喜欢可以继续用 label/value 组合的文字
            $label  = explode('/', $variant->options['label']  ?? '');
            $value  = explode('/', $variant->options['value']  ?? '');

            $parts = [];

            foreach ($label as $i => $name) {
                $name = trim($name);
                $val  = trim($value[$i] ?? '');

                if ($name !== '' && $val !== '') {
                    $parts[] = "{$name}: {$val}";
                }
            }

            $variantLabel = implode(' & ', $parts);
        }

        if (is_null($unitPrice)) {
            return back()->with('error', 'This product or variant does not have a price set.');
        }

        // ✅ 同一个 product + 同一个 variant 合并数量
        $query = $cart->items()->where('product_id', $product->id);

        if ($variantId) {
            $query->where('product_variant_id', $variantId);
        } else {
            $query->whereNull('product_variant_id');
        }

        $item = $query->first();

        if ($item) {
            $item->qty += $qty;
            $item->save();
        } else {
            $cart->items()->create([
                'product_id'         => $product->id,
                'product_variant_id' => $variantId,
                'qty'                => $qty,
                'unit_price'         => $unitPrice,
                'variant_label'      => $variantLabel,
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
        // 先记住它属于哪一个 cart
        $cart = $item->cart;   // 确保 CartItem 有 cart() 关系

        // 删掉这条 item
        $item->delete();

        // 如果这个 cart 已经没有任何 item 了，就把 cart 也删掉
        if ($cart && !$cart->items()->exists()) {
            $cart->delete();
        }

        return redirect()->route('cart.index')
            ->with('success', 'Item removed.');
    }
}
