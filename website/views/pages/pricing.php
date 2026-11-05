<?php
$pr = (array) t('pricing');
$eyebrow = $pr['eyebrow'];
$heading = $pr['heading'];
$sub = $pr['sub'];
$actions = '';
require ROOT . '/views/partials/page_head.php';
$rastdeskUrl = setting('rastdesk_url', 'https://rastdesk.ir');
?>
<section class="section">
  <div class="wrap">
    <div class="plans plans-4">
      <?php foreach ($pr['editions'] as $ed):
          $price = trim(setting_raw('price_' . $ed['key'], (string) (PRICE_DEFAULTS[$ed['key']] ?? '')));
      ?>
        <div class="plan<?= $ed['featured'] ? ' featured' : '' ?>">
          <?php if ($ed['featured']): ?><span class="plan-flag"><?= e($pr['featured_label']) ?></span><?php endif; ?>
          <h2><?= e($ed['name']) ?></h2>
          <p class="plan-lead"><?= e($ed['lead']) ?></p>
          <div class="price">
            <?php if ($price !== ''): ?>
              <b><?= e(digits($price, $lang)) ?></b><span><?= e($pr['price_unit']) ?></span>
            <?php else: ?>
              <b class="ask"><?= e(t('common.get_price')) ?></b>
            <?php endif; ?>
            <small><?= e($pr['license']) ?></small>
          </div>
          <ul class="check-list">
            <?php foreach ($ed['points'] as $pt): ?><li><?= icon('check') ?><span><?= e($pt) ?></span></li><?php endforeach; ?>
          </ul>
          <a class="btn <?= $ed['featured'] ? 'btn-primary' : 'btn-outline' ?> btn-block" href="<?= e(url($lang, 'demo')) ?>?interest=rastdesk_cloud#form"><?= e(t('common.request_demo')) ?></a>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="price-notes">
      <p><?= icon('check') ?><span><?= e($pr['included']) ?></span></p>
      <p><?= icon('plus') ?><span><?= e($pr['addon_note']) ?> <a href="<?= e(url($lang, 'contact')) ?>"><?= e(t('common.contact_us')) ?></a></span></p>
      <p><a class="link-arrow" href="<?= e($rastdeskUrl) ?>" target="_blank" rel="noopener"><?= e($pr['details_link']) ?> ↗</a></p>
    </div>
  </div>
</section>

<section class="section tint">
  <div class="wrap">
    <div class="custom-band">
      <span class="ico-badge lg"><?= icon('code') ?></span>
      <div>
        <h2><?= e($pr['custom_title']) ?></h2>
        <p><?= e($pr['custom_text']) ?></p>
      </div>
      <a class="btn btn-primary btn-lg" href="<?= e(url($lang, 'demo')) ?>?interest=custom#form"><?= e(t('common.request_consult')) ?></a>
    </div>
  </div>
</section>

<section class="section">
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
