<?php

/**
 * Diagnose menu photo issues (run on hosting):
 *   cd ~/bawean && php scripts/diagnose-menu-photos.php
 */

$root = dirname(__DIR__);
require $root . '/vendor/autoload.php';
$app = require $root . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\MenuItem;
use Illuminate\Support\Facades\Storage;

$logFile = $root . '/debug-b66d57.log';

echo "=== Menu photo diagnostics ===\n\n";

$total = MenuItem::count();
$withColumn = MenuItem::whereNotNull('photo')->count();
$withFile = 0;
$broken = [];

foreach (MenuItem::orderBy('name')->get() as $item) {
    if (! $item->photo) {
        $broken[] = "{$item->name}: no photo in DB";

        continue;
    }
    if (Storage::disk('public')->exists($item->photo)) {
        $withFile++;
    } else {
        $broken[] = "{$item->name}: DB={$item->photo} but file missing";
    }
}

$linkOk = is_link($root . '/public/storage') || is_dir($root . '/public/storage/menu');
$assetsCount = is_dir($root . '/database/seeders/assets/menu')
    ? count(glob($root . '/database/seeders/assets/menu/*.jpg'))
    : 0;

echo "Total menu items:     {$total}\n";
echo "DB photo column set:  {$withColumn}\n";
echo "File exists on disk:  {$withFile}\n";
echo "Bundled JPG assets:   {$assetsCount}\n";
echo "public/storage link:  " . ($linkOk ? 'OK' : 'MISSING — run: php artisan storage:link') . "\n";
echo "APP_URL:              " . config('app.url') . "\n\n";

if ($broken !== []) {
    echo "Issues (" . count($broken) . "):\n";
    foreach (array_slice($broken, 0, 10) as $line) {
        echo "  - {$line}\n";
    }
    if (count($broken) > 10) {
        echo '  ... and ' . (count($broken) - 10) . " more\n";
    }
} else {
    echo "All items have photo files.\n";
}

echo "\nFix: php artisan menu:seed-photos\n";
echo "     php artisan storage:link\n";

// #region agent log
@file_put_contents($logFile, json_encode([
    'sessionId' => 'b66d57',
    'hypothesisId' => 'H1,H2,H4',
    'location' => 'diagnose-menu-photos.php',
    'message' => 'CLI menu photo diagnostics',
    'data' => [
        'total' => $total,
        'db_with_photo' => $withColumn,
        'file_exists' => $withFile,
        'broken_count' => count($broken),
        'assets_count' => $assetsCount,
        'public_storage_ok' => $linkOk,
    ],
    'timestamp' => (int) (microtime(true) * 1000),
], JSON_UNESCAPED_SLASHES) . "\n", FILE_APPEND);
// #endregion

echo "\nLog: debug-b66d57.log\n";
