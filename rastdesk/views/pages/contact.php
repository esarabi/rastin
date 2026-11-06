<?php
$c = (array) t('contact');
$eyebrow = $c['eyebrow'];
$heading = $c['heading'];
$sub = $c['sub'];
$actions = '';
require ROOT . '/views/partials/page_head.php';
$phone = setting('phone', '021-88100050');
$mobile = setting('mobile', '0912 601 8213');
$email = setting('email', 'info@rastdesk.ir');
$tel = fn($n) => 'tel:' . preg_replace('/[^\d+]/', '', $n);
$mapUrl = setting('map_url', 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode('Tehran Africa Blvd Dastgerdi 269'));
?>
<section class="section">
  <div class="wrap form-layout">
    <aside class="contact-info">
      <div class="info-item"><span class="ico-badge"><?= icon('mobile') ?></span><div><small><?= e(t('common.mobile')) ?></small><a href="<?= e($tel($mobile)) ?>" dir="ltr"><?= e(digits($mobile, $lang)) ?></a></div></div>
      <div class="info-item"><span class="ico-badge"><?= icon('phone') ?></span><div><small><?= e(t('common.phone')) ?></small><a href="<?= e($tel($phone)) ?>" dir="ltr"><?= e(digits($phone, $lang)) ?></a></div></div>
      <div class="info-item"><span class="ico-badge"><?= icon('mail') ?></span><div><small><?= e(t('common.email')) ?></small><a href="mailto:<?= e($email) ?>" dir="ltr"><?= e($email) ?></a></div></div>
      <div class="info-item"><span class="ico-badge"><?= icon('pin') ?></span><div><small><?= e(t('common.address')) ?></small><span><?= e(site_text('address', 'defaults.address')) ?></span><a class="link-arrow" href="<?= e($mapUrl) ?>" target="_blank" rel="noopener">↗</a></div></div>
    </aside>
    <form class="form card" id="form" method="post" action="<?= e(url($lang, 'contact')) ?>#form" novalidate>
      <h2><?= e($c['form_title']) ?></h2>
      <?php $successText = $c['success']; require ROOT . '/views/partials/form_status.php'; ?>
      <?= csrf_field() ?>
      <div class="hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
      <div class="fields">
        <?php foreach ([['name', 'text', true, false], ['company', 'text', false, false], ['phone', 'tel', true, false], ['email', 'email', false, false], ['message', 'textarea', true, true]] as [$f, $type, $required, $full]) { require ROOT . '/views/partials/field.php'; } ?>
      </div>
      <div class="form-foot">
        <button class="btn btn-primary btn-lg" type="submit"><?= e(t('form.send')) ?></button>
        <small><?= e(t('form.privacy')) ?></small>
      </div>
    </form>
  </div>
</section>
