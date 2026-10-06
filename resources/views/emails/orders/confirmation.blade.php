<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Order confirmed</title></head>
<body style="font-family: Arial, sans-serif; color: #222; max-width: 640px; margin: 0 auto; padding: 24px;">
    <h2 style="margin-bottom: 4px;">Thank you, {{ $order->customer_name }}!</h2>
    <p style="color: #666;">Your order <strong>{{ $order->order_number }}</strong> has been received and is being processed.</p>

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

    <table style="width: 100%; max-width: 320px; margin-left: auto; font-size: 14px;">
        <tr><td style="padding: 4px 0; color: #666;">Subtotal</td><td style="text-align: right;">${{ number_format($order->subtotal, 2) }}</td></tr>
        @if($order->discount > 0)
            <tr><td style="padding: 4px 0; color: #666;">Discount{{ $order->coupon_code ? ' ('.$order->coupon_code.')' : '' }}</td><td style="text-align: right;">−${{ number_format($order->discount, 2) }}</td></tr>
        @endif
        <tr><td style="padding: 4px 0; color: #666;">Shipping</td><td style="text-align: right;">${{ number_format($order->shipping_cost, 2) }}</td></tr>
        @if($order->tax > 0)
            <tr><td style="padding: 4px 0; color: #666;">Tax</td><td style="text-align: right;">${{ number_format($order->tax, 2) }}</td></tr>
        @endif
        <tr style="font-weight: bold; font-size: 16px;"><td style="padding: 8px 0;">Total</td><td style="text-align: right;">${{ number_format($order->total, 2) }}</td></tr>
    </table>

    <p style="color: #888; font-size: 12px; margin-top: 32px;">Payment method: {{ $order->payment_method }} · We'll email you again when your order ships.</p>
</body>
</html>
