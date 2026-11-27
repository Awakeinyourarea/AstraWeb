<?php
declare(strict_types=1);

use App\Helpers;

require __DIR__ . '/../partials/header.php';
?>
<section class="page-hero">
  <div class="wrap">
    <div class="eyebrow hero-eyebrow">Get Started</div>
    <h1>Download <span class="hl"><?= Helpers::e(SITE_NAME) ?></span></h1>
    <p>Everything you need to install the Renewal client and connect to <?= Helpers::e(SITE_NAME) ?>.</p>
  </div>
</section>

<div class="page-main">
  <div class="grid-2" style="margin-bottom:24px;">
    <div class="panel">
      <span class="fcard-badge">Recommended</span>
      <h2 style="font-family:var(--font-display);margin:4px 0 8px;">Full Game Client</h2>
      <p style="color:var(--ink-soft);margin-bottom:18px;">Complete pre-patched <?= Helpers::e(SITE_EPISODE) ?> package. Extract anywhere and launch the patcher or game client directly.</p>
      <a class="btn btn-gold" href="<?= Helpers::e(DOWNLOAD_FULL_URL) ?>">Download Full Client</a>
    </div>
    <div class="panel">
      <span class="fcard-badge">Existing Client</span>
      <h2 style="font-family:var(--font-display);margin:4px 0 8px;">Patcher &amp; Data Files</h2>
      <p style="color:var(--ink-soft);margin-bottom:18px;">Already have an up-to-date kRO Renewal folder? Extract our patcher &amp; GRF files into your existing directory.</p>
      <a class="btn btn-ghost" href="<?= Helpers::e(DOWNLOAD_PATCH_URL) ?>">Download Lite Patch</a>
    </div>
  </div>

  <div class="panel">
    <div class="eyebrow">Setup Steps</div>
    <h3 style="font-family:var(--font-display);margin:6px 0 16px;font-size:24px;">Quick Installation Guide</h3>
    <div class="grid-3">
      <div class="fcard">
        <span class="fcard-badge">Step 1</span>
        <h3>Register Account</h3>
        <p>Create your game account on the <a href="<?= Helpers::e(Helpers::baseUrl('register')) ?>" style="color:var(--accent-deep);font-weight:700;">Register page</a> before launching.</p>
      </div>
      <div class="fcard">
        <span class="fcard-badge">Step 2</span>
        <h3>Extract &amp; Patch</h3>
        <p>Extract the archive to a clean folder (avoid <code>Program Files</code>) and run the patcher as needed.</p>
      </div>
      <div class="fcard">
        <span class="fcard-badge">Step 3</span>
        <h3>Configure &amp; Play</h3>
        <p>Run <code>opensetup.exe</code> to set resolution &amp; audio, then launch the client and enter Midgard.</p>
      </div>
    </div>
  </div>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
