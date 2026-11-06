<?php
$pr = (array) t('pricing');
$data = modules_data();
$eyebrow = $pr['eyebrow'];
$heading = $pr['heading'];
$sub = $pr['sub'];
$actions = '';
require ROOT . '/views/partials/page_head.php';
?>
<section class="section">
  <div class="wrap">
    <div class="plans">
      <?php foreach (EDITIONS as $ed) require ROOT . '/views/partials/edition_card.php'; ?>
    </div>
    <div class="price-extras">
      <div class="card">
        <h3><?= e($pr['extra_user']) ?></h3>
        <p class="big-num"><?= e(money(setting_raw('extra_user_price', EXTRA_USER_DEFAULT), $lang)) ?> <small><?= e(t('common.toman')) ?></small></p>
        <p class="muted"><?= e($pr['extra_user_text']) ?></p>
      </div>
      <div class="card">
        <h3><?= e($pr['included_title']) ?></h3>
        <ul class="check-list"><?php foreach ($pr['included'] as $x): ?><li><?= icon('check') ?><span><?= e($x) ?></span></li><?php endforeach; ?></ul>
      </div>
    </div>
    <p class="muted small center-text"><?= e($pr['valid_from']) ?></p>
  </div>
</section>

<section class="section alt" id="compare">
  <div class="wrap">
    <div class="sec-head"><h2><?= e($pr['compare_title']) ?></h2></div>
    <div class="table-wrap">
      <table class="compare">
        <thead>
          <tr><th scope="col"><?= e($pr['module']) ?></th><?php foreach (EDITIONS as $ed): ?><th scope="col"><?= e(t('editions.' . $ed)) ?><small><?= e(is_numeric(edition_price($ed)) ? money(edition_price($ed), $lang) . ' ' . t('common.million_toman') : '') ?></small></th><?php endforeach; ?></tr>
        </thead>
        <?php foreach ($data['groups'] as $gid => $g): ?>
          <tbody>
            <tr class="grp"><th colspan="5" scope="colgroup"><?= e($g['title']) ?></th></tr>
            <?php foreach (array_filter($data['modules'], fn($m) => $m['group'] === $gid) as $slug => $m): $in = editions_from($m['edition']); ?>
              <tr>
                <th scope="row"><a href="<?= e(url($lang, 'modules/' . $slug)) ?>"><?= e($m['title']) ?></a></th>
                <?php foreach (EDITIONS as $ed): ?><td><?= in_array($ed, $in, true) ? '<span class="yes" aria-label="✓">' . icon('check') . '</span>' : '<span class="no" aria-label="—">—</span>' ?></td><?php endforeach; ?>
              </tr>
            <?php endforeach; ?>
          </tbody>
        <?php endforeach; ?>
        <tfoot><tr><th><?= e($pr['users_cap']) ?></th><?php foreach (EDITIONS as $ed): ?><td><b><?= e(digits(edition_users($ed), $lang)) ?></b></td><?php endforeach; ?></tr></tfoot>
      </table>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap custom-band">
    <div>
      <h2><?= e($pr['custom_title']) ?></h2>
      <p class="lead"><?= e($pr['custom_text']) ?></p>
    </div>
    <ol class="mini-steps"><?php foreach ($pr['custom_steps'] as $i => $st): ?><li><span><?= e(digits((string) ($i + 1), $lang)) ?></span><?= e($st) ?></li><?php endforeach; ?></ol>
    <a class="btn btn-primary btn-lg" href="<?= e(url($lang, 'contact')) ?>"><?= e(t('cta.call')) ?></a>
  </div>
</section>

<section class="section alt">
  <div class="wrap narrow">
    <div class="sec-head center"><h2><?= e(t('home.faq_title')) ?></h2></div>
    <div class="faq">
      <?php foreach ((array) t('faq') as $i => $f): ?><details<?= $i === 0 ? ' open' : '' ?>><summary><?= e($f['q']) ?><?= icon('plus', 'ico faq-ico') ?></summary><p><?= e($f['a']) ?></p></details><?php endforeach; ?>
    </div>
  </div>
</section>
<?php require ROOT . '/views/partials/cta_band.php'; ?>
