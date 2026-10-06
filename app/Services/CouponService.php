<?php

namespace App\Services;

use App\Models\Coupon;

/**
 * Coupon validation & discount math, shared by the cart and checkout.
 * The applied coupon code is kept in the session under "coupon_code".
 */
class CouponService
{
    public const SESSION_KEY = 'coupon_code';

    /**
     * Validate a code against the current cart subtotal.
     *
     * @return array{valid: bool, message: string, coupon: ?Coupon, discount: float}
     */
    public function validate(string $code, float $subtotal): array
    {
        $coupon = Coupon::findByCode($code);

        if (! $coupon) {
            return $this->fail('Invalid coupon code.');
        }

        if (! $coupon->isUsable($subtotal)) {
            return $this->fail($this->reason($coupon, $subtotal), $coupon);
        }

        return [
            'valid' => true,
            'message' => 'Coupon applied: '.$coupon->label().'.',
            'coupon' => $coupon,
            'discount' => $coupon->discountFor($subtotal),
        ];
    }

    /**
     * Discount for the coupon currently in session (0 when none/invalid).
     *
     * @return array{coupon: ?Coupon, discount: float}
     */
    public function fromSession(float $subtotal): array
    {
        $code = session(self::SESSION_KEY);

        if (! $code) {
            return ['coupon' => null, 'discount' => 0.0];
        }

        $result = $this->validate($code, $subtotal);

        if (! $result['valid']) {
            $this->clear();

            return ['coupon' => null, 'discount' => 0.0];
        }

        return ['coupon' => $result['coupon'], 'discount' => $result['discount']];
    }

    public function apply(string $code): void
    {
        session([self::SESSION_KEY => strtoupper(trim($code))]);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    /**
     * Increment usage counter after a successful order.
     */
    public function markUsed(?Coupon $coupon): void
    {
        if ($coupon) {
            $coupon->increment('used_count');
        }

        $this->clear();
    }

    protected function fail(string $message, ?Coupon $coupon = null): array
    {
        return ['valid' => false, 'message' => $message, 'coupon' => $coupon, 'discount' => 0.0];
    }

    protected function reason(Coupon $coupon, float $subtotal): string
    {
        if (! $coupon->is_active) {
            return 'This coupon is no longer active.';
        }

        $now = now();

        if (($coupon->starts_at && $now->lt($coupon->starts_at))
            || ($coupon->ends_at && $now->gt($coupon->ends_at))) {
            return 'This coupon is not valid at this time.';
        }

        if ($subtotal < (float) $coupon->min_order) {
            return 'This coupon requires a minimum order of $'.number_format((float) $coupon->min_order, 2).'.';
        }

        if ($coupon->max_uses !== null && $coupon->used_count >= $coupon->max_uses) {
            return 'This coupon has reached its usage limit.';
        }

        return 'This coupon cannot be applied.';
    }
}
