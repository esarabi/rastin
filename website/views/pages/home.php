<?php
$h = (array) t('home');
$d = fn($s) => digits((string) $s, $lang);
?>
<section class="hero">
  <div class="wrap hero-grid">
    <div class="hero-copy">
      <p class="eyebrow"><?= e($h['hero_eyebrow']) ?></p>
      <h1><?= e(site_text('hero_title', 'home.hero_title')) ?></h1>
      <p class="lead"><?= e(site_text('hero_sub', 'home.hero_sub')) ?></p>
      <div class="actions">
        <a class="btn btn-primary btn-lg" href="<?= e(url($lang, 'demo')) ?>"><?= e(t('common.request_demo')) ?></a>
        <a class="btn btn-outline btn-lg" href="<?= e(url($lang, 'rastdesk')) ?>"><?= e(t('nav.rastdesk')) ?> <?= icon('arrow', 'ico flip') ?></a>
      </div>
      <ul class="ticks">
        <?php foreach ($h['hero_points'] as $pt): ?><li><?= icon('check') ?><?= e($pt) ?></li><?php endforeach; ?>
      </ul>
    </div>
    <div class="hero-art" aria-hidden="true">
      <canvas class="net"></canvas>
      <div class="mock">
        <div class="mock-head"><span><?= e($h['mock_title']) ?></span><em><?= e($h['mock_note']) ?></em></div>
        <div class="mock-stats">
          <div><b><?= e($d('1,284')) ?></b><small><?= e($h['mock_calls']) ?></small></div>
          <div><b><?= e($d('87%')) ?></b><small><?= e($h['mock_satisfaction']) ?></small></div>
        </div>
        <div class="mock-wave"><?php for ($i = 0; $i < 36; $i++): $v = 18 + (int) (abs(sin($i * 0.7) * cos($i * 0.23)) * 62); ?><i style="height:<?= $v ?>%"></i><?php endfor; ?></div>
        <div class="mock-topics">
          <small><?= e($h['mock_topics']) ?></small>
          <?php foreach ($h['mock_topic_list'] as $k => $tp): ?>
            <div class="bar"><span><?= e($tp) ?></span><i style="--w:<?= [78, 54, 33][$k] ?? 30 ?>%"></i></div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="industries">
  <div class="wrap">
    <p><?= e($h['industries_title']) ?></p>
    <ul><?php foreach ($h['industries'] as $ind): ?><li><?= e($ind) ?></li><?php endforeach; ?></ul>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="sec-head">
      <p class="eyebrow"><?= e($h['services_eyebrow']) ?></p>
      <h2><?= e($h['services_title']) ?></h2>
    </div>
    <div class="cards cards-4">
      <?php foreach ($h['services'] as $s): ?>
        <a class="card card-link" href="<?= e(url($lang, $s['link'])) ?>">
          <span class="ico-badge"><?= icon($s['icon']) ?></span>
          <h3><?= e($s['title']) ?></h3>
          <p><?= e($s['text']) ?></p>
          <span class="more"><?= e(t('common.learn_more')) ?> <?= icon('arrow', 'ico flip') ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section dark">
  <canvas class="net" aria-hidden="true"></canvas>
  <div class="wrap split">
    <div>
      <p class="eyebrow"><?= e($h['rastdesk_eyebrow']) ?></p>
      <h2><?= e($h['rastdesk_title']) ?></h2>
      <p class="lead"><?= e($h['rastdesk_text']) ?></p>
      <div class="actions">
        <a class="btn btn-primary" href="<?= e(url($lang, 'rastdesk')) ?>"><?= e(t('common.learn_more')) ?></a>
        <a class="btn btn-ghost-light" href="<?= e(setting('rastdesk_url', 'https://rastdesk.ir')) ?>" target="_blank" rel="noopener"><?= e(t('common.visit_rastdesk')) ?> ↗</a>
      </div>
    </div>
    <ul class="feature-list">
      <?php foreach ($h['rastdesk_points'] as $i => $pt): ?>
        <li><span class="ico-badge"><?= icon(['sales', 'ticket', 'phone', 'chart'][$i] ?? 'check') ?></span><?= e($pt) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<section class="section" id="speech">
  <div class="wrap">
    <div class="sec-head">
      <p class="eyebrow"><?= e($h['speech_eyebrow']) ?></p>
      <h2><?= e($h['speech_title']) ?></h2>
      <p class="lead"><?= e($h['speech_text']) ?></p>
    </div>
    <ol class="steps">
      <?php foreach ($h['speech_steps'] as $i => $st): ?>
        <li><span class="step-n"><?= e($d($i + 1)) ?></span><h3><?= e($st['title']) ?></h3><p><?= e($st['text']) ?></p></li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>

<section class="section tint">
  <div class="wrap">
    <div class="sec-head">
      <p class="eyebrow"><?= e($h['deploy_eyebrow']) ?></p>
      <h2><?= e($h['deploy_title']) ?></h2>
    </div>
    <div class="cards cards-2">
      <?php foreach ($h['deploy'] as $i => $dp): ?>
        <div class="card card-flat">
          <span class="ico-badge"><?= icon($i === 0 ? 'cloud' : 'server') ?></span>
          <h3><?= e($dp['title']) ?></h3>
          <p><?= e($dp['text']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="sec-head">
      <p class="eyebrow"><?= e($h['why_eyebrow']) ?></p>
      <h2><?= e($h['why_title']) ?></h2>
    </div>
    <div class="why">
      <?php foreach ($h['why'] as $i => $w): ?>
        <div class="why-item"><?= icon(['shield', 'lang', 'puzzle', 'map'][$i] ?? 'check', 'ico why-ico') ?><h3><?= e($w['title']) ?></h3><p><?= e($w['text']) ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php if (!empty($posts)): ?>
<section class="section tint">
  <div class="wrap">
    <div class="sec-head row">
      <h2><?= e($h['blog_title']) ?></h2>
      <a class="link-arrow" href="<?= e(url($lang, 'blog')) ?>"><?= e(t('common.all_posts')) ?> <?= icon('arrow', 'ico flip') ?></a>
    </div>
    <div class="posts"><?php foreach ($posts as $p) require ROOT . '/views/partials/post_card.php'; ?></div>
  </div>
</section>
<?php endif; ?>

<?php $ctaTitle = $h['cta_title']; $ctaText = $h['cta_text']; require ROOT . '/views/partials/cta.php'; ?>
