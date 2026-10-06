<?php

namespace Plugins\MultiVendor\Models;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    protected $fillable = [
        'vendor_id',
        'order_id',
        'order_item_id',
        'gross',
        'commission_rate',
        'commission',
        'earning',
    ];

    protected function casts(): array
    {
        return [
            'gross' => 'decimal:2',
            'commission_rate' => 'decimal:2',
            'commission' => 'decimal:2',
            'earning' => 'decimal:2',
        ];
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
