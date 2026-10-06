<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Seeder;

/**
 * Translation keys for the ZIP theme/plugin installer.
 * Run: php artisan db:seed --class=ZipInstallerTranslationsSeeder
 */
class ZipInstallerTranslationsSeeder extends Seeder
{
    public function run(): void
    {
        $keys = [
            // Themes page
            'upload_theme' => ['en' => 'Upload Theme', 'ur' => 'تھیم اپ لوڈ کریں'],
            'activate' => ['en' => 'Activate', 'ur' => 'فعال کریں'],
            'current_theme' => ['en' => 'Current theme', 'ur' => 'موجودہ تھیم'],
            'theme_zip_help_title' => ['en' => 'How to package a theme ZIP', 'ur' => 'تھیم ZIP کیسے بنائیں'],
            'theme_zip_help' => [
                'en' => 'Upload a .zip (max 20MB) containing theme.json at its root. The theme will appear here and can be activated.',
                'ur' => 'theme.json والی .zip فائل اپ لوڈ کریں (زیادہ سے زیادہ 20MB)۔ تھیم یہاں نظر آئے گی۔',
            ],

            // Plugins page
            'upload_plugin' => ['en' => 'Upload Plugin', 'ur' => 'پلگ اِن اپ لوڈ کریں'],
            'enable' => ['en' => 'Enable', 'ur' => 'فعال کریں'],
            'disable' => ['en' => 'Disable', 'ur' => 'غیر فعال کریں'],
            'enabled' => ['en' => 'Enabled', 'ur' => 'فعال'],
            'disabled' => ['en' => 'Disabled', 'ur' => 'غیر فعال'],
            'no_plugins' => ['en' => 'No plugins installed.', 'ur' => 'کوئی پلگ اِن انسٹال نہیں۔'],
            'plugin_zip_help_title' => ['en' => 'How to package a plugin ZIP', 'ur' => 'پلگ اِن ZIP کیسے بنائیں'],
            'plugin_zip_help' => [
                'en' => 'Upload a .zip (max 20MB) containing plugin.json at its root with a valid service provider. Enable it after install to run its migrations.',
                'ur' => 'plugin.json والی .zip فائل اپ لوڈ کریں۔ انسٹال کے بعد فعال کریں۔',
            ],

            // Shared
            'name' => ['en' => 'Name', 'ur' => 'نام'],
            'description' => ['en' => 'Description', 'ur' => 'تفصیل'],
            'version' => ['en' => 'Version', 'ur' => 'ورژن'],
            'author' => ['en' => 'Author', 'ur' => 'مصنف'],
            'status' => ['en' => 'Status', 'ur' => 'حیثیت'],
            'actions' => ['en' => 'Actions', 'ur' => 'اعمال'],
            'active' => ['en' => 'Active', 'ur' => 'فعال'],
            'delete' => ['en' => 'Delete', 'ur' => 'حذف کریں'],
            'confirm_delete' => ['en' => 'Are you sure you want to delete this?', 'ur' => 'کیا آپ واقعی حذف کرنا چاہتے ہیں؟'],
        ];

        foreach ($keys as $key => $locales) {
            foreach ($locales as $code => $value) {
                Translation::updateOrCreate(
                    ['language_code' => $code, 'group' => 'admin', 'key' => $key],
                    ['value' => $value]
                );
            }
        }
    }
}
