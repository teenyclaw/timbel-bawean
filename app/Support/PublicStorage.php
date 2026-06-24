<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class PublicStorage
{
    /** Copy a file from storage/app/public to public/storage (for hosts without symlink). */
    public static function publish(string $relativePath): bool
    {
        $relativePath = ltrim($relativePath, '/');
        $source = Storage::disk('public')->path($relativePath);

        if (! is_file($source)) {
            return false;
        }

        $target = public_path('storage/' . $relativePath);
        $dir = dirname($target);

        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        return copy($source, $target);
    }

    public static function isWebAccessible(string $relativePath): bool
    {
        return is_file(public_path('storage/' . ltrim($relativePath, '/')));
    }

    public static function webUrl(string $relativePath): string
    {
        return '/storage/' . ltrim($relativePath, '/');
    }
}
