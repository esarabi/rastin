<?php
$pr = (array) t('pricing');
$eyebrow = $pr['eyebrow'];
$heading = $pr['heading'];
$sub = $pr['sub'];
$actions = '';
require ROOT . '/views/partials/page_head.php';
$interest = ['rastdesk_cloud', 'rastdesk_onprem', 'custom'];
?>
<section class="section">
  <div class="wrap">
    <div class="plans">
      <?php foreach ($pr['plans'] as $i => $pl): ?>
        <div class="plan<?= $pl['featured'] ? ' featured' : '' ?>">
          <?php if ($pl['featured']): ?><span class="plan-flag"><?= e($pr['featured_label']) ?></span><?php endif; ?>
          <span class="ico-badge"><?= icon(['cloud', 'server', 'code'][$i] ?? 'check') ?></span>
          <h2><?= e($pl['name']) ?></h2>
          <p class="plan-lead"><?= e($pl['lead']) ?></p>
          <ul class="check-list">
            <?php foreach ($pl['points'] as $pt): ?><li><?= icon('check') ?><span><?= e($pt) ?></span></li><?php endforeach; ?>
          </ul>
          <a class="btn <?= $pl['featured'] ? 'btn-primary' : 'btn-outline' ?> btn-block" href="<?= e(url($lang, 'demo')) ?>?interest=<?= e($interest[$i] ?? 'other') ?>#form"><?= e(t('common.' . $pl['cta'])) ?></a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section tint">
  <div class="wrap narrow">
    <div class="sec-head"><h2><?= e($pr['faq_title']) ?></h2></div>
    <div class="faq">
      <?php foreach ($pr['faq'] as $i => $f): ?>
        <details<?= $i === 0 ? ' open' : '' ?>><summary><?= e($f['q']) ?><?= icon('plus', 'ico faq-ico') ?></summary><p><?= e($f['a']) ?></p></details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php $ctaTitle = t('home.cta_title'); $ctaText = t('home.cta_text'); require ROOT . '/views/partials/cta.php'; ?>
