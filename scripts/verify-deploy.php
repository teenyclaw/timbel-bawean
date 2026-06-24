<?php

/**
 * Standalone deploy checker — run on hosting without Laravel bootstrap:
 *   php scripts/verify-deploy.php
 */

$root = dirname(__DIR__);

$required = [
    'artisan',
    'composer.json',
    'composer.lock',
    'public/index.php',
    'bootstrap/app.php',
    'app/View/Components/GuestLayout.php',
    'resources/views/components/guest-layout.blade.php',
    'database/seeders/DatabaseSeeder.php',
    'routes/web.php',
    'vendor/autoload.php',
    '.env',
];

$requiredDirs = [
    'app',
    'app/Http/Controllers',
    'app/Models',
    'app/Services',
    'config',
    'database/migrations',
    'resources/views',
    'storage/app',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache',
    'vendor',
];

echo "QR POS deploy check — root: {$root}\n\n";

$missing = [];

foreach ($required as $path) {
    $full = $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path);
    if (! file_exists($full)) {
        $missing[] = $path;
        echo "[MISSING] {$path}\n";
    } else {
        echo "[OK]      {$path}\n";
    }
}

foreach ($requiredDirs as $dir) {
    $full = $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $dir);
    if (! is_dir($full)) {
        $missing[] = $dir . '/';
        echo "[MISSING] {$dir}/\n";
    } else {
        echo "[OK]      {$dir}/\n";
    }
}

// Spot-check recent fixes
$seeder = @file_get_contents($root . '/database/seeders/DatabaseSeeder.php') ?: '';
if ($seeder && ! str_contains($seeder, 'use App\Models\MenuItem;')) {
    $missing[] = 'DatabaseSeeder.php (missing MenuItem import)';
    echo "[MISSING] DatabaseSeeder.php — use App\\Models\\MenuItem;\n";
}

echo "\n";
if ($missing === []) {
    echo "All critical paths present.\n";
    exit(0);
}

echo count($missing) . " issue(s) found.\n\n";
echo "Common fixes:\n";
echo "  - vendor/ missing → run: php ~/composer.phar install --no-dev -d {$root}\n";
echo "  - .env missing    → copy .env.example to .env and configure MySQL\n";
echo "  - incomplete zip  → re-upload full project or use git pull\n";
exit(1);
