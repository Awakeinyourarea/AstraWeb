<?php
declare(strict_types=1);

require_once __DIR__ . '/app/bootstrap.php';

use App\Auth;
use App\Helpers;
use App\ItemMobDb;
use App\ServerData;

$route = trim((string)($_GET['route'] ?? $_GET['page'] ?? ''), '/');
if ($route === '') {
    $route = 'home';
}

// Lightweight JSON API endpoints
if (str_starts_with($route, 'api/')) {
    switch ($route) {
        case 'api/summary':
            Helpers::json(['ok' => true, 'data' => ServerData::summary()]);
        case 'api/item':
            $id = (int)($_GET['id'] ?? 0);
            $item = ItemMobDb::findItem($id);
            Helpers::json($item ? ['ok' => true, 'data' => $item] : ['ok' => false, 'error' => 'Not found'], $item ? 200 : 404);
        case 'api/mob':
            $id = (int)($_GET['id'] ?? 0);
            $mob = ItemMobDb::findMob($id);
            Helpers::json($mob ? ['ok' => true, 'data' => $mob] : ['ok' => false, 'error' => 'Not found'], $mob ? 200 : 404);
        default:
            Helpers::json(['ok' => false, 'error' => 'Unknown API route'], 404);
    }
}

if ($route === 'logout') {
    Auth::logout();
    Auth::startSession();
    Helpers::setFlash('success', 'You have been signed out.');
    header('Location: ' . Helpers::baseUrl('/'));
    exit;
}

$routes = [
    'home'      => ['file' => 'pages/home.php',      'title' => 'Home'],
    'rankings'  => ['file' => 'pages/rankings.php',  'title' => 'Rankings'],
    'whosell'   => ['file' => 'pages/whosell.php',   'title' => 'WhoSell Market'],
    'database'  => ['file' => 'pages/database.php',  'title' => 'Item & Monster Database'],
    'downloads' => ['file' => 'pages/downloads.php', 'title' => 'Downloads'],
    'wiki'      => ['file' => 'pages/wiki.php',      'title' => 'Server Wiki'],
    'donate'    => ['file' => 'pages/donate.php',    'title' => 'Donate & Support'],
    'login'     => ['file' => 'pages/login.php',     'title' => 'Login'],
    'register'  => ['file' => 'pages/register.php',  'title' => 'Register'],
    'account'   => ['file' => 'pages/account.php',   'title' => 'Account Control Panel'],
];

if (!isset($routes[$route])) {
    http_response_code(404);
    $pageTitle = '404 Not Found';
    $currentUser = Auth::currentUser();
    require __DIR__ . '/partials/header.php';
    echo '<section class="page-hero"><div class="wrap"><div class="eyebrow hero-eyebrow">Error 404</div><h1>Page <span class="hl">Not Found</span></h1><p>The page you requested does not exist.</p></div></section>';
    echo '<div class="page-main"><div class="panel center"><a class="btn btn-gold" href="' . Helpers::e(Helpers::baseUrl('/')) . '">Return Home</a></div></div>';
    require __DIR__ . '/partials/footer.php';
    exit;
}

$pageTitle = $routes[$route]['title'];
$currentUser = Auth::currentUser();
require __DIR__ . '/' . $routes[$route]['file'];
