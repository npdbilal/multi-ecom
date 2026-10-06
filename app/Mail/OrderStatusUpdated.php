<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Sent when an admin changes an order's status. Queue-ready.
 */
class OrderStatusUpdated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public string $oldStatus,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order '.$this->order->order_number.' — status: '.ucfirst($this->order->status),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.status-updated',
            with: [
                'order' => $this->order->load('items.product'),
                'oldStatus' => $this->oldStatus,
            ],
        );
    }
}
