<?php
/** One edition card. Expects $ed (basic|pro|plus|max), $lang, optional $compact */
$i = array_search($ed, EDITIONS, true);
$price = edition_price($ed);
$own = array_filter(modules_data()['modules'], fn($m) => $m['edition'] === $ed);
$featured = $ed === 'plus';
?>
<div class="plan<?= $featured ? ' featured' : '' ?>">
  <?php if ($featured): ?><span class="plan-flag"><?= e(t('pricing.featured')) ?></span><?php endif; ?>
  <div class="plan-head">
    <h3><?= e(t('editions.' . $ed)) ?></h3>
    <span class="users"><?= e(sprintf(t('common.up_to_users'), digits(edition_users($ed), $lang))) ?></span>
  </div>
  <p class="plan-lead"><?= e(t('edition_leads.' . $ed)) ?></p>
  <div class="price">
    <?php if (is_numeric($price)): ?>
      <b><?= e(money($price, $lang)) ?></b><span><?= e(t('common.million_toman')) ?></span>
    <?php else: ?>
      <b class="ask"><?= e(t('cta.call')) ?></b>
    <?php endif; ?>
    <small><?= e(t('common.perpetual')) ?></small>
  </div>
  <?php if ($i > 0): ?><p class="plus-prev"><?= e(sprintf(t('pricing.plus_prev'), t('editions.' . EDITIONS[$i - 1]))) ?></p><?php endif; ?>
  <ul class="check-list">
    <?php foreach ($own as $slug => $m): ?><li><?= icon('check') ?><a href="<?= e(url($lang, 'modules/' . $slug)) ?>"><?= e($m['title']) ?></a></li><?php endforeach; ?>
  </ul>
  <a class="btn <?= $featured ? 'btn-primary' : 'btn-outline' ?> btn-block" href="<?= e(url($lang, 'demo')) ?>?edition=<?= e($ed) ?>#form"><?= e(t('cta.demo')) ?></a>
</div>
