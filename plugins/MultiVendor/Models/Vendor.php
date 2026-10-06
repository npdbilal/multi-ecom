<?php

namespace Plugins\MultiVendor\Models;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_SUSPENDED = 'suspended';

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'logo',
        'status',
        'commission_rate',
    ];

    protected function casts(): array
    {
        return [
            'commission_rate' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function totalEarnings(): float
    {
        return (float) $this->commissions()->sum('earning');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function forUser(int $userId): ?self
    {
        return static::where('user_id', $userId)->first();
    }
}
