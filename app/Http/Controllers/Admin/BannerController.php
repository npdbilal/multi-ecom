<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->latest()->paginate(15);

        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.form');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image_file')) {
            $data['image'] = $request->file('image_file')->store('media/banners', 'public');
        }

        Banner::create($data);

        return redirect()->route('admin.banners.index')->with('success', trans_db('admin.saved'));
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.form', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image_file')) {
            $data['image'] = $request->file('image_file')->store('media/banners', 'public');
        } elseif (empty($data['image'])) {
            unset($data['image']);
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', trans_db('admin.saved'));
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', trans_db('admin.deleted'));
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'image' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|max:5120',
            'link' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]) + [
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $request->input('sort_order', 0),
        ];
    }
}
