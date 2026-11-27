<?php
declare(strict_types=1);

use App\Auth;
use App\Helpers;

$currentUser = $currentUser ?? Auth::currentUser();
$pageTitle = $pageTitle ?? 'Home';
$flash = Helpers::getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#ffc62b">
  <title><?= Helpers::e($pageTitle) ?> - <?= Helpers::e(SITE_NAME) ?></title>
  <link rel="stylesheet" href="<?= Helpers::e(Helpers::baseUrl('assets/css/style.css')) ?>">
  <script>
    (() => {
      const saved = localStorage.getItem('honey_theme');
      const dark = saved === 'dark' || (!saved && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
      if (dark) document.documentElement.classList.add('dark');
    })();
  </script>
</head>
<body>
<nav id="mainNav" class="nav">
  <a class="brand" href="<?= Helpers::e(Helpers::baseUrl('/')) ?>">
    <img src="<?= Helpers::e(Helpers::baseUrl('assets/img/logo.webp')) ?>" alt="<?= Helpers::e(SITE_NAME) ?>" class="brand-logo">
  </a>

  <div class="nav-links">
    <a href="<?= Helpers::e(Helpers::baseUrl('wiki')) ?>">Wiki</a>
    <a href="<?= Helpers::e(Helpers::baseUrl('downloads')) ?>">Downloads</a>
    <a href="<?= Helpers::e(Helpers::baseUrl('donate')) ?>">Donations</a>

    <div class="has-menu">
      <button class="menu-trigger" type="button" aria-haspopup="true" aria-expanded="false">Database <span class="caret">▾</span></button>
      <div class="submenu" role="menu">
        <a href="<?= Helpers::e(Helpers::baseUrl('database?tab=items')) ?>" role="menuitem">
          <svg class="submenu-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 7v14"/><path d="M16 12h2"/><path d="M16 8h2"/><path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/><path d="M6 12h2"/><path d="M6 8h2"/></svg>
          Items
        </a>
        <a href="<?= Helpers::e(Helpers::baseUrl('database?tab=mobs')) ?>" role="menuitem">
          <svg class="submenu-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12.5 17-.5-1-.5 1h1z"/><path d="M15 22a1 1 0 0 0 1-1v-1a2 2 0 0 0 1.56-3.25 8 8 0 1 0-11.12 0A2 2 0 0 0 8 20v1a1 1 0 0 0 1 1z"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="12" r="1"/></svg>
          Monsters
        </a>
        <a href="<?= Helpers::e(Helpers::baseUrl('database?tab=maps')) ?>" role="menuitem">
          <svg class="submenu-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14.106 5.553a2 2 0 0 0 1.788 0l3.659-1.83A1 1 0 0 1 21 4.619v12.764a1 1 0 0 1-.553.894l-4.553 2.277a2 2 0 0 1-1.788 0l-4.212-2.106a2 2 0 0 0-1.788 0l-3.659 1.83A1 1 0 0 1 3 19.381V6.618a1 1 0 0 1 .553-.894l4.553-2.277a2 2 0 0 1 1.788 0z"/><path d="M15 5.764v15"/><path d="M9 3.236v15"/></svg>
          Maps
        </a>
        <a href="<?= Helpers::e(Helpers::baseUrl('database?tab=mvp-cards')) ?>" role="menuitem">
          <svg class="submenu-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 13h.01"/><path d="M12 6v3"/><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20"/></svg>
          MVP Cards
        </a>
        <a href="<?= Helpers::e(Helpers::baseUrl('whosell')) ?>" role="menuitem">
          <svg class="submenu-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
          Merchants
        </a>
        <a href="<?= Helpers::e(Helpers::baseUrl('database?tab=calculator')) ?>" role="menuitem">
          <svg class="submenu-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="16" height="20" x="4" y="2" rx="2"/><line x1="8" x2="16" y1="6" y2="6"/><line x1="16" x2="16" y1="14" y2="18"/><path d="M16 10h.01"/><path d="M12 10h.01"/><path d="M8 10h.01"/><path d="M12 14h.01"/><path d="M8 14h.01"/><path d="M12 18h.01"/><path d="M8 18h.01"/></svg>
          Build Calc
        </a>
        <a href="<?= Helpers::e(Helpers::baseUrl('database?tab=name-checker')) ?>" role="menuitem">
          <svg class="submenu-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 16 6-12 6 12"/><path d="M8 12h8"/><path d="m16 20 2 2 4-4"/></svg>
          Name Checker
        </a>
      </div>
    </div>

    <div class="has-menu">
      <button class="menu-trigger" type="button" aria-haspopup="true" aria-expanded="false">Rankings <span class="caret">▾</span></button>
      <div class="submenu" role="menu">
        <a href="<?= Helpers::e(Helpers::baseUrl('rankings?tab=mvp')) ?>" role="menuitem">
          <svg class="submenu-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11.562 3.266a.5.5 0 0 1 .876 0L15.39 8.87a1 1 0 0 0 1.516.294L21.183 5.5a.5.5 0 0 1 .798.519l-2.834 10.246a1 1 0 0 1-.956.734H5.81a1 1 0 0 1-.957-.734L2.02 6.02a.5.5 0 0 1 .798-.519l4.276 3.664a1 1 0 0 0 1.516-.294z"/><path d="M5 21h14"/></svg>
          MVP Ranking
        </a>
        <a href="<?= Helpers::e(Helpers::baseUrl('rankings?tab=mvp-log')) ?>" role="menuitem">
          <svg class="submenu-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 12h-5"/><path d="M15 8h-5"/><path d="M19 17V5a2 2 0 0 0-2-2H4"/><path d="M8 21h12a2 2 0 0 0 2-2v-1a1 1 0 0 0-1-1H11a1 1 0 0 0-1 1v1a2 2 0 1 1-4 0V5a2 2 0 1 0-4 0v2a1 1 0 0 0 1 1h3"/></svg>
          MVP Log
        </a>
        <a href="<?= Helpers::e(Helpers::baseUrl('rankings?tab=guild')) ?>" role="menuitem">
          <svg class="submenu-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 20v-9H2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2Z"/><path d="M18 11V4H6v7"/><path d="M15 22v-4a3 3 0 0 0-3-3v0a3 3 0 0 0-3 3v4"/><path d="M22 11V9"/><path d="M2 11V9"/><path d="M6 4V2"/><path d="M18 4V2"/><path d="M10 4V2"/><path d="M14 4V2"/></svg>
          Guild Rankings
        </a>
        <a href="<?= Helpers::e(Helpers::baseUrl('rankings?tab=battleground')) ?>" role="menuitem">
          <svg class="submenu-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="14.5 17.5 3 6 3 3 6 3 17.5 14.5"/><line x1="13" x2="19" y1="19" y2="13"/><line x1="16" x2="20" y1="16" y2="20"/><line x1="19" x2="21" y1="21" y2="19"/><polyline points="14.5 6.5 18 3 21 3 21 6 17.5 9.5"/><line x1="5" x2="9" y1="14" y2="18"/><line x1="7" x2="4" y1="17" y2="20"/><line x1="3" x2="5" y1="19" y2="21"/></svg>
          Battleground
        </a>
        <a href="<?= Helpers::e(Helpers::baseUrl('rankings?tab=hunting')) ?>" role="menuitem">
          <svg class="submenu-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
          Hunting
        </a>
        <a href="<?= Helpers::e(Helpers::baseUrl('rankings?tab=level')) ?>" role="menuitem">
          <svg class="submenu-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7.21 15 2.66 7.14a2 2 0 0 1 .13-2.2L4.4 2.8A2 2 0 0 1 6 2h12a2 2 0 0 1 1.6.8l1.6 2.14a2 2 0 0 1 .14 2.2L16.79 15"/><path d="M11 12 5.12 2.2"/><path d="m13 12 5.88-9.8"/><path d="M8 7h8"/><circle cx="12" cy="17" r="5"/><path d="M12 18v-2h-.5"/></svg>
          Level
        </a>
        <a href="<?= Helpers::e(Helpers::baseUrl('rankings?tab=playtime')) ?>" role="menuitem">
          <svg class="submenu-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 22h14"/><path d="M5 2h14"/><path d="M17 22v-4.172a2 2 0 0 0-.586-1.414L12 12l-4.414 4.414A2 2 0 0 0 7 17.828V22"/><path d="M7 2v4.172a2 2 0 0 0 .586 1.414L12 12l4.414-4.414A2 2 0 0 0 17 6.172V2"/></svg>
          Playtime
        </a>
        <a href="<?= Helpers::e(Helpers::baseUrl('rankings?tab=pvp')) ?>" role="menuitem">
          <svg class="submenu-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="14.5 17.5 3 6 3 3 6 3 17.5 14.5"/><line x1="13" x2="19" y1="19" y2="13"/><line x1="16" x2="20" y1="16" y2="20"/><line x1="19" x2="21" y1="21" y2="19"/></svg>
          Player vs Player
        </a>
        <a href="<?= Helpers::e(Helpers::baseUrl('rankings?tab=zeny')) ?>" role="menuitem">
          <svg class="submenu-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg>
          Currency
        </a>
      </div>
    </div>
  </div>

  <div class="nav-right">
    <div class="nav-util">
      <button type="button" class="lang-trigger" title="English">
        <span>🌐</span><span>EN</span>
      </button>
      <span class="pipe"></span>
      <button type="button" class="theme-toggle" id="themeToggle" aria-label="Toggle theme" title="Toggle Light / Dark Mode">
        <span class="moon">☾</span>
        <span class="sun">☀</span>
      </button>
    </div>

    <div class="auth-actions">
      <?php if ($currentUser): ?>
        <div class="has-menu">
          <button type="button" class="btn-honey account-btn menu-trigger">
            <span class="account-avatar"><?= Helpers::e(substr($currentUser['userid'], 0, 1)) ?></span>
            <span><?= Helpers::e($currentUser['userid']) ?></span>
            <span class="caret">▾</span>
          </button>
          <div class="submenu submenu-right">
            <a href="<?= Helpers::e(Helpers::baseUrl('account')) ?>">Account Panel</a>
            <a href="<?= Helpers::e(Helpers::baseUrl('logout')) ?>">Sign Out</a>
          </div>
        </div>
      <?php else: ?>
        <a href="<?= Helpers::e(Helpers::baseUrl('register')) ?>" class="btn-honey btn-honey-sm">Register</a>
        <a href="<?= Helpers::e(Helpers::baseUrl('login')) ?>" class="btn btn-ghost btn-sm btn-ghost-nav">Login →</a>
      <?php endif; ?>
    </div>

    <button type="button" class="mobile-hamburger" id="mobileMenuBtn" aria-label="Toggle navigation">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
    </button>
  </div>

  <div class="mobile-menu" id="mobileMenu">
    <a class="mobile-link" href="<?= Helpers::e(Helpers::baseUrl('wiki')) ?>">Wiki</a>
    <a class="mobile-link" href="<?= Helpers::e(Helpers::baseUrl('downloads')) ?>">Downloads</a>
    <a class="mobile-link" href="<?= Helpers::e(Helpers::baseUrl('donate')) ?>">Donations</a>
    <a class="mobile-link" href="<?= Helpers::e(Helpers::baseUrl('database?tab=items')) ?>">Items Database</a>
    <a class="mobile-link" href="<?= Helpers::e(Helpers::baseUrl('database?tab=mobs')) ?>">Monsters Database</a>
    <a class="mobile-link" href="<?= Helpers::e(Helpers::baseUrl('whosell')) ?>">WhoSell Market</a>
    <a class="mobile-link" href="<?= Helpers::e(Helpers::baseUrl('rankings?tab=level')) ?>">Rankings</a>
    <?php if ($currentUser): ?>
      <a class="mobile-link" href="<?= Helpers::e(Helpers::baseUrl('account')) ?>">Account (<?= Helpers::e($currentUser['userid']) ?>)</a>
      <a class="mobile-link" href="<?= Helpers::e(Helpers::baseUrl('logout')) ?>">Sign Out</a>
    <?php else: ?>
      <a class="mobile-link" href="<?= Helpers::e(Helpers::baseUrl('register')) ?>">Register</a>
      <a class="mobile-link" href="<?= Helpers::e(Helpers::baseUrl('login')) ?>">Login</a>
    <?php endif; ?>
  </div>
</nav>
<main>
<?php if ($flash): ?>
  <div class="wrap" style="position:relative;z-index:40;margin-top:84px;margin-bottom:-64px;">
    <div class="alert <?= $flash['type'] === 'success' ? 'alert-success' : 'alert-error' ?>">
      <?= Helpers::e($flash['message']) ?>
    </div>
  </div>
<?php endif; ?>
