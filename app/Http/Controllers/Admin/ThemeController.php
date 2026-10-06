<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ThemeManager;

class ThemeController extends Controller
{
    public function index(ThemeManager $themes)
    {
        return view('admin.themes.index', ['themes' => $themes->all()]);
    }

    public function activate(string $theme, ThemeManager $themes)
    {
        if (! $themes->activate($theme)) {
            return back()->with('error', trans_db('admin.theme_not_found'));
        }

        return back()->with('success', trans_db('admin.theme_activated'));
    }
}
