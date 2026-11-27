<?php
declare(strict_types=1);

use App\Helpers;
use App\ServerData;

$summary = ServerData::summary();
$champions = ServerData::champions();
$imgBase = Helpers::baseUrl('assets/img');

// 20 Battle Pass grid slots (matching honeyro_net 1:1)
$bpSlots = [
    ['img' => 'asset_90.png',  'alt' => 'Day 1 Reward',  'qty' => ''],
    ['img' => 'asset_91.png',  'alt' => 'Day 2 Reward',  'qty' => ''],
    ['img' => 'asset_92.png',  'alt' => 'Day 3 Reward',  'qty' => ''],
    ['img' => 'asset_93.png',  'alt' => 'Day 4 Reward',  'qty' => ''],
    ['img' => 'asset_94.png',  'alt' => 'Day 5 Reward',  'qty' => ''],
    ['img' => 'asset_95.png',  'alt' => 'Day 6 Reward',  'qty' => ''],
    ['img' => 'asset_91.png',  'alt' => 'Day 7 Reward',  'qty' => 'x2'],
    ['img' => 'asset_92.png',  'alt' => 'Day 8 Reward',  'qty' => ''],
    ['img' => 'asset_93.png',  'alt' => 'Day 9 Reward',  'qty' => ''],
    ['img' => 'asset_96.png',  'alt' => 'Day 10 Reward', 'qty' => 'x5'],
    ['img' => 'asset_97.png',  'alt' => 'Day 11 Reward', 'qty' => 'x2'],
    ['img' => 'asset_91.png',  'alt' => 'Day 12 Reward', 'qty' => 'x3'],
    ['img' => 'asset_98.png',  'alt' => 'Day 13 Reward', 'qty' => ''],
    ['img' => 'asset_93.png',  'alt' => 'Day 14 Reward', 'qty' => 'x2'],
    ['img' => 'asset_96.png',  'alt' => 'Day 15 Reward', 'qty' => 'x5'],
    ['img' => 'asset_99.png',  'alt' => 'Day 16 Reward', 'qty' => ''],
    ['img' => 'asset_100.png', 'alt' => 'Day 17 Reward', 'qty' => ''],
    ['img' => 'asset_101.png', 'alt' => 'Day 18 Reward', 'qty' => ''],
    ['img' => 'asset_102.png', 'alt' => 'Day 19 Reward', 'qty' => ''],
    ['img' => 'asset_103.png', 'alt' => 'Day 20 Reward', 'qty' => ''],
];

// Interactive Server Features Switcher (01..04)
$featureSystems = [
    [
        'num'   => '01',
        'title' => 'Card Recycler',
        'desc'  => 'Recycle unwanted cards into random rewards—keep farming meaningful.',
        'specs' => [
            ['Location', 'Main city NPC'],
            ['Type', 'Repeatable'],
            ['Ideal for', 'Farmers & collectors'],
        ],
        'href'  => Helpers::baseUrl('wiki#server-info'),
        'img'   => $imgBase . '/card-recycler-trim.webp',
        'color' => '#e3a012',
    ],
    [
        'num'   => '02',
        'title' => 'Monster Hunter',
        'desc'  => 'Take on daily & weekly bounty contracts across Rune Midgard for exclusive tokens and gear progression.',
        'specs' => [
            ['Location', 'Bounty Board'],
            ['Type', 'Daily & Weekly'],
            ['Ideal for', 'Solo & party grinders'],
        ],
        'href'  => Helpers::baseUrl('database?tab=mobs'),
        'img'   => $imgBase . '/poring-feature.webp',
        'color' => '#2f6fd6',
    ],
    [
        'num'   => '03',
        'title' => 'Costume & Headgear Quest',
        'desc'  => 'Craft iconic hats and seasonal visual costumes using materials gathered from classic monsters.',
        'specs' => [
            ['Location', 'Prontera Atelier'],
            ['Type', 'Crafting Quest'],
            ['Ideal for', 'Fashion & collectors'],
        ],
        'href'  => Helpers::baseUrl('database?tab=items'),
        'img'   => $imgBase . '/episode.webp',
        'color' => '#d6356a',
    ],
    [
        'num'   => '04',
        'title' => 'Pet Evolution System',
        'desc'  => 'Raise loyal companions to maximum intimacy and evolve them into stronger partners with bonus utility.',
        'specs' => [
            ['Location', 'Taming & Pet UI'],
            ['Type', 'Companion'],
            ['Ideal for', 'Adventurers & AFK'],
        ],
        'href'  => Helpers::baseUrl('wiki#commands'),
        'img'   => $imgBase . '/level.webp',
        'color' => '#5aa23a',
    ],
];

require __DIR__ . '/../partials/header.php';
?>

<div class="home-page">
  <!-- Floating Discord Mascot -->
  <a class="discord-float" href="<?= Helpers::e(DISCORD_URL) ?>" target="_blank" rel="noopener noreferrer" title="Join our Discord">
    <img src="<?= Helpers::e($imgBase . '/poring-feature.webp') ?>" alt="Join our Discord" class="discord-img">
  </a>

  <!-- 1. Hero -->
  <header id="top" class="hero">
    <video class="hero-bg" autoplay muted loop playsinline>
      <source src="<?= Helpers::e($imgBase . '/main.webm') ?>" type="video/webm">
    </video>
    <div class="hero-scrim"></div>
    <div class="hero-comb"></div>

    <div class="hero-inner">
      <div class="eyebrow hero-eyebrow">Welcome to <?= Helpers::e(SITE_NAME) ?></div>
      <img src="<?= Helpers::e($imgBase . '/logo.webp') ?>" alt="<?= Helpers::e(SITE_NAME) ?> Logo" class="hero-logo">

      <div class="hero-stats">
        <div class="stat">
          <span class="lab"><span class="dot <?= $summary['server_online'] ? '' : 'off' ?>"></span>Online</span>
          <span class="num"><?= number_format($summary['online_players']) ?></span>
        </div>
        <div class="stat">
          <span class="lab"><span class="star">★</span>Autotrade</span>
          <span class="num"><?= number_format($summary['autotrade_players']) ?></span>
        </div>
      </div>

      <p class="hero-lead">
        Step into a classic Pre-Renewal world with a sweet twist. <?= Helpers::e(SITE_NAME) ?> keeps the old-school vibe while adding quality-of-life systems, fair progression, and long-term goals that make every session feel rewarding.
      </p>

      <div class="hero-cta">
        <a href="<?= Helpers::e(Helpers::baseUrl('downloads')) ?>" class="btn-honey btn-honey-lg">Download ↓</a>
        <?php if ($currentUser): ?>
          <a href="<?= Helpers::e(Helpers::baseUrl('account')) ?>" class="btn btn-ghost btn-lg on-dark">Account Panel</a>
        <?php else: ?>
          <a href="<?= Helpers::e(Helpers::baseUrl('register')) ?>" class="btn btn-ghost btn-lg on-dark">Register Now</a>
        <?php endif; ?>
      </div>
    </div>
    <div class="hero-fade"></div>
  </header>

  <!-- 2. Active Season -->
  <section class="sec season">
    <div class="wrap reveal center">
      <div class="ornament">
        <span class="gem"></span>
        <span>Active Season</span>
        <span class="gem"></span>
      </div>
    </div>

    <div class="sb season-banner reveal">
      <img class="sb-bg" src="<?= Helpers::e($imgBase . '/honey_share.jpg') ?>" alt="Digimon Season" style="object-position:50% 100%;transform-origin:50% 100%;transform:scale(1.35);" draggable="false">
      <div class="sb-veil"></div>

      <?php
        $sparks = [
            ['0%', '0s', '7s', '3px'], ['37%', '-0.9s', '8s', '4px'], ['74%', '-1.8s', '9s', '5px'],
            ['11%', '-2.7s', '10s', '3px'], ['48%', '-3.6s', '11s', '4px'], ['85%', '-4.5s', '7s', '5px'],
            ['22%', '-5.4s', '8s', '3px'], ['59%', '-6.3s', '9s', '4px'], ['96%', '-7.2s', '10s', '5px'],
            ['33%', '-8.1s', '11s', '3px'], ['70%', '-9s', '7s', '4px'], ['7%', '-9.9s', '8s', '5px'],
        ];
        foreach ($sparks as [$left, $delay, $dur, $size]):
      ?>
        <span class="spark" style="left:<?= $left ?>;animation-delay:<?= $delay ?>;animation-duration:<?= $dur ?>;width:<?= $size ?>;height:<?= $size ?>;"></span>
      <?php endforeach; ?>

      <div class="sb-body">
        <div class="sb-text">
          <div class="sb-tags">
            <span class="sb-tag"><span class="pulse"></span>Active Season</span>
            <span class="sb-sub">Digital Monsters</span>
          </div>
          <h2 class="sb-title">Digimon Season</h2>
          <p class="sb-desc">The Digital World has opened its gates in Prontera. Tai and Matt have arrived to guide this new season of adventures.</p>
          <p class="sb-meta">✦ Active until July 31, 2026</p>
          <a href="<?= Helpers::e(Helpers::baseUrl('wiki')) ?>" class="sb-btn">Learn more on the Wiki →</a>
        </div>
      </div>
    </div>
  </section>

  <!-- 3. Server Information -->
  <section class="sec info">
    <div class="wrap">
      <div class="reveal center">
        <div class="ornament">
          <span class="gem"></span>
          <span>Server Information</span>
          <span class="gem"></span>
        </div>
        <h2 class="info-heading">Classic Pre-Renewal, done right</h2>
      </div>

      <div class="info-grid">
        <div class="reveal">
          <article class="info-card">
            <span aria-hidden="true" class="corner-tl" style="width:18px;height:18px;"></span>
            <span aria-hidden="true" class="corner-br" style="width:18px;height:18px;"></span>
            <div class="info-art">
              <span class="info-art-glow"></span>
              <img src="<?= Helpers::e($imgBase . '/rates.webp') ?>" alt="Rates">
            </div>
            <div class="info-label">Rates</div>
            <h3 class="info-val"><?= Helpers::e(SITE_RATES_SHORT) ?></h3>
            <div class="info-div"></div>
            <p class="info-desc">Balanced rates for a fun, steady progression—fast enough to enjoy, slow enough to matter.</p>
          </article>
        </div>

        <div class="reveal">
          <article class="info-card">
            <span aria-hidden="true" class="corner-tl" style="width:18px;height:18px;"></span>
            <span aria-hidden="true" class="corner-br" style="width:18px;height:18px;"></span>
            <div class="info-art">
              <span class="info-art-glow"></span>
              <img src="<?= Helpers::e($imgBase . '/level.webp') ?>" alt="Max Level">
            </div>
            <div class="info-label">Max Level</div>
            <h3 class="info-val"><?= Helpers::e(SITE_MAX_LEVEL) ?></h3>
            <div class="info-div"></div>
            <p class="info-desc">Pure Pre-Renewal limits: classic builds, meaningful choices, and familiar pacing.</p>
          </article>
        </div>

        <div class="reveal">
          <article class="info-card">
            <span aria-hidden="true" class="corner-tl" style="width:18px;height:18px;"></span>
            <span aria-hidden="true" class="corner-br" style="width:18px;height:18px;"></span>
            <div class="info-art">
              <span class="info-art-glow"></span>
              <img src="<?= Helpers::e($imgBase . '/episode.webp') ?>" alt="Episode">
            </div>
            <div class="info-label">Episode</div>
            <h3 class="info-val"><?= Helpers::e(SITE_EPISODE) ?></h3>
            <div class="info-div"></div>
            <p class="info-desc">Progressive content roadmap: we start at Nameless and move forward as the server grows.</p>
          </article>
        </div>

        <div class="reveal">
          <article class="info-card">
            <span aria-hidden="true" class="corner-tl" style="width:18px;height:18px;"></span>
            <span aria-hidden="true" class="corner-br" style="width:18px;height:18px;"></span>
            <div class="info-art">
              <span class="info-art-glow"></span>
              <img src="<?= Helpers::e($imgBase . '/security.webp') ?>" alt="International Server">
            </div>
            <div class="info-label">Region</div>
            <h3 class="info-val">International</h3>
            <div class="info-div"></div>
            <p class="info-desc">Multilingual support and friendly community vibes—play together without barriers.</p>
          </article>
        </div>
      </div>
    </div>
  </section>

  <!-- 4. Battle Pass -->
  <section class="sec bpass">
    <div class="wrap">
      <div class="reveal bp-head">
        <div>
          <div class="bp-kicker">Systems</div>
          <h2 class="bp-title"><?= Helpers::e(SITE_NAME) ?> Battle Pass</h2>
          <p class="bp-lead">Complete monthly missions, level up your pass, and unlock rewards that blend cosmetics, convenience and progression-only goodies.</p>
        </div>
        <div class="bp-head-right">
          <span class="bp-status"><span class="bp-status-dot"></span>Season in progress</span>
          <a href="<?= Helpers::e(Helpers::baseUrl('wiki')) ?>" class="bp-view-btn">View full Battle Pass</a>
        </div>
      </div>

      <div class="reveal">
        <div class="bp-card">
          <span aria-hidden="true" class="corner-tl" style="width:20px;height:20px;"></span>
          <span aria-hidden="true" class="corner-br" style="width:20px;height:20px;"></span>
          <span class="bp-featured-tag">Season 01 · Featured</span>

          <div>
            <div class="bp-board">
              <img src="<?= Helpers::e($imgBase . '/bp.webp') ?>" alt="Battle Pass <?= Helpers::e(SITE_NAME) ?>">
              <div class="bp-grid">
                <?php foreach ($bpSlots as $slot): ?>
                  <div class="bp-slot" title="<?= Helpers::e($slot['alt']) ?>">
                    <img src="<?= Helpers::e($imgBase . '/' . $slot['img']) ?>" alt="<?= Helpers::e($slot['alt']) ?>">
                    <?php if ($slot['qty'] !== ''): ?>
                      <small class="bp-qty"><?= Helpers::e($slot['qty']) ?></small>
                    <?php endif; ?>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

          <div>
            <div class="bp-kicker">Seasonal Progression</div>
            <h2 style="margin:14px 0 26px;font-size:clamp(26px,3vw,38px);text-transform:uppercase;line-height:1.1;color:var(--bp-title);">
              Level up your <span style="color:var(--bp-gold-hi);">Battle Pass</span> every season.
            </h2>

            <div class="bp-progress-box">
              <div class="bp-progress-top">
                <span style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:0.18em;color:var(--bp-text);">Season 01 · In progress</span>
                <span style="display:inline-flex;align-items:baseline;gap:6px;font-size:14px;color:var(--bp-text);">
                  Lv. <strong class="bp-lv">23</strong><span>/ 50</span>
                </span>
              </div>
              <div class="bp-bar">
                <div class="bp-bar-fill"></div>
                <span class="bp-tick" style="left:20%;"></span>
                <span class="bp-tick" style="left:40%;"></span>
                <span class="bp-tick" style="left:60%;"></span>
                <span class="bp-tick" style="left:80%;"></span>
                <span class="bp-gem"></span>
              </div>
              <div class="bp-scale" aria-hidden="true">
                <span>1</span><span>10</span><span>20</span><span>30</span><span>40</span><span>50</span>
              </div>
              <div style="margin-top:14px;font-size:13px;color:var(--bp-text);">1,240 / 2,000 XP to your next reward</div>
            </div>

            <div class="bp-chips">
              <span class="bp-chip">Costumes</span>
              <span class="bp-chip">Consumables</span>
              <span class="bp-chip">XP Boosts</span>
              <span class="bp-chip">Premium currency</span>
            </div>

            <div style="display:flex;flex-wrap:wrap;align-items:center;gap:22px;">
              <a href="<?= Helpers::e(Helpers::baseUrl('wiki')) ?>" class="btn-honey btn-honey-lg">Claim your rewards</a>
              <span style="font-size:11.5px;font-weight:800;text-transform:uppercase;letter-spacing:0.14em;color:var(--bp-green);">100% Free</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 5. Hall of Champions -->
  <section class="sec players">
    <div class="players-beams"></div>
    <div class="players-floor"></div>

    <div class="wrap">
      <div class="reveal center">
        <div class="ornament">
          <i style="font-style:normal;font-size:15px;color:var(--accent-deep);">♛</i>
          <span>Hall of Champions</span>
          <i style="font-style:normal;font-size:15px;color:var(--accent-deep);">♛</i>
        </div>
        <h2 class="info-heading">The reigning #1 of each battlefield</h2>
        <p style="margin-top:10px;font-size:16px;color:var(--ink-soft);">Will you take their crown?</p>
      </div>

      <div class="podium-grid">
        <div class="champ-wrap reveal" style="--cat:#d2481f;">
          <article class="champ-card">
            <div class="champ-stage">
              <span class="champ-aura"></span>
              <span class="champ-shadow"></span>
              <span class="champ-disc"></span>
              <img src="<?= Helpers::e($imgBase . '/honey.png') ?>" alt="<?= Helpers::e($champions['pvp']) ?>" class="champ-img">
            </div>
            <div class="champ-pedestal">
              <span aria-hidden="true" class="corner-tl" style="width:16px;height:16px;"></span>
              <span aria-hidden="true" class="corner-br" style="width:16px;height:16px;"></span>
              <div class="champ-cat">Champion of PvP</div>
              <div class="champ-name"><?= Helpers::e($champions['pvp']) ?></div>
              <div class="champ-sub">Most arena kills this season</div>
            </div>
          </article>
        </div>

        <div class="champ-wrap center-champ reveal" style="--cat:#2f6fd6;">
          <article class="champ-card">
            <div class="champ-stage">
              <span class="champ-aura"></span>
              <span class="champ-shadow"></span>
              <span class="champ-disc"></span>
              <img src="<?= Helpers::e($imgBase . '/honey.png') ?>" alt="<?= Helpers::e($champions['woe']) ?>" class="champ-img">
            </div>
            <div class="champ-pedestal">
              <span aria-hidden="true" class="corner-tl" style="width:16px;height:16px;"></span>
              <span aria-hidden="true" class="corner-br" style="width:16px;height:16px;"></span>
              <div class="champ-cat">Champion of WoE</div>
              <div class="champ-name"><?= Helpers::e($champions['woe']) ?></div>
              <div class="champ-sub">Most castles held in War of Emperium</div>
            </div>
          </article>
        </div>

        <div class="champ-wrap reveal" style="--cat:#dba00f;">
          <article class="champ-card">
            <div class="champ-stage">
              <span class="champ-aura"></span>
              <span class="champ-shadow"></span>
              <span class="champ-disc"></span>
              <img src="<?= Helpers::e($imgBase . '/honey.png') ?>" alt="<?= Helpers::e($champions['mvp']) ?>" class="champ-img">
            </div>
            <div class="champ-pedestal">
              <span aria-hidden="true" class="corner-tl" style="width:16px;height:16px;"></span>
              <span aria-hidden="true" class="corner-br" style="width:16px;height:16px;"></span>
              <div class="champ-cat">Champion of MVP</div>
              <div class="champ-name"><?= Helpers::e($champions['mvp']) ?></div>
              <div class="champ-sub">Most MVP bosses defeated</div>
            </div>
          </article>
        </div>
      </div>
    </div>
  </section>

  <!-- 6. Server Features (Interactive Switcher) -->
  <section class="sec features">
    <div class="wrap">
      <div class="reveal center">
        <div class="ornament">
          <span class="gem"></span>
          <span>Server Features</span>
          <span class="gem"></span>
        </div>
        <h2 style="margin-top:4px;font-size:clamp(28px,4vw,46px);font-weight:800;line-height:1.1;color:var(--fs-title);">What makes <?= Helpers::e(SITE_NAME) ?> special</h2>
        <p style="margin-top:8px;font-size:16px;color:var(--ink-soft);">Tap a system to explore what makes <?= Helpers::e(SITE_NAME) ?> special.</p>
      </div>

      <div class="reveal fs-layout" id="featuresSwitcher" data-systems="<?= Helpers::e(json_encode($featureSystems, JSON_UNESCAPED_SLASHES)) ?>">
        <div class="fs-tabs">
          <?php foreach ($featureSystems as $i => $sys): ?>
            <button type="button" class="fs-tab <?= $i === 0 ? 'active' : '' ?>">
              <span class="fs-tab-num"><?= Helpers::e($sys['num']) ?></span>
              <?= Helpers::e($sys['title']) ?>
            </button>
          <?php endforeach; ?>
        </div>

        <div class="fs-stage" style="--c:<?= Helpers::e($featureSystems[0]['color']) ?>;">
          <span aria-hidden="true" class="corner-tl" style="width:20px;height:20px;"></span>
          <span aria-hidden="true" class="corner-br" style="width:20px;height:20px;"></span>

          <div class="fs-info">
            <div class="fs-meta-top">
              <span style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:0.22em;color:var(--fs-gold);">System</span>
              <span data-fs-counter style="font-size:12px;font-weight:800;letter-spacing:0.14em;color:var(--fs-muted);">01 / 04</span>
            </div>

            <h2 class="fs-title" data-fs-title><?= Helpers::e($featureSystems[0]['title']) ?></h2>
            <p class="fs-desc" data-fs-desc><?= Helpers::e($featureSystems[0]['desc']) ?></p>

            <div class="fs-specs" data-fs-specs>
              <?php foreach ($featureSystems[0]['specs'] as [$k, $v]): ?>
                <div class="fs-spec">
                  <span><?= Helpers::e($k) ?></span>
                  <b><?= Helpers::e($v) ?></b>
                </div>
              <?php endforeach; ?>
            </div>

            <div class="fs-actions">
              <a href="<?= Helpers::e($featureSystems[0]['href']) ?>" data-fs-link class="btn-honey btn-honey-lg">Read More →</a>
              <button type="button" class="fs-nav-btn" data-fs-prev aria-label="Previous">‹</button>
              <button type="button" class="fs-nav-btn" data-fs-next aria-label="Next">›</button>
            </div>
          </div>

          <div class="fs-visual">
            <span class="fs-ring-outer"></span>
            <span class="fs-ring-inner"></span>
            <div class="fs-float">
              <img src="<?= Helpers::e($featureSystems[0]['img']) ?>" alt="<?= Helpers::e($featureSystems[0]['title']) ?>" data-fs-img>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 7. Final CTA -->
  <section class="cta">
    <div class="eyebrow">Your adventure awaits</div>
    <h2>Ready to taste the <span class="hl">sweet life</span>?</h2>
    <p>Download the client, create your account and join hundreds of adventurers already exploring the world of <?= Helpers::e(SITE_NAME) ?>.</p>
    <div class="cta-row">
      <a href="<?= Helpers::e(Helpers::baseUrl('downloads')) ?>" class="btn-honey btn-honey-lg">Download ↓</a>
      <a href="<?= Helpers::e(Helpers::baseUrl('register')) ?>" class="btn btn-ghost btn-lg on-dark">Register Now</a>
    </div>
  </section>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
