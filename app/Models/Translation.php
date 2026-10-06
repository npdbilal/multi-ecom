<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use HasFactory;

    protected $fillable = [
        'language_code',
        'group',
        'key',
        'value',
    ];

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_code', 'code');
    }

    /**
     * Full dotted key, e.g. "shop.add_to_cart".
     */
    public function fullKey(): string
    {
        return $this->group.'.'.$this->key;
    }

    /**
     * Keys that exist in the default language but are missing for $code.
     */
    public static function missingFor(string $code): array
    {
        $default = Language::default()?->code ?? config('app.fallback_locale');

        if ($code === $default) {
            return [];
        }

        $defaultKeys = static::where('language_code', $default)
            ->get()
            ->map(fn ($t) => $t->fullKey())
            ->all();

        $translatedKeys = static::where('language_code', $code)
            ->get()
            ->map(fn ($t) => $t->fullKey())
            ->all();

        return array_values(array_diff($defaultKeys, $translatedKeys));
    }
}
