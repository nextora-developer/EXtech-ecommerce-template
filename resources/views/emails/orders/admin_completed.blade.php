<x-mail::message>
# ✅ Order Marked as Completed

A customer has confirmed they have received the order.

---

## 🧾 Order Info

- **Order No:** {{ $order->order_no }}
- **Customer:** {{ $order->customer_name }} ({{ $order->customer_email }})
- **Status:** {{ ucfirst($oldStatus) }} → **{{ ucfirst($newStatus) }}**
- **Confirmed At:** {{ now()->format('Y-m-d H:i') }}

<x-mail::panel>
Subtotal: RM {{ number_format($order->subtotal, 2) }}  
Shipping: RM {{ number_format($order->shipping_fee, 2) }}  
**Total Paid: RM {{ number_format($order->total, 2) }}**
</x-mail::panel>

<x-mail::button :url="route('admin.orders.show', $order)">
Open in Admin Panel
</x-mail::button>

Thanks,  
{{ config('app.name') }} System
</x-mail::message>
