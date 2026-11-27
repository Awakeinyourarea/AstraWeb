<?php
declare(strict_types=1);

use App\Helpers;
use App\ServerData;

$search = trim((string)($_GET['q'] ?? ''));
$page = max(1, (int)($_GET['p'] ?? 1));
$result = ServerData::whosell($search, $page, 30);
$rows = $result['rows'];

require __DIR__ . '/../partials/header.php';
?>
<section class="page-hero">
  <div class="wrap">
    <div class="eyebrow hero-eyebrow">Live Economy</div>
    <h1>WhoSell <span class="hl">Market</span></h1>
    <p>Search active player vending shops across Midgard and copy <code>/navi</code> coordinates directly.</p>
  </div>
</section>

<div class="page-main">
  <div class="panel">
    <form class="filter-bar" method="get" action="<?= Helpers::e(Helpers::baseUrl('whosell')) ?>">
      <input class="input input-grow" type="text" name="q" value="<?= Helpers::e($search) ?>" placeholder="Search item name, item ID, vendor, shop title, or map...">
      <button class="btn btn-gold" type="submit">Find Items</button>
      <?php if ($search !== ''): ?>
        <a class="btn btn-ghost" href="<?= Helpers::e(Helpers::baseUrl('whosell')) ?>">Reset</a>
      <?php endif; ?>
    </form>

    <div style="margin-bottom:14px;color:var(--ink-soft);font-size:13.5px;font-weight:700;">
      Showing <strong><?= number_format($result['total']) ?></strong> listed vending item(s)
    </div>

    <div class="table-wrap">
      <table class="rk-table">
        <thead>
          <tr><th>Item</th><th>Qty</th><th>Price</th><th>Shop &amp; Vendor</th><th>Location</th></tr>
        </thead>
        <tbody>
          <?php if (empty($rows)): ?>
            <tr><td colspan="5" style="text-align:center;color:var(--ink-faint);padding:36px;">No active vending items match your search right now.</td></tr>
          <?php else: foreach ($rows as $r):
            $refine = (int)$r['refine'] > 0 ? '+' . (int)$r['refine'] . ' ' : '';
            $slots = (int)$r['item_slots'] > 0 ? ' [' . (int)$r['item_slots'] . ']' : '';
            $navi = '/navi ' . $r['map'] . ' ' . (int)$r['x'] . '/' . (int)$r['y'];
          ?>
            <tr>
              <td>
                <strong><?= Helpers::e($refine . $r['item_name'] . $slots) ?></strong>
                <span style="font-size:12px;color:var(--ink-faint);margin-left:6px;">#<?= (int)$r['nameid'] ?></span>
                <?php if (!empty($r['cards'])): ?>
                  <div style="font-size:12px;color:var(--accent-deep);margin-top:2px;">Cards: <?= Helpers::e(implode(', ', $r['cards'])) ?></div>
                <?php endif; ?>
              </td>
              <td><?= number_format((int)$r['amount']) ?></td>
              <td><strong style="color:var(--accent-deep);"><?= Helpers::e(Helpers::formatZeny($r['price'])) ?></strong></td>
              <td>
                <div><strong><?= Helpers::e($r['title']) ?></strong></div>
                <div style="font-size:12px;color:var(--ink-soft);">by <?= Helpers::e($r['vendor_name']) ?><?= (int)$r['autotrade'] ? ' (Autotrade)' : '' ?></div>
              </td>
              <td>
                <button type="button" class="btn btn-sm btn-ghost" onclick="copyText('<?= Helpers::e($navi) ?>')">
                  <?= Helpers::e($r['map']) ?> (<?= (int)$r['x'] ?>, <?= (int)$r['y'] ?>)
                </button>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>

    <?php if ($result['pages'] > 1): ?>
      <div class="pager">
        <?php for ($p = 1; $p <= min(10, $result['pages']); $p++): ?>
          <a class="pg <?= $p === $page ? 'active' : '' ?>" href="<?= Helpers::e(Helpers::baseUrl('whosell?q=' . urlencode($search) . '&p=' . $p)) ?>"><?= $p ?></a>
        <?php endfor; ?>
      </div>
    <?php endif; ?>
  </div>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
