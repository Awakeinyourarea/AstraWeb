<?php
declare(strict_types=1);

// ============================================================================
// AstraRO Web Portal Configuration
// ============================================================================

define('SITE_NAME', 'HoneyRO');
define('SITE_TAGLINE', 'Classic Mid-Rate Ragnarok Online — Sweet Progression & Long-Term Adventure');
define('SITE_EPISODE', 'Episode 13.3 · El Dicastes');
define('SITE_RATES_SHORT', '10x / 10x / 5x');
define('SITE_MAX_LEVEL', '250 / 50');

// Server Rates Detail (synced with conf/battle/exp.conf & drops.conf)
define('RATES_DETAIL', [
    'base_exp' => '10x',
    'job_exp'  => '10x',
    'common'   => '1x – 3x',
    'equip'    => '5x',
    'card'     => '10x',
    'mvp_card' => '1x',
]);

// Links (update these to your actual Discord / Download mirrors)
define('DISCORD_URL', 'https://discord.gg/astraro');
define('DOWNLOAD_FULL_URL', '#download-full');
define('DOWNLOAD_PATCH_URL', '#download-patch');

// MySQL Connection (matches conf/inter_athena.conf)
define('DB_HOST', '127.0.0.1');
define('DB_PORT', 3306);
define('DB_NAME', 'astra_main');
define('DB_USER', 'root');
define('DB_PASS', '123456');

// rAthena Server Config & Ports
define('USE_MD5_PASSWORDS', false); // conf/login_athena.conf -> use_MD5_passwords: no
define('SERVER_HOST', '127.0.0.1');
define('PORT_LOGIN', 6900);
define('PORT_CHAR', 6121);
define('PORT_MAP', 5121);

// Paths
define('WEB_ROOT', dirname(__DIR__));
define('STORAGE_PATH', WEB_ROOT . '/storage');
define('RATHENA_ROOT', dirname(WEB_ROOT));
define('RATHENA_DB_PATH', RATHENA_ROOT . '/db/re');

// Auto-detect Base URL (works under /astraro or root /)
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
$baseUrl = ($scriptDir === '/' || $scriptDir === '.') ? '' : rtrim($scriptDir, '/');
define('BASE_URL', $baseUrl);
