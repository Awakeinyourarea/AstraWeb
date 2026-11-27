<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Helpers.php';
require_once __DIR__ . '/Auth.php';
require_once __DIR__ . '/ItemMobDb.php';
require_once __DIR__ . '/ServerData.php';
require_once __DIR__ . '/ContentData.php';

\App\Auth::startSession();
