<x-mail::message>
# 🛎 New Order Received

A new order has just been placed on **{{ config('app.name') }}**.

---

## 🧾 Order Summary

- **Order No:** {{ $order->order_no }}
- **Placed At:** {{ $order->created_at->format('Y-m-d H:i') }}
- **Status:** {{ ucfirst($order->status) }}
- **Payment Method:** {{ $order->payment_method_name }} ({{ $order->payment_method_code }})

<x-mail::panel>
Subtotal: **RM {{ number_format($order->subtotal, 2) }}**  
Shipping: **RM {{ number_format($order->shipping_fee, 2) }}**  
**Grand Total: RM {{ number_format($order->total, 2) }}**
</x-mail::panel>

---

## 👤 Customer Details

- **Name:** {{ $order->customer_name }}
- **Phone:** {{ $order->customer_phone }}
- **Email:** {{ $order->customer_email }}

---

## 📦 Shipping Address

{{ $order->address_line1 }}  
@if($order->address_line2)
{{ $order->address_line2 }}  
@endif
{{ $order->postcode }} {{ $order->city }}, {{ $order->state }}  
{{ $order->country }}

---

## 🛍 Items

<x-mail::table>
| # | Product | Variant | Qty | Unit Price | Line Total |
|:-:|:--------|:--------|:---:|-----------:|-----------:|
@foreach ($order->items as $index => $item)
| {{ $index + 1 }} | {{ $item->product_name }} | {{ $item->variant_label ?? '-' }} | {{ $item->qty }} | RM {{ number_format($item->unit_price, 2) }} | RM {{ number_format($item->unit_price * $item->qty, 2) }} |
@endforeach
</x-mail::table>


You can review this order in your admin panel.

Thanks,  
{{ config('app.name') }} Admin
</x-mail::message>
