<?php
declare(strict_types=1);

use App\ContentData;
use App\Helpers;

$packages = ContentData::donatePackages();
require __DIR__ . '/../partials/header.php';
?>
<section class="page-hero">
  <div class="wrap">
    <div class="eyebrow hero-eyebrow">Support The Server</div>
    <h1>Donate <span class="hl">Packages</span></h1>
    <p>Contributions help cover dedicated hosting, DDoS protection, and ongoing content development.</p>
  </div>
</section>

<div class="page-main">
  <div class="grid-3" style="margin-bottom:24px;">
    <?php foreach ($packages as $pkg): ?>
      <div class="panel" style="<?= $pkg['popular'] ? 'border-color:var(--accent);box-shadow:0 0 0 2px rgba(255,196,0,0.22),var(--sh-2);' : '' ?>">
        <?php if ($pkg['popular']): ?>
          <span class="fcard-badge">Most Popular</span>
        <?php else: ?>
          <span class="fcard-badge">Support Tier</span>
        <?php endif; ?>
        <h2 style="font-family:var(--font-display);margin:4px 0 6px;font-size:22px;"><?= Helpers::e($pkg['name']) ?></h2>
        <div style="font-size:26px;font-weight:900;color:var(--accent-deep);margin-bottom:4px;"><?= Helpers::e($pkg['price']) ?></div>
        <div style="font-size:13px;font-weight:700;color:var(--ink-soft);margin-bottom:16px;"><?= Helpers::e($pkg['credits']) ?></div>
        <ul style="padding-left:18px;margin:0 0 20px;color:var(--ink-soft);font-size:14px;">
          <?php foreach ($pkg['perks'] as $perk): ?>
            <li style="margin-bottom:6px;"><?= Helpers::e($perk) ?></li>
          <?php endforeach; ?>
        </ul>
        <a class="btn <?= $pkg['popular'] ? 'btn-gold' : 'btn-ghost' ?>" style="width:100%;" href="<?= Helpers::e(DISCORD_URL) ?>" target="_blank" rel="noopener">Contact via Discord</a>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="panel">
    <p style="margin:0;color:var(--ink-soft);font-size:14px;">
      <strong>Note for Server Owner:</strong> Customize package tiers in <code>web/app/ContentData.php</code> and connect your preferred payment webhook (PromptPay / Stripe / PayPal) when ready for live billing.
    </p>
  </div>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
