<?php
$dm = (array) t('demo');
$eyebrow = $dm['eyebrow'];
$heading = $dm['heading'];
$sub = $dm['sub'];
$actions = '';
require ROOT . '/views/partials/page_head.php';
$mobile = setting('mobile', '0912 601 8213');
$modOptions = array_map(fn($m) => $m['title'], modules_data()['modules']);
?>
<section class="section">
  <div class="wrap form-layout">
    <aside class="form-aside">
      <ul class="check-list big"><?php foreach ($dm['points'] as $pt): ?><li><?= icon('check') ?><span><?= e($pt) ?></span></li><?php endforeach; ?></ul>
      <div class="call-card">
        <small><?= e(t('cta.call')) ?></small>
        <a href="tel:<?= e(preg_replace('/[^\d+]/', '', $mobile)) ?>" dir="ltr"><?= e(digits($mobile, $lang)) ?></a>
      </div>
      <p class="micro"><?= icon('check') ?><?= e(t('cta.microcopy')) ?></p>
    </aside>
    <form class="form card" id="form" method="post" action="<?= e(url($lang, 'demo')) ?>#form" novalidate>
      <h2><?= e($dm['title']) ?></h2>
      <?php $successText = $dm['success']; require ROOT . '/views/partials/form_status.php'; ?>
      <?= csrf_field() ?>
      <div class="hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
      <div class="fields">
        <?php
        foreach ([
            ['name', 'text', true, false, []], ['company', 'text', true, false, []],
            ['phone', 'tel', true, false, []], ['email', 'email', false, false, []],
            ['position', 'text', false, false, []], ['users', 'select', false, false, $dm['users']],
            ['edition', 'select', false, false, $dm['editions']], ['deploy', 'select', false, false, $dm['deploy']],
            ['module', 'select', false, true, $modOptions], ['message', 'textarea', false, true, []],
        ] as [$f, $type, $required, $full, $options]) {
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
