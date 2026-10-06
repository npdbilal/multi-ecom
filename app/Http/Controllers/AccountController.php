<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

/**
 * Customer dashboard: order history, address book, profile.
 */
class AccountController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $orders = $user->orders()->latest()->take(5)->get();
        $wishlistCount = $user->wishlistItems()->count();

        return view('account.dashboard', compact('user', 'orders', 'wishlistCount'));
    }

    public function orders()
    {
        $orders = auth()->user()->orders()->latest()->paginate(10);

        return view('account.orders', compact('orders'));
    }

    public function editProfile()
    {
        return view('account.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
        ]);

        $user->update($data);

        return back()->with('success', trans_db('admin.saved'));
    }

    // ---------------- Address book ----------------

    public function addresses()
    {
        $addresses = auth()->user()->addresses()->get();

        return view('account.addresses', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        $data = $this->validatedAddress($request);
        $data['user_id'] = auth()->id();

        if (auth()->user()->addresses()->count() === 0) {
            $data['is_default'] = true;
        }

        Address::create($data);

        return back()->with('success', trans_db('admin.saved'));
    }

    public function updateAddress(Request $request, Address $address)
    {
        $this->authorizeAddress($address);

        $address->update($this->validatedAddress($request));

        return back()->with('success', trans_db('admin.saved'));
    }

    public function destroyAddress(Address $address)
    {
        $this->authorizeAddress($address);
        $address->delete();

        return back()->with('success', trans_db('admin.deleted'));
    }

    public function setDefaultAddress(Address $address)
    {
        $this->authorizeAddress($address);

        auth()->user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('success', trans_db('admin.saved'));
    }

    protected function validatedAddress(Request $request): array
    {
        return $request->validate([
            'label' => 'nullable|string|max:50',
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:100',
        ]) + ['label' => $request->input('label', 'Home')];
    }

    protected function authorizeAddress(Address $address): void
    {
        abort_unless($address->user_id === auth()->id(), 403);
    }
}
