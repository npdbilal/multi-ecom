<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'vendor_id',
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'compare_price',
        'sku',
        'stock',
        'image',
        'images',
        'is_active',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'images' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Vendor relationship — populated when the MultiVendor plugin is
     * enabled (products carry a vendor_id). The plugin's Vendor model
     * always exists in the codebase, so this relation is safe in core.
     */
    public function vendor()
    {
        return $this->belongsTo(\Plugins\MultiVendor\Models\Vendor::class, 'vendor_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function inStock(): bool
    {
        return $this->stock > 0;
    }

    public function discountPercent(): ?int
    {
        if (! $this->compare_price || $this->compare_price <= $this->price) {
            return null;
        }

        return (int) round((1 - $this->price / $this->compare_price) * 100);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
