<?php
declare(strict_types=1);

use App\Auth;
use App\Helpers;

if ($currentUser) {
    header('Location: ' . Helpers::baseUrl('account'));
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Helpers::verifyCsrf($_POST['_csrf'] ?? null)) {
        $error = 'Invalid session token. Please refresh and try again.';
    } else {
        $pass = (string)($_POST['password'] ?? '');
        $confirm = (string)($_POST['password_confirm'] ?? '');
        if ($pass !== $confirm) {
            $error = 'Passwords do not match.';
        } else {
            $res = Auth::register(
                (string)($_POST['userid'] ?? ''),
                $pass,
                (string)($_POST['email'] ?? ''),
                (string)($_POST['sex'] ?? 'M'),
                (string)($_POST['birthdate'] ?? '2000-01-01')
            );
            if ($res['ok']) {
                Helpers::setFlash('success', 'Account created! You can now log into the game client.');
                header('Location: ' . Helpers::baseUrl('account'));
                exit;
            }
            $error = $res['error'];
        }
    }
}

require __DIR__ . '/../partials/header.php';
?>
<section class="page-hero">
  <div class="wrap">
    <div class="eyebrow hero-eyebrow">Begin Adventure</div>
    <h1>Create <span class="hl">Game Account</span></h1>
    <p>Register directly to <?= Helpers::e(SITE_NAME) ?> and start exploring Midgard.</p>
  </div>
</section>

<div class="page-main">
  <div class="panel form-card">
    <?php if ($error !== ''): ?>
      <div class="alert alert-error"><?= Helpers::e($error) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= Helpers::e(Helpers::baseUrl('register')) ?>">
      <input type="hidden" name="_csrf" value="<?= Helpers::e(Helpers::csrfToken()) ?>">
      <div class="field">
        <label for="userid">Username (4–23 chars, A–Z / 0–9 / _)</label>
        <input class="input" id="userid" name="userid" type="text" required minlength="4" maxlength="23" pattern="[A-Za-z0-9_]{4,23}" value="<?= Helpers::e($_POST['userid'] ?? '') ?>">
      </div>
      <div class="field">
        <label for="email">Email Address</label>
        <input class="input" id="email" name="email" type="email" required maxlength="39" value="<?= Helpers::e($_POST['email'] ?? '') ?>">
      </div>
      <div class="grid-2">
        <div class="field">
          <label for="sex">Account Gender</label>
          <select class="select" id="sex" name="sex">
            <option value="M" <?= ($_POST['sex'] ?? 'M') === 'M' ? 'selected' : '' ?>>Male</option>
            <option value="F" <?= ($_POST['sex'] ?? '') === 'F' ? 'selected' : '' ?>>Female</option>
          </select>
        </div>
        <div class="field">
          <label for="birthdate">Birthdate</label>
          <input class="input" id="birthdate" name="birthdate" type="date" value="<?= Helpers::e($_POST['birthdate'] ?? '2000-01-01') ?>">
        </div>
      </div>
      <div class="field">
        <label for="password">Password</label>
        <input class="input" id="password" name="password" type="password" required minlength="4" maxlength="32">
      </div>
      <div class="field">
        <label for="password_confirm">Confirm Password</label>
        <input class="input" id="password_confirm" name="password_confirm" type="password" required minlength="4" maxlength="32">
      </div>
      <button class="btn btn-gold" type="submit" style="width:100%;">Create Account</button>
    </form>

    <p style="text-align:center;margin:18px 0 0;color:var(--ink-soft);font-size:14px;">
      Already registered? <a href="<?= Helpers::e(Helpers::baseUrl('login')) ?>" style="color:var(--accent-deep);font-weight:800;">Sign in</a>
    </p>
  </div>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
