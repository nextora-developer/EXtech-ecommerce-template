<x-mail::message>
# Thank you for your order!

Hi {{ $order->customer_name }},

We have received your order **{{ $order->order_no }}**.

<x-mail::panel>
Subtotal: RM {{ number_format($order->subtotal, 2) }}  
Shipping: RM {{ number_format($order->shipping_fee, 2) }}  
**Total: RM {{ number_format($order->total, 2) }}**
</x-mail::panel>

<x-mail::button :url="route('account.orders.index')">
View My Orders
</x-mail::button>

Thanks,  
{{ config('app.name') }}
</x-mail::message>
