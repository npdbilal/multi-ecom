<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Media library: upload / browse / delete files stored on the public disk,
 * and attach library files to a product's image gallery.
 */
class MediaController extends Controller
{
    public function index()
    {
        $files = collect(Storage::disk('public')->files('media'))
            ->sortDesc()
            ->map(fn ($path) => [
                'path' => $path,
                'url' => media_url($path),
                'name' => basename($path),
                'size' => Storage::disk('public')->size($path),
            ]);

        return view('admin.media.index', compact('files'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'image|max:5120', // 5 MB per file
        ]);

        foreach ($request->file('files') as $file) {
            $file->store('media', 'public');
        }

        return back()->with('success', trans_db('admin.saved'));
    }

    public function destroy(string $file)
    {
        $path = 'media/'.basename($file);

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            // Detach from any product galleries (keep the product itself).
            ProductImage::where('path', $path)->delete();
        }

        return back()->with('success', trans_db('admin.deleted'));
    }

    /**
     * Attach a library file to a product's gallery.
     */
    public function attach(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'path' => 'required|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        $path = 'media/'.basename($request->path);

        if (! Storage::disk('public')->exists($path)) {
            return back()->with('error', 'File not found.');
        }

        $product->images()->create([
            'path' => $path,
            'sort_order' => $product->images()->max('sort_order') + 1,
            'is_primary' => $product->images()->count() === 0,
        ]);

        return back()->with('success', trans_db('admin.saved'));
    }
}
