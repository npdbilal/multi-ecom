<?php

namespace App\Http\Controllers;

use App\Models\Language;

/**
 * Frontend language switcher — stores the choice in the session.
 * SetLocale middleware picks it up on the next request.
 */
class LanguageController extends Controller
{
    public function switch(string $code)
    {
        $language = Language::active()->where('code', $code)->first();

        if ($language) {
            session(['locale' => $language->code]);
        }

        return redirect()->back();
    }
}
