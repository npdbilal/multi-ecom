<?php

namespace Plugins\MultiVendor\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Plugins\MultiVendor\Models\Vendor;
use Symfony\Component\HttpFoundation\Response;

class VendorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $vendor = $request->user() ? Vendor::forUser($request->user()->id) : null;

        if (! $vendor || ! $vendor->isApproved()) {
            return redirect()->route('vendor.register')
                ->with('error', 'Vendor access requires an approved vendor account.');
        }

        // Share the vendor with views/controllers.
        $request->attributes->set('vendor', $vendor);
        view()->share('currentVendor', $vendor);

        return $next($request);
    }
}
