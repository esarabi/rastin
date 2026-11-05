<?php
$a = (array) t('about');
$eyebrow = $a['eyebrow'];
$heading = $a['heading'];
$sub = $a['sub'];
$actions = '';
require ROOT . '/views/partials/page_head.php';
?>
<section class="section">
  <div class="wrap">
    <div class="facts">
      <?php foreach ($a['facts'] as $f): ?><div><b><?= e($f['value']) ?></b><span><?= e($f['label']) ?></span></div><?php endforeach; ?>
    </div>
    <div class="split about-split">
      <div>
        <h2><?= e($a['story_title']) ?></h2>
        <div class="prose"><?php foreach ($a['story'] as $para): ?><p><?= e($para) ?></p><?php endforeach; ?></div>
      </div>
      <aside class="mission">
        <?= brand_mark('mark mission-mark') ?>
        <h2><?= e($a['mission_title']) ?></h2>
        <p><?= e($a['mission']) ?></p>
      </aside>
    </div>
  </div>
</section>

<section class="section tint">
  <div class="wrap">
    <div class="sec-head"><h2><?= e($a['values_title']) ?></h2></div>
    <div class="cards cards-4">
      <?php foreach ($a['values'] as $v): ?>
        <div class="card card-flat"><h3><?= e($v['title']) ?></h3><p><?= e($v['text']) ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="sec-head"><h2><?= e(t('home.industries_title')) ?></h2></div>
    <ul class="chips"><?php foreach ((array) t('home.industries') as $ind): ?><li><?= e($ind) ?></li><?php endforeach; ?></ul>
  </div>
</section>

<?php $ctaTitle = t('home.cta_title'); $ctaText = t('home.cta_text'); require ROOT . '/views/partials/cta.php'; ?>
