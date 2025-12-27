<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Cart::with(['items.product'])
            ->where('user_id', auth()->id())
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $items    = $cart->items;
        $subtotal = $items->sum(fn($i) => $i->unit_price * $i->qty);

        // 🔹 这里拿 defaultAddress（如果你 User 里有这个关系）
        $user            = auth()->user();
        $defaultAddress  = $user?->defaultAddress;   // User::defaultAddress 关系
        $addresses      = $user?->addresses ?? collect(); // 所有地址

        return view('checkout.index', compact(
            'items',
            'subtotal',
            'defaultAddress',
            'addresses'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required',
            'phone'   => 'required',
            'address' => 'required',
        ]);

        DB::transaction(function () use ($request) {

            $cart = Cart::with('items.product')
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $items = $cart->items;

            if ($items->isEmpty()) {
                abort(400, 'Cart is empty');
            }

            $subtotal = $items->sum(function ($item) {
                return $item->unit_price * $item->qty;
            });

            $order = Order::create([
                'user_id'        => auth()->id(),
                'customer_name'  => $request->name,
                'customer_phone' => $request->phone,
                'address_line1'  => $request->address,
                'subtotal'       => $subtotal,
                'total'          => $subtotal, // 之后可以 + shipping
                'status'         => 'pending',
            ]);

            foreach ($items as $item) {
                $order->items()->create([
                    'product_id'       => $item->product_id,
                    'qty'              => $item->qty,
                    'unit_price'       => $item->unit_price,
                    'product_variant_id' => $item->product_variant_id ?? null,
                    'variant_label'    => $item->variant_label ?? null,
                ]);
            }

            // 清空购物车
            $cart->items()->delete();
            // $cart->delete(); // 看你要不要顺便删掉整个 cart
        });

        return redirect()->route('account.orders.index')
            ->with('success', 'Order placed successfully.');
    }
}
