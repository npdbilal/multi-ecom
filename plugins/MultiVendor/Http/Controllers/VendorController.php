<?php

namespace Plugins\MultiVendor\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Plugins\MultiVendor\Models\Vendor;

class VendorController extends Controller
{
    public function showRegister()
    {
        $existing = Vendor::forUser(auth()->id());

        return view('multivendor::register', compact('existing'));
    }

    public function register(Request $request)
    {
        if (Vendor::forUser(auth()->id())) {
            return back()->with('error', 'You already have a vendor application.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|string|max:500',
        ]);

        Vendor::create([
            'user_id' => auth()->id(),
            'name' => $data['name'],
            'slug' => Str::slug($data['name']).'-'.Str::random(6),
            'description' => $data['description'] ?? null,
            'logo' => $data['logo'] ?? null,
            'status' => Vendor::STATUS_PENDING,
        ]);

        return redirect()->route('vendor.register')
            ->with('success', 'Application submitted! We will review it shortly.');
    }
}
