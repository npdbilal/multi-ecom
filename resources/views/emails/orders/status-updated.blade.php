<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Order status updated</title></head>
<body style="font-family: Arial, sans-serif; color: #222; max-width: 640px; margin: 0 auto; padding: 24px;">
    <h2 style="margin-bottom: 4px;">Order {{ $order->order_number }} update</h2>
    <p style="color: #666;">
        Hi {{ $order->customer_name }}, your order status changed
        from <strong>{{ ucfirst($oldStatus) }}</strong>
        to <strong>{{ ucfirst($order->status) }}</strong>.
    </p>

    @if($order->status === 'shipped')
        <p>📦 Your order is on its way!</p>
    @elseif($order->status === 'delivered')
        <p>✅ Your order has been delivered. Enjoy!</p>
    @elseif($order->status === 'cancelled')
        <p>We're sorry — your order was cancelled. Contact us if you have questions.</p>
    @endif

    <table style="width: 100%; border-collapse: collapse; margin: 24px 0;">
        <thead>
            <tr style="border-bottom: 2px solid #eee; text-align: left;">
                <th style="padding: 8px 0;">Item</th>
                <th style="padding: 8px 0;">Qty</th>
                <th style="padding: 8px 0; text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr style="border-bottom: 1px solid #f0f0f0;">
                    <td style="padding: 8px 0;">
                        {{ $item->product_name }}
                        @if($item->variant_label)<br><small style="color:#888;">{{ $item->variant_label }}</small>@endif
                    </td>
                    <td style="padding: 8px 0;">{{ $item->quantity }}</td>
                    <td style="padding: 8px 0; text-align: right;">${{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="font-size: 14px;">Order total: <strong>${{ number_format($order->total, 2) }}</strong></p>
</body>
</html>
