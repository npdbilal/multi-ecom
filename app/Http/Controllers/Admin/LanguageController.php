<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = Language::orderBy('sort_order')->get();

        return view('admin.languages.index', compact('languages'));
    }

    public function create()
    {
        return view('admin.languages.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:10|unique:languages,code|regex:/^[a-z]{2,3}(-[A-Z]{2})?$/',
            'name' => 'required|string|max:100',
            'native_name' => 'nullable|string|max:100',
            'is_rtl' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $data['is_rtl'] = $request->boolean('is_rtl');
        $data['is_active'] = true;

        $language = Language::create($data);

        // First language becomes default automatically.
        if (Language::count() === 1) {
            $language->makeDefault();
        }

        app(\App\Services\TranslationService::class)->flushCache();

        return redirect()->route('admin.languages.index')->with('success', trans_db('admin.saved'));
    }

    public function edit(Language $language)
    {
        return view('admin.languages.edit', compact('language'));
    }

    public function update(Request $request, Language $language)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'native_name' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
        ]);

        $data['is_rtl'] = $request->boolean('is_rtl');
        $data['is_active'] = $request->boolean('is_active');

        // The default language cannot be deactivated.
        if ($language->is_default) {
            $data['is_active'] = true;
        }

        $language->update($data);

        app(\App\Services\TranslationService::class)->flushCache();

        return redirect()->route('admin.languages.index')->with('success', trans_db('admin.saved'));
    }

    public function destroy(Language $language)
    {
        abort_if($language->is_default, 422, 'Cannot delete the default language.');

        $language->translations()->delete();
        $language->delete();

        app(\App\Services\TranslationService::class)->flushCache();

        return redirect()->route('admin.languages.index')->with('success', trans_db('admin.deleted'));
    }

    public function makeDefault(Language $language)
    {
        abort_unless($language->is_active, 422);

        $language->makeDefault();

        app(\App\Services\TranslationService::class)->flushCache();

        return back()->with('success', trans_db('admin.saved'));
    }

    public function toggle(Language $language)
    {
        abort_if($language->is_default && $language->is_active, 422, 'Cannot deactivate the default language.');

        $language->update(['is_active' => ! $language->is_active]);

        app(\App\Services\TranslationService::class)->flushCache();

        return back()->with('success', trans_db('admin.saved'));
    }
}
