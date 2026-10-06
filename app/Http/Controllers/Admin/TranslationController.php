<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Translation;
use App\Services\TranslationService;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    public function index(Request $request)
    {
        $languages = Language::orderBy('sort_order')->get();
        $groups = Translation::distinct()->pluck('group');

        $selectedLanguage = $request->get('language', Language::default()?->code);
        $selectedGroup = $request->get('group');
        $search = $request->get('q');

        $query = Translation::with('language')->orderBy('group')->orderBy('key');

        if ($selectedLanguage) {
            $query->where('language_code', $selectedLanguage);
        }

        if ($selectedGroup) {
            $query->where('group', $selectedGroup);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('key', 'like', "%{$search}%")
                    ->orWhere('value', 'like', "%{$search}%");
            });
        }

        $translations = $query->paginate(25)->withQueryString();

        // Missing-translation report: for each active non-default language,
        // count keys present in default but absent there.
        $missing = [];
        foreach ($languages->where('is_active', true) as $lang) {
            $missing[$lang->code] = count(Translation::missingFor($lang->code));
        }

        return view('admin.translations.index', compact(
            'languages', 'groups', 'translations',
            'selectedLanguage', 'selectedGroup', 'search', 'missing'
        ));
    }

    public function create()
    {
        $languages = Language::active()->orderBy('sort_order')->get();
        $groups = Translation::distinct()->pluck('group');

        return view('admin.translations.create', compact('languages', 'groups'));
    }

    public function store(Request $request, TranslationService $service)
    {
        $data = $request->validate([
            'group' => 'required|string|max:50|regex:/^[a-z0-9_]+$/',
            'key' => 'required|string|max:150|regex:/^[a-z0-9_.]+$/',
            'values' => 'required|array',
            'values.*' => 'nullable|string',
        ]);

        foreach ($data['values'] as $locale => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            $service->set("{$data['group']}.{$data['key']}", $locale, $value);
        }

        return redirect()->route('admin.translations.index')->with('success', trans_db('admin.saved'));
    }

    public function edit(Translation $translation)
    {
        return view('admin.translations.edit', compact('translation'));
    }

    public function update(Request $request, Translation $translation, TranslationService $service)
    {
        $data = $request->validate([
            'value' => 'required|string',
        ]);

        $service->set(
            $translation->group.'.'.$translation->key,
            $translation->language_code,
            $data['value']
        );

        return redirect()->route('admin.translations.index')->with('success', trans_db('admin.saved'));
    }

    public function destroy(Translation $translation, TranslationService $service)
    {
        $translation->delete();
        $service->flushCache();

        return back()->with('success', trans_db('admin.deleted'));
    }

    /**
     * Copy missing keys from the default language as empty placeholders so
     * translators can see exactly what needs translating.
     */
    public function syncMissing(Request $request, TranslationService $service)
    {
        $request->validate(['language' => 'required|exists:languages,code']);

        $code = $request->language;
        $created = 0;

        foreach (Translation::missingFor($code) as $fullKey) {
            [$group, $key] = explode('.', $fullKey, 2) + [null, null];

            // Copy the default value as a starting point, prefixed for visibility.
            $defaultValue = $service->get($fullKey);

            Translation::create([
                'language_code' => $code,
                'group' => $group,
                'key' => $key,
                'value' => $defaultValue,
            ]);

            $created++;
        }

        $service->flushCache();

        return back()->with('success', trans_db('admin.translations_synced', null, ['count' => $created]));
    }
}
