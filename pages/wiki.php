<?php
declare(strict_types=1);

use App\ContentData;
use App\Helpers;

$sections = ContentData::wikiSections();
require __DIR__ . '/../partials/header.php';
?>
<section class="page-hero">
  <div class="wrap">
    <div class="eyebrow hero-eyebrow">Information</div>
    <h1>Server <span class="hl">Wiki &amp; Guide</span></h1>
    <p>Rates, useful player commands, and community rules for <?= Helpers::e(SITE_NAME) ?>.</p>
  </div>
</section>

<div class="page-main">
  <div style="display:flex;flex-direction:column;gap:24px;">
    <?php foreach ($sections as $sec): ?>
      <section class="panel" id="<?= Helpers::e($sec['id']) ?>">
        <div class="eyebrow">Guide</div>
        <h2 style="font-family:var(--font-display);margin:4px 0 6px;font-size:26px;"><?= Helpers::e($sec['title']) ?></h2>
        <p style="margin:0 0 18px;color:var(--ink-soft);"><?= Helpers::e($sec['summary']) ?></p>
        <div class="table-wrap">
          <table class="rk-table">
            <tbody>
              <?php foreach ($sec['rows'] as [$k, $v]): ?>
                <tr>
                  <td style="width:32%;font-weight:800;color:var(--cocoa);"><?= Helpers::e($k) ?></td>
                  <td><?= Helpers::e($v) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>
    <?php endforeach; ?>
  </div>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
