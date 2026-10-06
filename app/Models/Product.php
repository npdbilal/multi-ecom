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

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class)->orderBy('sort_order');
    }

    public function activeVariants()
    {
        return $this->variants()->where('is_active', true);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->reviews()->approved()->latest();
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
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
        if ($this->activeVariants()->exists()) {
            return $this->activeVariants()->sum('stock') > 0;
        }

        return $this->stock > 0;
    }

    /**
     * Primary display image: first primary/ordered gallery image,
     * falling back to the legacy `image` column.
     */
    public function displayImage(): ?string
    {
        $image = $this->relationLoaded('images')
            ? $this->images->first()
            : $this->images()->first();

        if ($image) {
            return $image->url();
        }

        return $this->image;
    }

    /**
     * Average rating (1–5) across approved reviews, 0 when none.
     */
    public function averageRating(): float
    {
        return round((float) $this->approvedReviews()->avg('rating'), 1);
    }

    public function reviewsCount(): int
    {
        return $this->approvedReviews()->count();
    }

    /**
     * Whether the product is low on stock (uses the low_stock_threshold setting).
     */
    public function isLowStock(): bool
    {
        $threshold = (int) setting('low_stock_threshold', 5);

        if ($this->activeVariants()->exists()) {
            return $this->activeVariants()->sum('stock') <= $threshold;
        }

        return $this->stock <= $threshold;
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
