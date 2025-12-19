<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    private function userCart(Request $request): Cart
    {
        return Cart::firstOrCreate(['user_id' => $request->user()->id]);
    }

    public function show(Request $request)
    {
        $cart = $this->userCart($request)->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('shop.index')->with('success', 'Your cart is empty');
        }

        $subtotal = $cart->items->sum(fn($i) => $i->qty * $i->unit_price);
        $shipping = 0;
        $total = $subtotal + $shipping;

        return view('checkout.show', compact('cart', 'subtotal', 'shipping', 'total'));
    }

    public function place(Request $request)
    {
        $data = $request->validate([
            'customer_name' => 'required|string|max:120',
            'customer_phone' => 'required|string|max:30',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:120',
            'state' => 'required|string|max:120',
            'postcode' => 'required|string|max:20',
        ]);

        $cart = $this->userCart($request)->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('shop.index');
        }

        return DB::transaction(function () use ($request, $cart, $data) {
            // stock check
            foreach ($cart->items as $item) {
                if ($item->product->stock < $item->qty) {
                    return back()->withErrors([
                        'stock' => "{$item->product->name} has not enough stock."
                    ]);
                }
            }

            $subtotal = $cart->items->sum(fn($i) => $i->qty * $i->unit_price);
            $shipping = 0;
            $total = $subtotal + $shipping;

            $order = Order::create([
                'user_id' => $request->user()->id,
                'order_no' => 'BRIF-' . strtoupper(Str::random(10)),
                ...$data,
                'subtotal' => $subtotal,
                'shipping_fee' => $shipping,
                'total_' => $total,
                'status' => 'pending',
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'qty' => $item->qty,
                    'unit_price' => $item->unit_price,
                ]);

                // reduce stock
                $item->product->decrement('stock', $item->qty);
            }

            // clear cart
            $cart->items()->delete();

            return redirect()->route('orders.mine.show', $order)->with('success', 'Order placed!');
        });
    }

    public function myOrders(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->latest()->paginate(10);

        return view('orders.mine', compact('orders'));
    }

    public function myOrderShow(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        $order->load('items');
        return view('orders.show', compact('order'));
    }
}
