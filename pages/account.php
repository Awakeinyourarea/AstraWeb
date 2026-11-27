<?php
declare(strict_types=1);

use App\Auth;
use App\Helpers;

if (!$currentUser) {
    header('Location: ' . Helpers::baseUrl('login'));
    exit;
}

$accountId = (int)$currentUser['account_id'];
$msg = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Helpers::verifyCsrf($_POST['_csrf'] ?? null)) {
        $msg = ['type' => 'error', 'text' => 'Invalid CSRF token. Please refresh the page.'];
    } else {
        $action = (string)($_POST['action'] ?? '');
        if ($action === 'reset_pos') {
            $res = Auth::resetPosition($accountId, (int)($_POST['char_id'] ?? 0));
            $msg = $res['ok']
                ? ['type' => 'success', 'text' => "Moved {$res['name']} back to Prontera (156, 191)."]
                : ['type' => 'error', 'text' => $res['error']];
        } elseif ($action === 'reset_look') {
            $res = Auth::resetLook($accountId, (int)($_POST['char_id'] ?? 0));
            $msg = $res['ok']
                ? ['type' => 'success', 'text' => "Reset hair & palette style for {$res['name']}."]
                : ['type' => 'error', 'text' => $res['error']];
        } elseif ($action === 'change_pass') {
            $newPass = (string)($_POST['new_password'] ?? '');
            $confPass = (string)($_POST['confirm_password'] ?? '');
            if ($newPass !== $confPass) {
                $msg = ['type' => 'error', 'text' => 'New passwords do not match.'];
            } else {
                $res = Auth::changePassword($accountId, (string)($_POST['current_password'] ?? ''), $newPass);
                $msg = $res['ok']
                    ? ['type' => 'success', 'text' => 'Password updated successfully.']
                    : ['type' => 'error', 'text' => $res['error']];
            }
        }
    }
}

$characters = Auth::userCharacters($accountId);
require __DIR__ . '/../partials/header.php';
?>
<section class="page-hero">
  <div class="wrap">
    <div class="eyebrow hero-eyebrow">Control Panel</div>
    <h1>Welcome, <span class="hl"><?= Helpers::e($currentUser['userid']) ?></span></h1>
    <p>Account ID #<?= $accountId ?> &bull; Manage characters and security settings.</p>
  </div>
</section>

<div class="page-main">
  <?php if ($msg): ?>
    <div class="alert <?= $msg['type'] === 'success' ? 'alert-success' : 'alert-error' ?>">
      <?= Helpers::e($msg['text']) ?>
    </div>
  <?php endif; ?>

  <div class="grid-4" style="margin-bottom:24px;">
    <div class="fcard"><span class="fcard-badge">Email</span><h3><?= Helpers::e($currentUser['email']) ?></h3><p>Registered address</p></div>
    <div class="fcard"><span class="fcard-badge">Characters</span><h3><?= count($characters) ?> / <?= (int)$currentUser['character_slots'] ?></h3><p>Active slots</p></div>
    <div class="fcard"><span class="fcard-badge">Logins</span><h3><?= number_format((int)$currentUser['logincount']) ?></h3><p>Last IP: <?= Helpers::e($currentUser['last_ip'] ?: '—') ?></p></div>
    <div class="fcard"><span class="fcard-badge">Last Seen</span><h3 style="font-size:15px;"><?= Helpers::e($currentUser['lastlogin'] ?: 'Never') ?></h3><p>Gender: <?= Helpers::e($currentUser['sex']) ?></p></div>
  </div>

  <div class="panel" style="margin-bottom:24px;">
    <div class="eyebrow">Characters</div>
    <h2 style="font-family:var(--font-display);margin:4px 0 16px;font-size:24px;">Your Adventurers</h2>
    <div class="table-wrap">
      <table class="rk-table">
        <thead><tr><th>Name</th><th>Class</th><th>Base / Job</th><th>Zeny</th><th>Map</th><th>Status</th><th>Self-Service</th></tr></thead>
        <tbody>
          <?php if (empty($characters)): ?>
            <tr><td colspan="7" style="text-align:center;color:var(--ink-faint);padding:34px;">No characters created on this account yet.</td></tr>
          <?php else: foreach ($characters as $c): ?>
            <tr>
              <td><strong><?= Helpers::e($c['name']) ?></strong><?= $c['guild_name'] ? '<div style="font-size:12px;color:var(--ink-soft);">' . Helpers::e($c['guild_name']) . '</div>' : '' ?></td>
              <td><?= Helpers::e(Helpers::jobName((int)$c['class'])) ?></td>
              <td><strong><?= (int)$c['base_level'] ?></strong> / <?= (int)$c['job_level'] ?></td>
              <td><?= Helpers::e(Helpers::formatZeny($c['zeny'])) ?></td>
              <td><code><?= Helpers::e($c['last_map']) ?> (<?= (int)$c['last_x'] ?>, <?= (int)$c['last_y'] ?>)</code></td>
              <td><span class="online-pill <?= (int)$c['online'] === 1 ? 'on' : '' ?>"><?= (int)$c['online'] === 1 ? 'Online' : 'Offline' ?></span></td>
              <td>
                <div style="display:flex;gap:6px;flex-wrap:wrap;">
                  <form method="post" style="display:inline;">
                    <input type="hidden" name="_csrf" value="<?= Helpers::e(Helpers::csrfToken()) ?>">
                    <input type="hidden" name="action" value="reset_pos">
                    <input type="hidden" name="char_id" value="<?= (int)$c['char_id'] ?>">
                    <button class="btn btn-sm btn-ghost" type="submit" <?= (int)$c['online'] === 1 ? 'disabled title="Log out first"' : '' ?>>Reset Pos</button>
                  </form>
                  <form method="post" style="display:inline;">
                    <input type="hidden" name="_csrf" value="<?= Helpers::e(Helpers::csrfToken()) ?>">
                    <input type="hidden" name="action" value="reset_look">
                    <input type="hidden" name="char_id" value="<?= (int)$c['char_id'] ?>">
                    <button class="btn btn-sm btn-ghost" type="submit" <?= (int)$c['online'] === 1 ? 'disabled title="Log out first"' : '' ?>>Reset Look</button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="panel form-card" style="margin:0;">
    <div class="eyebrow">Security</div>
    <h2 style="font-family:var(--font-display);margin:4px 0 16px;font-size:24px;">Change Password</h2>
    <form method="post">
      <input type="hidden" name="_csrf" value="<?= Helpers::e(Helpers::csrfToken()) ?>">
      <input type="hidden" name="action" value="change_pass">
      <div class="field">
        <label for="current_password">Current Password</label>
        <input class="input" id="current_password" name="current_password" type="password" required>
      </div>
      <div class="field">
        <label for="new_password">New Password</label>
        <input class="input" id="new_password" name="new_password" type="password" required minlength="4" maxlength="32">
      </div>
      <div class="field">
        <label for="confirm_password">Confirm New Password</label>
        <input class="input" id="confirm_password" name="confirm_password" type="password" required minlength="4" maxlength="32">
      </div>
      <button class="btn btn-gold" type="submit">Update Password</button>
    </form>
  </div>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
