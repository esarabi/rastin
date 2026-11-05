<?php
$r = (array) t('rastdesk');
$rastdeskUrl = setting('rastdesk_url', 'https://rastdesk.ir');
$eyebrow = $r['eyebrow'];
$heading = $r['heading'];
$sub = $r['sub'];
$actions = '<div class="actions"><a class="btn btn-primary btn-lg" href="' . e(url($lang, 'demo')) . '">' . e(t('common.request_demo')) . '</a>'
    . '<a class="btn btn-outline btn-lg" href="' . e($rastdeskUrl) . '" target="_blank" rel="noopener">' . e(t('common.visit_rastdesk')) . ' ↗</a></div>';
require ROOT . '/views/partials/page_head.php';
?>
<section class="section">
  <div class="wrap">
    <div class="sec-head"><h2><?= e($r['modules_title']) ?></h2></div>
    <div class="cards cards-3">
      <?php foreach ($r['modules'] as $m): ?>
        <div class="card"><span class="ico-badge"><?= icon($m['icon']) ?></span><h3><?= e($m['title']) ?></h3><p><?= e($m['text']) ?></p></div>
      <?php endforeach; ?>
    </div>
    <p class="note-line"><?= e($r['note']) ?> <a href="<?= e($rastdeskUrl) ?>" target="_blank" rel="noopener" dir="ltr"><?= e(preg_replace('#^https?://#', '', $rastdeskUrl)) ?> ↗</a></p>
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
