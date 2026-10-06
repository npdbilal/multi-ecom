<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PluginManager;

class PluginController extends Controller
{
    public function index(PluginManager $plugins)
    {
        return view('admin.plugins.index', ['plugins' => $plugins->all()]);
    }

    public function enable(string $plugin, PluginManager $plugins)
    {
        if (! $plugins->enable($plugin)) {
            return back()->with('error', trans_db('admin.plugin_not_found'));
        }

        return back()->with('success', trans_db('admin.plugin_enabled'));
    }

    public function disable(string $plugin, PluginManager $plugins)
    {
        $plugins->disable($plugin);

        return back()->with('success', trans_db('admin.plugin_disabled'));
    }
}
