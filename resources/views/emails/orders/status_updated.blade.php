@php
    $labels = [
        'pending'    => 'Pending',
        'paid'       => 'Paid',
        'processing' => 'Processing',
        'shipped'    => 'Shipped',
        'completed'  => 'Completed',
        'cancelled'  => 'Cancelled',
    ];

    $fromLabel = $labels[strtolower($oldStatus)] ?? ucfirst($oldStatus);
    $toLabel   = $labels[strtolower($newStatus)] ?? ucfirst($newStatus);

    $messages = [
        'paid'       => 'We have received your payment. Your order will be processed soon.',
        'processing' => 'Your order is now being prepared.',
        'shipped'    => 'Good news! Your order has been shipped.',
        'completed'  => 'Your order has been completed. Thank you for shopping with us.',
        'cancelled'  => 'This order has been cancelled. If this was not expected, please contact us.',
        'pending'    => 'Your order is pending. We will update you once there is any progress.',
    ];

    $statusKey  = strtolower($newStatus);
    $statusText = $messages[$statusKey] ?? 'Your order status has been updated.';

    // 🚚 快递公司 → 追踪网址前缀（你以后可以自己再加）
    $trackingBaseUrls = [
        'j&t'         => 'https://www.jtexpress.my/tracking?no=',
        'jnt'         => 'https://www.jtexpress.my/tracking?no=',
        'j&t express' => 'https://www.jtexpress.my/tracking?no=',
        'ninja van'   => 'https://www.ninjavan.co/en-my/tracking?id=',
        'ninjavan'    => 'https://www.ninjavan.co/en-my/tracking?id=',
        'poslaju'     => 'https://www.tracking.my/poslaju/',
        'pos laju'    => 'https://www.tracking.my/poslaju/',
        'flash'       => 'https://www.flashexpress.my/fle/tracking?searhword=',
        'flash express' => 'https://www.flashexpress.my/fle/tracking?searhword=',
    ];

    $trackingUrl = null;

    if (!empty($order->tracking_number)) {
        $courierKey = strtolower($order->shipping_courier ?? '');

        foreach ($trackingBaseUrls as $keyword => $baseUrl) {
            if (str_contains($courierKey, $keyword)) {
                $trackingUrl = $baseUrl . urlencode($order->tracking_number);
                break;
            }
        }
    }
@endphp

<x-mail::message>
# Order Status Updated

Hi {{ $order->customer_name }},

The status for your order **{{ $order->order_no }}** has been updated.

> **From:** {{ $fromLabel }}  
> **To:** {{ $toLabel }}

{{ $statusText }}

{{-- 🚚 当状态是 shipped / completed 且有 tracking number 时，显示快递信息 --}}
@if(in_array(strtolower($newStatus), ['shipped', 'completed']) && $order->tracking_number)
> **Courier:** {{ $order->shipping_courier ?? '-' }}  
> **Tracking No.:**
@isset($trackingUrl)
<a href="{{ $trackingUrl }}" target="_blank">{{ $order->tracking_number }}</a>
@else
{{ $order->tracking_number }}
@endisset
@endif

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
