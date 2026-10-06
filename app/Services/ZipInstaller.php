<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use ZipArchive;

/**
 * WordPress-style ZIP installer base.
 *
 * Handles secure ZIP upload → validation → extraction for themes & plugins.
 *
 * Security:
 *  - Max file size enforced (default 20MB)
 *  - Only .zip files accepted
 *  - Path traversal blocked: entries with "..", absolute paths, or
 *    drive letters are rejected
 *  - Manifest (theme.json / plugin.json) must exist at package root
 *    (supports one level of wrapper folder, like WordPress)
 *  - Slug is sanitized to [a-z0-9-] before any filesystem use
 */
abstract class ZipInstaller
{
    /** Max upload size in bytes (20MB). */
    protected int $maxSize = 20971520;

    /** Manifest filename, e.g. "theme.json" or "plugin.json". */
    abstract protected function manifestFile(): string;

    /** Base install path, e.g. base_path('themes'). */
    abstract protected function installPath(): string;

    /**
     * Validate the uploaded ZIP and return [manifest array, temp extract path].
     *
     * @throws \InvalidArgumentException on any validation failure
     */
    protected function validateAndExtract(UploadedFile $file): array
    {
        // 1. Basic file checks
        if (! $file->isValid()) {
            throw new \InvalidArgumentException('Upload failed. Please try again.');
        }

        if ($file->getSize() > $this->maxSize) {
            throw new \InvalidArgumentException('ZIP is too large. Maximum size is 20MB.');
        }

        if (strtolower($file->getClientOriginalExtension()) !== 'zip') {
            throw new \InvalidArgumentException('Only .zip files are allowed.');
        }

        $zip = new ZipArchive();
        $res = $zip->open($file->getRealPath());

        if ($res !== true) {
            throw new \InvalidArgumentException('Could not open ZIP archive.');
        }

        // 2. Scan entries for path traversal BEFORE extracting
        $entries = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);

            if ($name === false || $name === '') {
                continue;
            }

            // Normalize separators
            $name = str_replace('\\', '/', $name);

            // Block traversal / absolute paths / drive letters / null bytes
            if (str_contains($name, '..')
                || str_starts_with($name, '/')
                || preg_match('#^[a-zA-Z]:#', $name)
                || str_contains($name, "\0")) {
                $zip->close();
                throw new \InvalidArgumentException('ZIP contains unsafe paths and was rejected.');
            }

            $entries[] = $name;
        }

        if (empty($entries)) {
            $zip->close();
            throw new \InvalidArgumentException('ZIP archive is empty.');
        }

        // 3. Extract to temp dir
        $tmpDir = storage_path('app/tmp/install_'.uniqid());

        if (! File::makeDirectory($tmpDir, 0755, true)) {
            $zip->close();
            throw new \InvalidArgumentException('Could not create temp directory.');
        }

        if (! $zip->extractTo($tmpDir)) {
            $zip->close();
            File::deleteDirectory($tmpDir);
            throw new \InvalidArgumentException('Failed to extract ZIP archive.');
        }

        $zip->close();

        // 4. Locate manifest — at root, or one wrapper folder deep
        $manifestName = $this->manifestFile();
        $packageRoot = null;

        if (File::exists($tmpDir.'/'.$manifestName)) {
            $packageRoot = $tmpDir;
        } else {
            foreach (File::directories($tmpDir) as $dir) {
                if (File::exists($dir.'/'.$manifestName)) {
                    $packageRoot = $dir;
                    break;
                }
            }
        }

        if (! $packageRoot) {
            File::deleteDirectory($tmpDir);
            throw new \InvalidArgumentException(
                "Invalid package: {$manifestName} not found at ZIP root."
            );
        }

        // 5. Parse & validate manifest
        $manifest = json_decode(File::get($packageRoot.'/'.$manifestName), true);

        if (! is_array($manifest) || empty($manifest['name'])) {
            File::deleteDirectory($tmpDir);
            throw new \InvalidArgumentException("Invalid {$manifestName}: missing required \"name\".");
        }

        $this->validateManifest($manifest, $tmpDir, $packageRoot);

        return [$manifest, $packageRoot, $tmpDir];
    }

    /**
     * Hook for type-specific manifest validation.
     *
     * @throws \InvalidArgumentException
     */
    protected function validateManifest(array $manifest, string $tmpDir, string $packageRoot): void
    {
        // Base: nothing extra. Subclasses add checks.
    }

    /**
     * Sanitize a slug for filesystem use: lowercase alnum + dashes.
     */
    protected function sanitizeSlug(string $slug): string
    {
        $slug = strtolower($slug);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');

        if ($slug === '') {
            throw new \InvalidArgumentException('Invalid slug in manifest.');
        }

        return $slug;
    }

    /**
     * Move the validated package root into its final destination.
     * Returns the installed slug.
     */
    protected function moveIntoPlace(string $packageRoot, string $tmpDir, string $slug): string
    {
        $dest = $this->installPath().'/'.$slug;

        if (File::exists($dest)) {
            File::deleteDirectory($tmpDir);
            throw new \InvalidArgumentException("A package with slug \"{$slug}\" is already installed.");
        }

        if (! File::makeDirectory($this->installPath(), 0755, true, true)) {
            File::deleteDirectory($tmpDir);
            throw new \InvalidArgumentException('Could not create install directory.');
        }

        if (! File::move($packageRoot, $dest)) {
            File::deleteDirectory($tmpDir);
            throw new \InvalidArgumentException('Failed to install package.');
        }

        // Clean temp
        File::deleteDirectory($tmpDir);

        return $slug;
    }
}
