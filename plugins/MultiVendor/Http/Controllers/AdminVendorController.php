<?php

namespace Plugins\MultiVendor\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Plugins\MultiVendor\Models\Vendor;

class AdminVendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::with('user')->latest()->paginate(15);

        return view('multivendor::admin.index', compact('vendors'));
    }

    public function approve(Vendor $vendor)
    {
        $vendor->update(['status' => Vendor::STATUS_APPROVED]);

        return back()->with('success', 'Vendor approved.');
    }

    public function reject(Vendor $vendor)
    {
        $vendor->update(['status' => Vendor::STATUS_REJECTED]);

        return back()->with('success', 'Vendor rejected.');
    }

    public function suspend(Vendor $vendor)
    {
        $vendor->update(['status' => Vendor::STATUS_SUSPENDED]);

        return back()->with('success', 'Vendor suspended.');
    }

    public function settings()
    {
        $rate = setting('multivendor.commission_rate', 10);

        return view('multivendor::admin.settings', compact('rate'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        setting(['multivendor.commission_rate' => $data['commission_rate']]);

        return back()->with('success', 'Commission settings saved.');
    }
}
