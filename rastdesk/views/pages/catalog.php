<?php
$cg = (array) t('catalog');
$gate = setting('catalog_gate', 'form') === 'form';
$dl = url($lang, 'catalog/download');
?>
<section class="page-head">
  <canvas class="net" aria-hidden="true"></canvas>
  <div class="wrap catalog-grid">
    <div>
      <p class="eyebrow"><?= e($cg['eyebrow']) ?></p>
      <h1><?= e($cg['heading']) ?></h1>
      <p class="lead"><?= e($cg['sub']) ?></p>
      <ul class="check-list big"><?php foreach ($cg['points'] as $pt): ?><li><?= icon('check') ?><span><?= e($pt) ?></span></li><?php endforeach; ?></ul>
      <p class="muted"><?= e($cg['size']) ?></p>
    </div>
    <div class="catalog-side">
      <div class="cover-mock" aria-hidden="true">
        <img src="<?= e(asset('assets/img/rastdesk-icon.svg')) ?>" alt="" width="70" height="70">
        <b><?= e(t('brand.product')) ?></b><span><?= e(sprintf(t('common.modules_count'), digits('37', $lang))) ?> · <?= e(digits('4', $lang)) ?> <?= e(t('nav.pricing')) ?></span>
      </div>
      <?php if (!$gate || $success): ?>
        <div class="form card" id="form">
          <?php if ($success): ?><div class="alert ok" role="status"><?= icon('check') ?><span><?= e($cg['success']) ?></span></div><?php endif; ?>
          <a class="btn btn-primary btn-lg btn-block" href="<?= e($dl) ?>" id="dl-link"><?= e($cg['download']) ?></a>
          <?php if ($success): ?><small class="muted"><?= e($cg['download_again']) ?></small><?php endif; ?>
          <a class="btn btn-outline btn-block" href="<?= e(url($lang, 'demo')) ?>"><?= e(t('cta.demo')) ?></a>
        </div>
        <?php if ($success): ?><script>setTimeout(function(){location.href=<?= json_encode($dl) ?>;},600);</script><?php endif; ?>
      <?php else: ?>
        <form class="form card" id="form" method="post" action="<?= e(url($lang, 'catalog')) ?>#form" novalidate>
          <h2><?= e($cg['form_title']) ?></h2>
          <?php if (!empty($errors['_form'])): ?><div class="alert err" role="alert"><span><?= e($errors['_form']) ?></span></div><?php endif; ?>
          <?= csrf_field() ?>
          <div class="hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
          <div class="fields one">
            <?php foreach ([['name', 'text', true, true], ['phone', 'tel', true, true], ['company', 'text', false, true], ['email', 'email', false, true]] as [$f, $type, $required, $full]) { require ROOT . '/views/partials/field.php'; } ?>
          </div>
          <button class="btn btn-primary btn-lg btn-block" type="submit"><?= e(t('form.send_catalog')) ?></button>
          <small class="muted"><?= e($cg['form_note']) ?> <?= e(t('form.privacy')) ?></small>
        </form>
      <?php endif; ?>
    </div>
  </div>
</section>
