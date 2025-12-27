<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\PaymentMethod;
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

        $paymentMethods = PaymentMethod::where('is_active', true)
            ->orderByDesc('is_default')
            ->get();

        return view('checkout.index', compact(
            'items',
            'subtotal',
            'defaultAddress',
            'addresses',
            'paymentMethods',
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required',
            'phone'           => 'required',
            'email'           => 'required|email',
            'address_line1'   => 'required',
            'postcode'        => 'required',
            'city'            => 'required',
            'state'           => 'required',
            'country'         => 'required',
            'payment_method'  => 'required|exists:payment_methods,code',
            'payment_receipt' => 'nullable|image|max:4096', // 4MB
        ]);

        $paymentMethod = PaymentMethod::where('code', $request->payment_method)
            ->where('is_active', true)
            ->firstOrFail();

        $cart = Cart::with('items.product')
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $items    = $cart->items;
        $subtotal = $items->sum(fn($i) => $i->unit_price * $i->qty);

        $receiptPath = null;
        if ($request->hasFile('payment_receipt')) {
            $receiptPath = $request->file('payment_receipt')
                ->store('payment_receipts', 'public');
        }

        DB::transaction(function () use ($request, $items, $subtotal, $paymentMethod, $receiptPath, $cart) {
            $order = Order::create([
                'user_id'              => auth()->id(),
                'customer_name'        => $request->name,
                'customer_phone'       => $request->phone,
                'customer_email'       => $request->email,
                'address_line1'        => $request->address_line1,
                'address_line2'        => $request->address_line2,
                'postcode'             => $request->postcode,
                'city'                 => $request->city,
                'state'                => $request->state,
                'country'              => $request->country,
                'subtotal'             => $subtotal,
                'total'                => $subtotal,
                'status'               => 'pending',
                'payment_method_code'  => $paymentMethod->code,
                'payment_method_name'  => $paymentMethod->name,
                'payment_receipt_path' => $receiptPath,
            ]);

            foreach ($items as $item) {
                $order->items()->create([
                    'product_id'         => $item->product_id,
                    'qty'                => $item->qty,
                    'unit_price'         => $item->unit_price,
                    'product_variant_id' => $item->product_variant_id,
                    'variant_label'      => $item->variant_label,
                ]);
            }

            $cart->items()->delete();
        });

        return redirect()->route('account.orders.index')
            ->with('success', 'Order placed successfully. We will contact you to verify your payment.');
    }
}
