<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\ShippingRate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Mail\OrderPlacedMail;
use App\Mail\AdminOrderNotificationMail;
use Illuminate\Support\Facades\Mail;
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

        $user           = auth()->user();
        $defaultAddress = $user?->defaultAddress;
        $addresses      = $user?->addresses ?? collect();

        $paymentMethods = PaymentMethod::where('is_active', true)
            ->orderByDesc('is_default')
            ->get();

        // ✅ 有没有实体商品
        $hasPhysical = $items->contains(function ($item) {
            return !$item->product->is_digital;   // 没勾 digital = 实体
        });

        // ✅ 先给 shippingFee = null，表示“待计算”
        $shippingFee = null;

        // ✅ 把 rate 丢给前端，用 JS 算（west_my / east_my）
        $shippingRates = $hasPhysical
            ? ShippingRate::pluck('rate', 'code')   // ['west_my' => 8, 'east_my' => 15, ...]
            : collect();                             // 全部 digital 就不用运费了

        $states = [
            // West Malaysia
            ['name' => 'Johor',           'zone' => 'west_my'],
            ['name' => 'Kedah',           'zone' => 'west_my'],
            ['name' => 'Kelantan',        'zone' => 'west_my'],
            ['name' => 'Melaka',          'zone' => 'west_my'],
            ['name' => 'Negeri Sembilan', 'zone' => 'west_my'],
            ['name' => 'Pahang',          'zone' => 'west_my'],
            ['name' => 'Perak',           'zone' => 'west_my'],
            ['name' => 'Perlis',          'zone' => 'west_my'],
            ['name' => 'Penang',          'zone' => 'west_my'],
            ['name' => 'Selangor',        'zone' => 'west_my'],
            ['name' => 'Terengganu',      'zone' => 'west_my'],
            ['name' => 'Kuala Lumpur',    'zone' => 'west_my'],
            ['name' => 'Putrajaya',       'zone' => 'west_my'],

            // East Malaysia
            ['name' => 'Sabah',           'zone' => 'east_my'],
            ['name' => 'Sarawak',         'zone' => 'east_my'],
            ['name' => 'Labuan',          'zone' => 'east_my'],
        ];

        return view('checkout.index', compact(
            'items',
            'subtotal',
            'defaultAddress',
            'addresses',
            'paymentMethods',
            'shippingFee',
            'shippingRates',
            'hasPhysical',
            'states',
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

        // 1️⃣ 检查有没有实体产品
        $hasPhysical = $items->contains(function ($item) {
            return !$item->product->is_digital; // 没设 true 就当实体
        });

        // 默认运费
        $shippingFee = 0;

        if ($hasPhysical) {
            // 2️⃣ 根据 state 判断东马 / 西马
            $eastStates = ['Sabah', 'Sarawak', 'Labuan'];

            $zoneCode = in_array($request->state, $eastStates)
                ? 'east_my'
                : 'west_my';

            // 3️⃣ 去 DB 拿 rate，找不到就当 0
            $rate = ShippingRate::where('code', $zoneCode)->value('rate') ?? 0;

            $shippingFee = $rate;
        } else {
            // 全部 digital
            $shippingFee = ShippingRate::where('code', 'digital')->value('rate') ?? 0;
        }

        $total = $subtotal + $shippingFee;

        $receiptPath = null;
        if ($request->hasFile('payment_receipt')) {
            $receiptPath = $request->file('payment_receipt')
                ->store('payment_receipts', 'public');
        }

        do {
            $orderNo = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (\App\Models\Order::where('order_no', $orderNo)->exists());

        $order = null;

        DB::transaction(function () use (
            $request,
            $items,
            $subtotal,
            $shippingFee,
            $paymentMethod,
            $receiptPath,
            $cart,
            $orderNo,
            $total,
            &$order
        ) {
            $order = Order::create([
                'order_no'            => $orderNo,
                'user_id'             => auth()->id(),
                'customer_name'       => $request->name,
                'customer_phone'      => $request->phone,
                'customer_email'      => $request->email,
                'address_line1'       => $request->address_line1,
                'address_line2'       => $request->address_line2,
                'postcode'            => $request->postcode,
                'city'                => $request->city,
                'state'               => $request->state,
                'country'             => $request->country,
                'subtotal'            => $subtotal,
                'shipping_fee'        => $shippingFee,
                'total'               => $total,
                'status'              => 'pending',
                'payment_method_code' => $paymentMethod->code,
                'payment_method_name' => $paymentMethod->name,
                'payment_receipt_path' => $receiptPath,
            ]);

            foreach ($items as $item) {
                $order->items()->create([
                    'product_id'         => $item->product_id,
                    'product_name'       => $item->product->name ?? '',
                    'qty'                => $item->qty,
                    'unit_price'         => $item->unit_price,
                    'product_variant_id' => $item->product_variant_id ?? null,
                    'variant_label'      => $item->variant_label ?? null,
                ]);
            }

            $cart->items()->delete();
        });

        // 4️⃣ 事务完成后寄 Email

        if ($order) {
            \Log::info('Checkout order created: ' . $order->order_no);
            \Log::info('Config admin_address is: ' . config('mail.admin_address'));

            try {
                if ($order->customer_email) {
                    \Log::info('Sending customer email for order: ' . $order->order_no);
                    Mail::to($order->customer_email)->send(new OrderPlacedMail($order));
                }

                if (config('mail.admin_address')) {
                    \Log::info('Sending admin email for order: ' . $order->order_no);
                    Mail::to(config('mail.admin_address'))->send(new AdminOrderNotificationMail($order));
                }
            } catch (\Throwable $e) {
                \Log::error('Order email send failed for ' . $order->order_no . ' : ' . $e->getMessage());
            }
        }


        return redirect()->route('account.orders.index')
            ->with('success', 'Order placed successfully.');
    }
}
