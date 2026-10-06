<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PluginInstaller;
use App\Services\PluginManager;
use Illuminate\Http\Request;

/**
 * Admin → Plugins: list installed plugins, upload ZIP,
 * enable/disable, delete.
 */
class PluginController extends Controller
{
    public function __construct(
        protected PluginManager $plugins,
        protected PluginInstaller $installer
    ) {}

    /** GET /admin/plugins */
    public function index()
    {
        return view('admin.plugins.index', [
            'plugins' => $this->plugins->all(),
        ]);
    }

    /** POST /admin/plugins/upload */
    public function upload(Request $request)
    {
        $request->validate([
            'plugin_zip' => 'required|file|mimes:zip|max:20480', // 20MB
        ]);

        try {
            $name = $this->installer->install($request->file('plugin_zip'));
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', "Plugin \"{$name}\" installed. Enable it to activate.");
    }

    /** POST /admin/plugins/{name}/enable */
    public function enable(string $name)
    {
        if (! $this->plugins->enable($name)) {
            return back()->with('error', 'Plugin not found.');
        }

        // Run the plugin's migrations on enable
        try {
            $this->installer->migrate($name);
        } catch (\Throwable $e) {
            // Don't fail the enable if migrations error; admin can retry
            return back()->with('warning', "Plugin enabled, but migrations had an issue: {$e->getMessage()}");
        }

        return back()->with('success', "Plugin \"{$name}\" enabled.");
    }

    /** POST /admin/plugins/{name}/disable */
    public function disable(string $name)
    {
        $this->plugins->disable($name);

        return back()->with('success', "Plugin \"{$name}\" disabled. Its data was kept.");
    }

    /** DELETE /admin/plugins/{name} */
    public function destroy(string $name)
    {
        try {
            $this->installer->delete($name, $this->plugins);
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Plugin deleted.');
    }
}
