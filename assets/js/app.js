(() => {
  const html = document.documentElement;
  const nav = document.getElementById('mainNav');

  // Theme initialization & persistence
  const savedTheme = localStorage.getItem('honey_theme');
  const applyDark = (isDark) => {
    html.classList.toggle('dark', isDark);
    if (nav) nav.classList.toggle('is-dark', isDark);
  };
  if (savedTheme === 'dark') {
    applyDark(true);
  } else if (savedTheme === 'light') {
    applyDark(false);
  } else {
    applyDark(window.matchMedia?.('(prefers-color-scheme: dark)').matches ?? false);
  }

  const themeBtn = document.getElementById('themeToggle');
  if (themeBtn) {
    themeBtn.addEventListener('click', () => {
      const next = !html.classList.contains('dark');
      applyDark(next);
      localStorage.setItem('honey_theme', next ? 'dark' : 'light');
    });
  }

  // Navbar scroll glass effect
  const onScroll = () => {
    if (!nav) return;
    nav.classList.toggle('scrolled', window.scrollY > 24);
  };
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  // Dropdown menus
  document.querySelectorAll('.has-menu').forEach((menu) => {
    const trigger = menu.querySelector('.menu-trigger');
    if (!trigger) return;
    trigger.addEventListener('click', (e) => {
      e.stopPropagation();
      const willOpen = !menu.classList.contains('open');
      document.querySelectorAll('.has-menu.open').forEach((m) => m.classList.remove('open'));
      if (willOpen) menu.classList.add('open');
    });
  });

  document.addEventListener('click', () => {
    document.querySelectorAll('.has-menu.open').forEach((m) => m.classList.remove('open'));
  });

  // Mobile menu toggle
  const mobBtn = document.getElementById('mobileMenuBtn');
  const mobMenu = document.getElementById('mobileMenu');
  if (mobBtn && mobMenu) {
    mobBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      mobMenu.classList.toggle('open');
    });
  }

  // Reveal on scroll
  const reveals = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window && reveals.length) {
    const io = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('in');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });
    reveals.forEach((el) => io.observe(el));
  } else {
    reveals.forEach((el) => el.classList.add('in'));
  }

  // Interactive Server Features Switcher (01..04)
  const fsRoot = document.getElementById('featuresSwitcher');
  if (fsRoot) {
    const systems = JSON.parse(fsRoot.dataset.systems || '[]');
    let idx = 0;

    const tabs = fsRoot.querySelectorAll('.fs-tab');
    const stage = fsRoot.querySelector('.fs-stage');
    const counter = fsRoot.querySelector('[data-fs-counter]');
    const title = fsRoot.querySelector('[data-fs-title]');
    const desc = fsRoot.querySelector('[data-fs-desc]');
    const specs = fsRoot.querySelector('[data-fs-specs]');
    const link = fsRoot.querySelector('[data-fs-link]');
    const img = fsRoot.querySelector('[data-fs-img]');

    const renderSystem = (nextIdx) => {
      if (!systems.length) return;
      idx = (nextIdx + systems.length) % systems.length;
      const sys = systems[idx];

      tabs.forEach((t, i) => t.classList.toggle('active', i === idx));
      if (stage) stage.style.setProperty('--c', sys.color || '#e3a012');
      if (counter) counter.textContent = String(idx + 1).padStart(2, '0') + ' / ' + String(systems.length).padStart(2, '0');
      if (title) title.textContent = sys.title;
      if (desc) desc.textContent = sys.desc;
      if (link) link.href = sys.href;
      if (img) {
        img.src = sys.img;
        img.alt = sys.title;
      }
      if (specs) {
        specs.innerHTML = (sys.specs || [])
          .map(([k, v]) => `<div class="fs-spec"><span>${k}</span><b>${v}</b></div>`)
          .join('');
      }
    };

    tabs.forEach((tab, i) => tab.addEventListener('click', () => renderSystem(i)));
    fsRoot.querySelector('[data-fs-prev]')?.addEventListener('click', () => renderSystem(idx - 1));
    fsRoot.querySelector('[data-fs-next]')?.addEventListener('click', () => renderSystem(idx + 1));
  }

  // Copy helper (WhoSell /navi)
  window.copyText = (text) => {
    navigator.clipboard?.writeText(text).then(() => {
      const toast = document.getElementById('copyToast');
      if (!toast) return;
      toast.textContent = 'Copied: ' + text;
      toast.classList.add('show');
      setTimeout(() => toast.classList.remove('show'), 2200);
    });
  };
})();
