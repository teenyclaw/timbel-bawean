<?php

namespace App\Console\Commands;

use App\Models\MenuItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SeedMenuPhotos extends Command
{
    protected $signature = 'menu:seed-photos {--force : Overwrite existing photos}';

    protected $description = 'Copy bundled menu photos into storage and attach them to menu items';

    public function handle(): int
    {
        $assetsDir = database_path('seeders/assets/menu');
        $manifestPath = $assetsDir . '/manifest.json';

        if (! is_file($manifestPath)) {
            $this->error('Missing manifest: database/seeders/assets/menu/manifest.json');

            return self::FAILURE;
        }

        /** @var array{menu: array<string, string>} $manifest */
        $manifest = json_decode(file_get_contents($manifestPath), true, 512, JSON_THROW_ON_ERROR);
        $force = (bool) $this->option('force');

        Storage::disk('public')->makeDirectory('menu');

        $updated = 0;
        $skipped = 0;
        $missing = 0;

        foreach (MenuItem::query()->orderBy('id')->get() as $item) {
            if ($item->photo && ! $force && Storage::disk('public')->exists($item->photo)) {
                $skipped++;

                continue;
            }

            $assetFile = $this->resolveAssetFile($item->name, $manifest['menu'] ?? [], $assetsDir);
            if ($assetFile === null) {
                $this->warn("No asset for: {$item->name}");
                $missing++;

                continue;
            }

            $dest = 'menu/' . pathinfo($assetFile, PATHINFO_BASENAME);
            Storage::disk('public')->put($dest, file_get_contents($assetFile));
            $item->update(['photo' => $dest]);
            $updated++;
        }

        // #region agent log
        file_put_contents(base_path('debug-b66d57.log'), json_encode([
            'sessionId' => 'b66d57',
            'runId' => 'seed-photos',
            'hypothesisId' => 'H1,H4',
            'location' => 'SeedMenuPhotos::handle',
            'message' => 'Menu photo seed completed',
            'data' => [
                'updated' => $updated,
                'skipped' => $skipped,
                'missing' => $missing,
                'with_photo_after' => MenuItem::whereNotNull('photo')->count(),
                'total_items' => MenuItem::count(),
            ],
            'timestamp' => (int) (microtime(true) * 1000),
        ])."\n", FILE_APPEND);
        // #endregion

        $this->info("Photos seeded: {$updated} updated, {$skipped} skipped, {$missing} missing asset.");

        return self::SUCCESS;
    }

    /** @param array<string, string> $menuMap */
    private function resolveAssetFile(string $name, array $menuMap, string $assetsDir): ?string
    {
        $candidates = [Str::slug($name)];

        if (isset($menuMap[$name])) {
            $candidates[] = $menuMap[$name];
        }

        foreach (array_unique($candidates) as $base) {
            $path = $assetsDir . '/' . $base . '.jpg';
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }
}
