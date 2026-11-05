<?php
$r = (array) t('rastdesk');
$rastdeskUrl = setting('rastdesk_url', 'https://rastdesk.ir');
$eyebrow = $r['eyebrow'];
$heading = $r['heading'];
$sub = $r['sub'];
$actions = '<div class="actions"><a class="btn btn-primary btn-lg" href="' . e(url($lang, 'demo')) . '?interest=rastdesk_cloud#form">' . e(t('common.request_demo')) . '</a>'
    . '<a class="btn btn-outline btn-lg" href="' . e(url($lang, 'pricing')) . '">' . e(t('nav.pricing')) . '</a></div>'
    . '<ul class="head-stats">';
foreach ($r['stats'] as $st) {
    $actions .= '<li><b>' . e($st['value']) . '</b><span>' . e($st['label']) . '</span></li>';
}
$actions .= '</ul>';
require ROOT . '/views/partials/page_head.php';
?>
<section class="section">
  <div class="wrap">
    <div class="sec-head"><h2><?= e($r['why_title']) ?></h2></div>
    <div class="cards cards-4">
      <?php foreach ($r['why'] as $w): ?>
        <div class="card"><span class="ico-badge"><?= icon($w['icon']) ?></span><h3><?= e($w['title']) ?></h3><p><?= e($w['text']) ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section tint" id="modules">
  <div class="wrap">
    <div class="sec-head">
      <h2><?= e($r['modules_title']) ?></h2>
      <p class="lead"><?= e($r['modules_sub']) ?></p>
    </div>
    <div class="mod-groups">
      <?php foreach ($r['groups'] as $g): ?>
        <div class="mod-card">
          <h3><span class="ico-badge"><?= icon($g['icon']) ?></span><?= e($g['title']) ?></h3>
          <ul><?php foreach ($g['items'] as $it): ?><li><?= icon('check') ?><span><?= e($it) ?></span></li><?php endforeach; ?></ul>
        </div>
      <?php endforeach; ?>
    </div>
    <p class="note-line"><?= e($r['note']) ?> <a href="<?= e($rastdeskUrl) ?>" target="_blank" rel="noopener" dir="ltr"><?= e(preg_replace('#^https?://#', '', $rastdeskUrl)) ?> ↗</a></p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="sec-head">
      <h2><?= e($r['onboard_title']) ?></h2>
      <p class="lead"><?= e($r['onboard_sub']) ?></p>
    </div>
    <ol class="steps steps-3">
      <?php foreach ($r['onboard'] as $i => $st): ?>
        <li><span class="step-n"><?= e(digits((string) ($i + 1), $lang)) ?></span><h3><?= e($st['title']) ?></h3><p><?= e($st['text']) ?></p></li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>

<section class="section tint">
  <div class="wrap">
    <div class="sec-head"><h2><?= e($r['deploy_title']) ?></h2></div>
    <div class="cards cards-2">
      <?php foreach ((array) t('home.deploy') as $i => $dp): ?>
        <div class="card card-flat"><span class="ico-badge"><?= icon($i === 0 ? 'cloud' : 'server') ?></span><h3><?= e($dp['title']) ?></h3><p><?= e($dp['text']) ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="sec-head"><h2><?= e(t('solutions.industries_title')) ?></h2></div>
    <div class="cards cards-4">
      <?php foreach ((array) t('solutions.industries') as $ind): ?>
        <div class="card card-flat"><h3><?= e($ind['title']) ?></h3><p><?= e($ind['text']) ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php $ctaTitle = $r['cta_title']; $ctaText = $r['cta_text']; require ROOT . '/views/partials/cta.php'; ?>
