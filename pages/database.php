<?php
declare(strict_types=1);

use App\Helpers;
use App\ItemMobDb;

$validTabs = ['items', 'mobs', 'maps', 'mvp-cards', 'calculator', 'name-checker'];
$tab = in_array($_GET['tab'] ?? 'items', $validTabs, true) ? (string)$_GET['tab'] : 'items';
$q = trim((string)($_GET['q'] ?? ''));
$filter = trim((string)($_GET['filter'] ?? ''));
$mvpOnly = ($_GET['mvp'] ?? '') === '1' ? '1' : '';
$page = max(1, (int)($_GET['p'] ?? 1));

if ($tab === 'mobs') {
    $result = ItemMobDb::searchMobs($q, $filter, $mvpOnly, $page, 30);
} elseif ($tab === 'mvp-cards') {
    $result = ItemMobDb::searchItems($q, 'Card', $page, 30);
} elseif ($tab === 'items') {
    $result = ItemMobDb::searchItems($q, $filter, $page, 30);
} else {
    $result = ['rows' => [], 'total' => 0, 'pages' => 1];
}
$rows = $result['rows'];

require __DIR__ . '/../partials/header.php';
?>
<section class="page-hero">
  <div class="wrap">
    <div class="eyebrow hero-eyebrow">Renewal Encyclopedia</div>
    <h1>Item &amp; Monster <span class="hl">Database</span></h1>
    <p>Indexed live from <?= Helpers::e(SITE_NAME) ?> YAML tables (29,300+ items and 2,600+ monsters).</p>
  </div>
</section>

<div class="page-main">
  <div class="panel">
    <div class="tabs">
      <a class="tab <?= $tab === 'items' ? 'active' : '' ?>" href="<?= Helpers::e(Helpers::baseUrl('database?tab=items')) ?>">Items</a>
      <a class="tab <?= $tab === 'mobs' ? 'active' : '' ?>" href="<?= Helpers::e(Helpers::baseUrl('database?tab=mobs')) ?>">Monsters</a>
      <a class="tab <?= $tab === 'mvp-cards' ? 'active' : '' ?>" href="<?= Helpers::e(Helpers::baseUrl('database?tab=mvp-cards')) ?>">Cards</a>
      <a class="tab <?= $tab === 'maps' ? 'active' : '' ?>" href="<?= Helpers::e(Helpers::baseUrl('database?tab=maps')) ?>">Maps</a>
      <a class="tab <?= $tab === 'calculator' ? 'active' : '' ?>" href="<?= Helpers::e(Helpers::baseUrl('database?tab=calculator')) ?>">Build Calc</a>
      <a class="tab <?= $tab === 'name-checker' ? 'active' : '' ?>" href="<?= Helpers::e(Helpers::baseUrl('database?tab=name-checker')) ?>">Name Checker</a>
    </div>

    <?php if (in_array($tab, ['maps', 'calculator', 'name-checker'], true)): ?>
      <div style="padding:36px 20px;text-align:center;color:var(--ink-soft);">
        <h3 style="margin-bottom:8px;color:var(--ink);">
          <?= $tab === 'maps' ? 'World Maps & Spawn Guide' : ($tab === 'calculator' ? 'Pre-Renewal Build Calculator' : 'Character Name Checker') ?>
        </h3>
        <p style="max-width:520px;margin:0 auto 18px;">
          <?= $tab === 'maps'
              ? 'Use the Monsters tab to search monsters and inspect their spawn maps, drops, and stats.'
              : ($tab === 'calculator'
                  ? 'Plan your Pre-Renewal stats and skills before entering Rune Midgard.'
                  : 'Check character name availability directly when creating your character in-game.') ?>
        </p>
        <a class="btn btn-gold" href="<?= Helpers::e(Helpers::baseUrl('database?tab=' . ($tab === 'maps' ? 'mobs' : 'items'))) ?>">
          <?= $tab === 'maps' ? 'Browse Monsters' : 'Browse Items' ?>
        </a>
      </div>
    <?php else: ?>
    <form class="filter-bar" method="get" action="<?= Helpers::e(Helpers::baseUrl('database')) ?>">
      <input type="hidden" name="tab" value="<?= Helpers::e($tab) ?>">
      <input class="input input-grow" type="text" name="q" value="<?= Helpers::e($q) ?>" placeholder="Search by ID, Name, or AegisName...">
      <?php if ($tab === 'items'): ?>
        <select class="select" name="filter">
          <option value="">All Types</option>
          <?php foreach (['Weapon', 'Armor', 'Card', 'Healing', 'Usable', 'Ammo', 'ShadowGear', 'Etc'] as $opt): ?>
            <option value="<?= $opt ?>" <?= $filter === $opt ? 'selected' : '' ?>><?= $opt ?></option>
          <?php endforeach; ?>
        </select>
      <?php else: ?>
        <select class="select" name="filter">
          <option value="">All Races</option>
          <?php foreach (['Angel', 'Brute', 'DemiHuman', 'Demon', 'Dragon', 'Fish', 'Formless', 'Insect', 'Plant', 'Undead'] as $opt): ?>
            <option value="<?= $opt ?>" <?= $filter === $opt ? 'selected' : '' ?>><?= $opt ?></option>
          <?php endforeach; ?>
        </select>
        <label style="display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:700;padding:0 6px;">
          <input type="checkbox" name="mvp" value="1" <?= $mvpOnly === '1' ? 'checked' : '' ?>> MVP Only
        </label>
      <?php endif; ?>
      <button class="btn btn-gold" type="submit">Search</button>
    </form>

    <div style="margin-bottom:14px;color:var(--ink-soft);font-size:13.5px;font-weight:700;">
      Found <strong><?= number_format($result['total']) ?></strong> <?= $tab === 'mobs' ? 'monster(s)' : 'item(s)' ?>
    </div>

    <div class="table-wrap">
      <table class="rk-table">
        <?php if ($tab === 'mobs'): ?>
          <thead><tr><th>ID</th><th>Monster</th><th>Lv / HP</th><th>Race / Element / Size</th><th>EXP (Base / Job)</th><th>Top Drops</th></tr></thead>
          <tbody>
            <?php if (empty($rows)): ?>
              <tr><td colspan="6" style="text-align:center;color:var(--ink-faint);padding:34px;">No monsters match your search.</td></tr>
            <?php else: foreach ($rows as $m): ?>
              <tr>
                <td><code>#<?= (int)$m['id'] ?></code></td>
                <td>
                  <strong><?= Helpers::e($m['name']) ?></strong>
                  <?php if (!empty($m['mvp'])): ?><span class="fcard-badge" style="margin:0 0 0 6px;">MVP</span><?php endif; ?>
                  <div style="font-size:12px;color:var(--ink-faint);"><?= Helpers::e($m['aegis']) ?></div>
                </td>
                <td><strong>Lv. <?= (int)$m['lv'] ?></strong><div style="font-size:12px;color:var(--ink-soft);"><?= number_format((int)$m['hp']) ?> HP</div></td>
                <td><?= Helpers::e($m['race']) ?> &bull; <?= Helpers::e($m['elem']) ?> <?= (int)$m['elemlv'] ?> &bull; <?= Helpers::e($m['size']) ?></td>
                <td><?= number_format((int)$m['bexp']) ?> / <?= number_format((int)$m['jexp']) ?></td>
                <td style="font-size:12px;color:var(--ink-soft);">
                  <?php
                    $drops = array_slice($m['drops'] ?? [], 0, 3);
                    if (empty($drops)) {
                        echo '—';
                    } else {
                        $labels = array_map(fn($d) => Helpers::e($d['item']) . ' (' . number_format($d['rate'] / 100, 2) . '%)', $drops);
                        echo implode('<br>', $labels);
                    }
                  ?>
                </td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        <?php else: ?>
          <thead><tr><th>ID</th><th>Item Name</th><th>Type</th><th>Stats</th><th>Weight</th><th>Buy / Sell</th></tr></thead>
          <tbody>
            <?php if (empty($rows)): ?>
              <tr><td colspan="6" style="text-align:center;color:var(--ink-faint);padding:34px;">No items match your search.</td></tr>
            <?php else: foreach ($rows as $it):
              $stats = [];
              if ($it['atk'] > 0) $stats[] = 'ATK ' . $it['atk'];
              if ($it['matk'] > 0) $stats[] = 'MATK ' . $it['matk'];
              if ($it['def'] > 0) $stats[] = 'DEF ' . $it['def'];
              if ($it['slots'] > 0) $stats[] = $it['slots'] . ' Slot(s)';
              if ($it['elv'] > 0) $stats[] = 'Req Lv ' . $it['elv'];
            ?>
              <tr>
                <td><code>#<?= (int)$it['id'] ?></code></td>
                <td>
                  <strong><?= Helpers::e($it['name']) ?><?= $it['slots'] > 0 ? ' [' . (int)$it['slots'] . ']' : '' ?></strong>
                  <div style="font-size:12px;color:var(--ink-faint);"><?= Helpers::e($it['aegis']) ?></div>
                </td>
                <td><span class="fcard-badge" style="margin:0;"><?= Helpers::e($it['type']) ?></span><?= $it['sub'] ? '<div style="font-size:11.5px;color:var(--ink-soft);margin-top:3px;">' . Helpers::e($it['sub']) . '</div>' : '' ?></td>
                <td style="font-size:12.5px;"><?= $stats ? Helpers::e(implode(' · ', $stats)) : '—' ?></td>
                <td><?= number_format($it['weight'] / 10, 1) ?></td>
                <td style="font-size:12.5px;"><?= number_format((int)$it['buy']) ?> / <?= number_format((int)$it['sell']) ?> z</td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        <?php endif; ?>
      </table>
    </div>

    <?php if ($result['pages'] > 1): ?>
      <div class="pager">
        <?php
          $start = max(1, $page - 3);
          $end = min($result['pages'], $start + 8);
          for ($p = $start; $p <= $end; $p++):
        ?>
          <a class="pg <?= $p === $page ? 'active' : '' ?>" href="<?= Helpers::e(Helpers::baseUrl("database?tab={$tab}&q=" . urlencode($q) . "&filter=" . urlencode($filter) . "&mvp={$mvpOnly}&p={$p}")) ?>"><?= $p ?></a>
        <?php endfor; ?>
      </div>
    <?php endif; ?>
  </div>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
