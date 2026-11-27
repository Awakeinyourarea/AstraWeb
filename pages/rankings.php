<?php
declare(strict_types=1);

use App\Helpers;
use App\ServerData;

$validTabs = ['level', 'guild', 'zeny', 'mvp', 'castles', 'mvp-log', 'battleground', 'hunting', 'playtime', 'pvp'];
$tab = in_array($_GET['tab'] ?? 'level', $validTabs, true) ? (string)$_GET['tab'] : 'level';
$search = trim((string)($_GET['q'] ?? ''));
$page = max(1, (int)($_GET['p'] ?? 1));

$queryTab = match ($tab) {
    'mvp-log' => 'mvp',
    'battleground', 'hunting', 'playtime', 'pvp' => 'level',
    default => $tab,
};
$result = ServerData::rankings($queryTab, $search, $page, 25);
$rows = $result['rows'];

require __DIR__ . '/../partials/header.php';
?>
<section class="page-hero">
  <div class="wrap">
    <div class="eyebrow hero-eyebrow">Hall of Fame</div>
    <h1>Server <span class="hl">Rankings</span></h1>
    <p>Live leaderboards queried directly from <?= Helpers::e(SITE_NAME) ?> character, guild, and MVP logs.</p>
  </div>
</section>

<div class="page-main">
  <div class="panel">
    <div class="tabs">
      <a class="tab <?= $tab === 'level' ? 'active' : '' ?>" href="<?= Helpers::e(Helpers::baseUrl('rankings?tab=level')) ?>">Level</a>
      <a class="tab <?= $tab === 'guild' ? 'active' : '' ?>" href="<?= Helpers::e(Helpers::baseUrl('rankings?tab=guild')) ?>">Guilds</a>
      <a class="tab <?= $tab === 'zeny' ? 'active' : '' ?>" href="<?= Helpers::e(Helpers::baseUrl('rankings?tab=zeny')) ?>">Zeny Wealth</a>
      <a class="tab <?= $tab === 'mvp' ? 'active' : '' ?>" href="<?= Helpers::e(Helpers::baseUrl('rankings?tab=mvp')) ?>">MVP Hunters</a>
      <a class="tab <?= $tab === 'castles' ? 'active' : '' ?>" href="<?= Helpers::e(Helpers::baseUrl('rankings?tab=castles')) ?>">WoE Castles</a>
    </div>

    <?php if ($tab !== 'castles'): ?>
      <form class="filter-bar" method="get" action="<?= Helpers::e(Helpers::baseUrl('rankings')) ?>">
        <input type="hidden" name="tab" value="<?= Helpers::e($tab) ?>">
        <input class="input input-grow" type="text" name="q" value="<?= Helpers::e($search) ?>" placeholder="Search <?= $tab === 'guild' ? 'guild or leader name...' : 'character name...' ?>">
        <button class="btn btn-gold" type="submit">Search</button>
        <?php if ($search !== ''): ?>
          <a class="btn btn-ghost" href="<?= Helpers::e(Helpers::baseUrl('rankings?tab=' . $tab)) ?>">Clear</a>
        <?php endif; ?>
      </form>
    <?php endif; ?>

    <div class="table-wrap">
      <table class="rk-table">
        <?php if ($tab === 'guild'): ?>
          <thead><tr><th>#</th><th>Guild</th><th>Master</th><th>Level</th><th>Members</th><th>Avg Lv</th><th>Castles</th></tr></thead>
          <tbody>
            <?php if (empty($rows)): ?>
              <tr><td colspan="7" style="text-align:center;color:var(--ink-faint);padding:34px;">No guilds found.</td></tr>
            <?php else: foreach ($rows as $i => $r): $rank = (($page - 1) * 25) + $i + 1; ?>
              <tr>
                <td><span class="rank-num rank-<?= $rank <= 3 ? $rank : 0 ?>"><?= $rank ?></span></td>
                <td><strong><?= Helpers::e($r['name']) ?></strong></td>
                <td><?= Helpers::e($r['master']) ?></td>
                <td><strong>Lv. <?= (int)$r['guild_lv'] ?></strong></td>
                <td><?= (int)$r['member_count'] ?> / <?= (int)$r['max_member'] ?></td>
                <td><?= (int)$r['average_lv'] ?></td>
                <td><?= (int)$r['castle_count'] ?></td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        <?php elseif ($tab === 'castles'): ?>
          <thead><tr><th>Castle</th><th>Controlling Guild</th><th>Guild Master</th><th>Economy</th><th>Defense</th></tr></thead>
          <tbody>
            <?php if (empty($rows)): ?>
              <tr><td colspan="5" style="text-align:center;color:var(--ink-faint);padding:34px;">No castle ownership records yet.</td></tr>
            <?php else: foreach ($rows as $r): ?>
              <tr>
                <td><strong><?= Helpers::e(Helpers::castleName((int)$r['castle_id'])) ?></strong></td>
                <td><?= $r['guild_name'] ? '<strong>' . Helpers::e($r['guild_name']) . '</strong>' : '<span style="color:var(--ink-faint);">Unclaimed</span>' ?></td>
                <td><?= $r['master'] ? Helpers::e($r['master']) : '—' ?></td>
                <td><?= (int)$r['economy'] ?></td>
                <td><?= (int)$r['defense'] ?></td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        <?php elseif ($tab === 'zeny'): ?>
          <thead><tr><th>#</th><th>Character</th><th>Class</th><th>Level</th><th>Zeny</th><th>Status</th></tr></thead>
          <tbody>
            <?php if (empty($rows)): ?>
              <tr><td colspan="6" style="text-align:center;color:var(--ink-faint);padding:34px;">No characters found.</td></tr>
            <?php else: foreach ($rows as $i => $r): $rank = (($page - 1) * 25) + $i + 1; ?>
              <tr>
                <td><span class="rank-num rank-<?= $rank <= 3 ? $rank : 0 ?>"><?= $rank ?></span></td>
                <td><strong><?= Helpers::e($r['name']) ?></strong><?= $r['guild_name'] ? '<div style="font-size:12px;color:var(--ink-soft);">' . Helpers::e($r['guild_name']) . '</div>' : '' ?></td>
                <td><?= Helpers::e(Helpers::jobName((int)$r['class'])) ?></td>
                <td><?= (int)$r['base_level'] ?> / <?= (int)$r['job_level'] ?></td>
                <td><strong style="color:var(--accent-deep);"><?= Helpers::e(Helpers::formatZeny($r['zeny'])) ?></strong></td>
                <td><span class="online-pill <?= (int)$r['online'] === 1 ? 'on' : '' ?>"><?= (int)$r['online'] === 1 ? 'Online' : 'Offline' ?></span></td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        <?php elseif ($tab === 'mvp'): ?>
          <thead><tr><th>#</th><th>Hunter</th><th>Class</th><th>Level</th><th>MVP Kills</th><th>Last Kill</th><th>Status</th></tr></thead>
          <tbody>
            <?php if (empty($rows)): ?>
              <tr><td colspan="7" style="text-align:center;color:var(--ink-faint);padding:34px;">No MVP kills recorded yet.</td></tr>
            <?php else: foreach ($rows as $i => $r): $rank = (($page - 1) * 25) + $i + 1; ?>
              <tr>
                <td><span class="rank-num rank-<?= $rank <= 3 ? $rank : 0 ?>"><?= $rank ?></span></td>
                <td><strong><?= Helpers::e($r['name']) ?></strong></td>
                <td><?= Helpers::e(Helpers::jobName((int)$r['class'])) ?></td>
                <td>Lv. <?= (int)$r['base_level'] ?></td>
                <td><strong style="color:var(--accent-deep);"><?= number_format((int)$r['mvp_kills']) ?></strong></td>
                <td><?= Helpers::e($r['last_mvp']) ?></td>
                <td><span class="online-pill <?= (int)$r['online'] === 1 ? 'on' : '' ?>"><?= (int)$r['online'] === 1 ? 'Online' : 'Offline' ?></span></td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        <?php else: ?>
          <thead><tr><th>#</th><th>Character</th><th>Class</th><th>Base / Job Lv</th><th>Map</th><th>Status</th></tr></thead>
          <tbody>
            <?php if (empty($rows)): ?>
              <tr><td colspan="6" style="text-align:center;color:var(--ink-faint);padding:34px;">No adventurers found.</td></tr>
            <?php else: foreach ($rows as $i => $r): $rank = (($page - 1) * 25) + $i + 1; ?>
              <tr>
                <td><span class="rank-num rank-<?= $rank <= 3 ? $rank : 0 ?>"><?= $rank ?></span></td>
                <td><strong><?= Helpers::e($r['name']) ?></strong><?= $r['guild_name'] ? '<div style="font-size:12px;color:var(--ink-soft);">' . Helpers::e($r['guild_name']) . '</div>' : '' ?></td>
                <td><?= Helpers::e(Helpers::jobName((int)$r['class'])) ?></td>
                <td><strong><?= (int)$r['base_level'] ?></strong> / <?= (int)$r['job_level'] ?></td>
                <td><code><?= Helpers::e($r['last_map']) ?></code></td>
                <td><span class="online-pill <?= (int)$r['online'] === 1 ? 'on' : '' ?>"><?= (int)$r['online'] === 1 ? 'Online' : 'Offline' ?></span></td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        <?php endif; ?>
      </table>
    </div>

    <?php if ($result['pages'] > 1): ?>
      <div class="pager">
        <?php for ($p = 1; $p <= min(10, $result['pages']); $p++): ?>
          <a class="pg <?= $p === $page ? 'active' : '' ?>" href="<?= Helpers::e(Helpers::baseUrl("rankings?tab={$tab}&q=" . urlencode($search) . "&p={$p}")) ?>"><?= $p ?></a>
        <?php endfor; ?>
      </div>
    <?php endif; ?>
  </div>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
