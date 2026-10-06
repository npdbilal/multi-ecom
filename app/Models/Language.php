<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'native_name',
        'is_default',
        'is_active',
        'is_rtl',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'is_active' => 'boolean',
            'is_rtl' => 'boolean',
        ];
    }

    public function translations()
    {
        return $this->hasMany(Translation::class, 'language_code', 'code');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public static function default(): ?self
    {
        return static::where('is_default', true)->first()
            ?? static::where('code', config('app.fallback_locale'))->first();
    }

    /**
     * Make this language the default. Only one default may exist.
     */
    public function makeDefault(): void
    {
        static::query()->update(['is_default' => false]);
        $this->update(['is_default' => true]);
    }

    public function getRouteKeyName(): string
    {
        return 'code';
    }
}
