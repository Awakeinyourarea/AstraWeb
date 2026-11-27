<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('CLI only');
}

require_once __DIR__ . '/../app/bootstrap.php';

$t0 = microtime(true);
$items = \App\ItemMobDb::buildItemCache();
$mobs = \App\ItemMobDb::buildMobCache();
$elapsed = microtime(true) - $t0;

echo sprintf("Cache rebuilt: %d items, %d mobs in %.2fs\n", $items, $mobs, $elapsed);
