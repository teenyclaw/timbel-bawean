<?php

/**
 * Hosting environment check (no Composer / Laravel bootstrap required):
 *   cd ~/bawean && php scripts/host-env-check.php
 */

$root = dirname(__DIR__);
$logFile = $root . '/debug-b66d57.log';

// #region agent log
$writeLog = static function (array $payload) use ($logFile): void {
    $payload['sessionId'] = 'b66d57';
    $payload['timestamp'] = (int) (microtime(true) * 1000);
    @file_put_contents($logFile, json_encode($payload, JSON_UNESCAPED_SLASHES) . "\n", FILE_APPEND);
};
// #endregion

echo "=== Host environment check ===\n";
echo "Project root: {$root}\n\n";

$phpVersion = PHP_VERSION;
echo "[PHP] {$phpVersion}\n";
$writeLog([
    'hypothesisId' => 'H5',
    'location' => 'host-env-check.php',
    'message' => 'PHP CLI available',
    'data' => ['php_version' => $phpVersion, 'sapi' => PHP_SAPI],
]);

$composerCandidates = [
    'composer (PATH)' => trim((string) shell_exec('command -v composer 2>/dev/null')),
    '~/composer.phar' => (is_file(getenv('HOME') . '/composer.phar') ? getenv('HOME') . '/composer.phar' : ''),
    '/usr/local/bin/composer' => (is_file('/usr/local/bin/composer') ? '/usr/local/bin/composer' : ''),
    '/opt/cpanel/composer/bin/composer' => (is_file('/opt/cpanel/composer/bin/composer') ? '/opt/cpanel/composer/bin/composer' : ''),
];

$foundComposer = null;
echo "\n[Composer search]\n";
foreach ($composerCandidates as $label => $path) {
    if ($path !== '') {
        echo "  FOUND  {$label} → {$path}\n";
        $foundComposer ??= $path;
    } else {
        echo "  —      {$label}\n";
    }
}

$writeLog([
    'hypothesisId' => 'H1,H2,H3',
    'location' => 'host-env-check.php',
    'message' => 'Composer path probe',
    'data' => [
        'composer_in_path' => $composerCandidates['composer (PATH)'] !== '',
        'found' => $foundComposer,
        'candidates' => $composerCandidates,
    ],
]);

$vendorOk = is_file($root . '/vendor/autoload.php');
echo "\n[Vendor] " . ($vendorOk ? 'OK vendor/autoload.php exists' : 'MISSING vendor/autoload.php') . "\n";

$writeLog([
    'hypothesisId' => 'H4',
    'location' => 'host-env-check.php',
    'message' => 'Vendor directory status',
    'data' => ['vendor_autoload_exists' => $vendorOk],
]);

$themeNew = is_file($root . '/resources/views/partials/theme/staff-sidebar.blade.php');
$cashierRoute = is_file($root . '/app/Http/Controllers/Pos/CashierController.php');
echo '[Code version] ' . ($themeNew && $cashierRoute ? 'NEW (POS kasir + theme)' : 'OLD (pre-882f52b)') . "\n";

$gitHead = trim((string) @shell_exec('cd ' . escapeshellarg($root) . ' && git log -1 --oneline 2>/dev/null'));
if ($gitHead !== '') {
    echo "[Git] {$gitHead}\n";
}

$writeLog([
    'hypothesisId' => 'H4',
    'location' => 'host-env-check.php',
    'message' => 'Deploy version markers',
    'data' => [
        'theme_partial' => $themeNew,
        'cashier_controller' => $cashierRoute,
        'git_head' => $gitHead ?: null,
    ],
]);

echo "\n--- Recommended next steps ---\n";

if ($foundComposer === null) {
    echo "1. Install Composer locally in home:\n";
    echo "     cd ~ && curl -sS https://getcomposer.org/installer | php\n";
    echo "     php ~/composer.phar install --no-dev --optimize-autoloader -d {$root}\n";
    echo "   OR use cPanel → Software → Setup PHP Composer (select folder bawean)\n";
} else {
    $cmd = str_contains($foundComposer, 'composer.phar') || str_ends_with($foundComposer, 'composer')
        ? "php {$foundComposer} install --no-dev --optimize-autoloader -d {$root}"
        : "{$foundComposer} install --no-dev --optimize-autoloader -d {$root}";
    echo "1. Run: {$cmd}\n";
}

if ($vendorOk) {
    echo "2. vendor/ already exists — after git pull you can SKIP composer if composer.lock unchanged.\n";
}

echo "3. Then:\n";
echo "     cd {$root}\n";
echo "     php artisan migrate --force\n";
echo "     php artisan config:cache && php artisan route:cache && php artisan view:cache\n";
echo "     php scripts/verify-deploy.php\n";

echo "\nLog written to: debug-b66d57.log\n";
