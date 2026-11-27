<?php
declare(strict_types=1);

use App\Helpers;
?>
</main>
<footer class="foot">
  <div class="wrap">
    <div class="f-brand">
      <span class="f-by brandname"><?= Helpers::e(SITE_NAME) ?></span>
      <span class="f-sep">&middot;</span>
      <span class="f-by">Ceres CP</span>
    </div>
    <div class="socials">
      <a href="#" aria-label="Instagram" class="social-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
          <rect x="3" y="3" width="18" height="18" rx="5"></rect>
          <circle cx="12" cy="12" r="4"></circle>
          <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"></circle>
        </svg>
      </a>
      <a href="#" aria-label="Facebook" class="social-link">
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
          <path d="M14 9h3V5h-3c-2.2 0-4 1.8-4 4v2H7v4h3v6h4v-6h3l1-4h-4V9c0-.6.4-1 1-1z"></path>
        </svg>
      </a>
      <a href="<?= Helpers::e(DISCORD_URL) ?>" target="_blank" rel="noopener noreferrer" aria-label="Discord" class="social-link">
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
          <path d="M19.5 5.5A16 16 0 0 0 15.5 4l-.3.5c1.4.4 2.6 1 3.7 1.7a13 13 0 0 0-11.8 0c1.1-.7 2.3-1.3 3.7-1.7L10.5 4A16 16 0 0 0 6.5 5.5C4 9 3.3 12.5 3.5 16a16 16 0 0 0 5 2.5l.6-1c-.8-.3-1.6-.7-2.3-1.2l.6-.4a11.5 11.5 0 0 0 9.2 0l.6.4c-.7.5-1.5.9-2.3 1.2l.6 1a16 16 0 0 0 5-2.5c.3-4-.8-7.5-3.6-10.5zM9.5 14c-.8 0-1.5-.8-1.5-1.7s.7-1.7 1.5-1.7 1.5.8 1.5 1.7-.7 1.7-1.5 1.7zm5 0c-.8 0-1.5-.8-1.5-1.7s.7-1.7 1.5-1.7 1.5.8 1.5 1.7-.7 1.7-1.5 1.7z"></path>
        </svg>
      </a>
    </div>
  </div>
</footer>
<div id="copyToast" class="copy-toast" role="status" aria-live="polite"></div>
<script src="<?= Helpers::e(Helpers::baseUrl('assets/js/app.js')) ?>"></script>
</body>
</html>
