<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ThemeInstaller;
use App\Services\ThemeManager;
use Illuminate\Http\Request;

/**
 * Admin → Themes: list installed themes, upload ZIP, activate, delete.
 */
class ThemeController extends Controller
{
    public function __construct(
        protected ThemeManager $themes,
        protected ThemeInstaller $installer
    ) {}

    /** GET /admin/themes */
    public function index()
    {
        return view('admin.themes.index', [
            'themes' => $this->themes->all(),
            'active' => $this->themes->active(),
        ]);
    }

    /** POST /admin/themes/upload */
    public function upload(Request $request)
    {
        $request->validate([
            'theme_zip' => 'required|file|mimes:zip|max:20480', // 20MB
        ]);

        try {
            $slug = $this->installer->install($request->file('theme_zip'));
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', "Theme installed. You can now activate it.");
    }

    /** POST /admin/themes/{slug}/activate */
    public function activate(string $slug)
    {
        if (! $this->themes->activate($slug)) {
            return back()->with('error', 'Theme not found.');
        }

        return back()->with('success', 'Theme activated.');
    }

    /** DELETE /admin/themes/{slug} */
    public function destroy(string $slug)
    {
        try {
            $this->installer->delete($slug, $this->themes);
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Theme deleted.');
    }
}
