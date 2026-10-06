<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(15);

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.form');
    }

    public function store(Request $request)
    {
        Coupon::create($this->validated($request));

        return redirect()->route('admin.coupons.index')->with('success', trans_db('admin.saved'));
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.form', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $coupon->update($this->validated($request, $coupon));

        return redirect()->route('admin.coupons.index')->with('success', trans_db('admin.saved'));
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')->with('success', trans_db('admin.deleted'));
    }

    protected function validated(Request $request, ?Coupon $coupon = null): array
    {
        $data = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,'.($coupon?->id ?? 'NULL'),
            'type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0',
            'min_order' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'boolean',
        ]);

        $data['code'] = strtoupper(trim($data['code']));
        $data['min_order'] = $data['min_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
