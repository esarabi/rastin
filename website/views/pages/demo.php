<?php
$dm = (array) t('demo');
$eyebrow = $dm['eyebrow'];
$heading = $dm['heading'];
$sub = $dm['sub'];
$actions = '';
require ROOT . '/views/partials/page_head.php';
?>
<section class="section">
  <div class="wrap form-layout">
    <aside class="demo-aside">
      <ul class="check-list">
        <?php foreach ($dm['points'] as $pt): ?><li><?= icon('check') ?><span><?= e($pt) ?></span></li><?php endforeach; ?>
      </ul>
      <div class="demo-call">
        <small><?= e(t('common.phone')) ?></small>
        <?php $phone = setting('phone', '021-88100050'); ?>
        <a href="tel:<?= e(preg_replace('/[^\d+]/', '', $phone)) ?>" dir="ltr"><?= e(digits($phone, $lang)) ?></a>
      </div>
    </aside>
    <form class="form card" id="form" method="post" action="<?= e(url($lang, 'demo')) ?>#form" novalidate>
      <h2><?= e($dm['title']) ?></h2>
      <?php $successText = $dm['success']; require ROOT . '/views/partials/form_status.php'; ?>
      <?= csrf_field() ?>
      <div class="hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
      <div class="fields">
        <?php
        $fields = [
            ['name', 'text', true, false, []],
            ['company', 'text', true, false, []],
            ['position', 'text', false, false, []],
            ['phone', 'tel', true, false, []],
            ['email', 'email', false, false, []],
            ['team_size', 'select', false, false, $dm['sizes']],
            ['interest', 'select', true, true, $dm['interests']],
            ['message', 'textarea', false, true, []],
        ];
        foreach ($fields as [$f, $type, $required, $full, $options]) {
            require ROOT . '/views/partials/field.php';
        }
        ?>
      </div>
      <div class="form-foot">
        <button class="btn btn-primary btn-lg" type="submit"><?= e(t('form.send_demo')) ?></button>
        <small><?= e(t('form.privacy')) ?></small>
      </div>
    </form>
  </div>
</section>
