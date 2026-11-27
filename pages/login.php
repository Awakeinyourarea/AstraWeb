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
        $res = Auth::login((string)($_POST['userid'] ?? ''), (string)($_POST['password'] ?? ''));
        if ($res['ok']) {
            Helpers::setFlash('success', 'Welcome back to ' . SITE_NAME . '!');
            header('Location: ' . Helpers::baseUrl('account'));
            exit;
        }
        $error = $res['error'];
    }
}

require __DIR__ . '/../partials/header.php';
?>
<section class="page-hero">
  <div class="wrap">
    <div class="eyebrow hero-eyebrow">Account Access</div>
    <h1>Sign In to <span class="hl"><?= Helpers::e(SITE_NAME) ?></span></h1>
    <p>Manage your characters, reset stuck positions, and update account security.</p>
  </div>
</section>

<div class="page-main">
  <div class="panel form-card">
    <?php if ($error !== ''): ?>
      <div class="alert alert-error"><?= Helpers::e($error) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= Helpers::e(Helpers::baseUrl('login')) ?>">
      <input type="hidden" name="_csrf" value="<?= Helpers::e(Helpers::csrfToken()) ?>">
      <div class="field">
        <label for="userid">Username</label>
        <input class="input" id="userid" name="userid" type="text" required autocomplete="username" value="<?= Helpers::e($_POST['userid'] ?? '') ?>">
      </div>
      <div class="field">
        <label for="password">Password</label>
        <input class="input" id="password" name="password" type="password" required autocomplete="current-password">
      </div>
      <button class="btn btn-gold" type="submit" style="width:100%;">Login</button>
    </form>

    <p style="text-align:center;margin:18px 0 0;color:var(--ink-soft);font-size:14px;">
      Need an account? <a href="<?= Helpers::e(Helpers::baseUrl('register')) ?>" style="color:var(--accent-deep);font-weight:800;">Register now</a>
    </p>
  </div>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
