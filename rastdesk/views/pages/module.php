<?php
$mp = (array) t('modules_page');
$data = modules_data();
$mods = $data['modules'];
$keys = array_keys($mods);
$pos = array_search($slug, $keys, true);
$prevSlug = $keys[$pos - 1] ?? null;
$nextSlug = $keys[$pos + 1] ?? null;
$related = array_filter($mods, fn($x, $k) => $x['group'] === $m['group'] && $k !== $slug, ARRAY_FILTER_USE_BOTH);
$group = $data['groups'][$m['group']];
$in = editions_from($m['edition']);
?>
<section class="page-head module-head">
  <canvas class="net" aria-hidden="true"></canvas>
  <div class="wrap page-head-inner">
    <nav class="crumbs" aria-label="breadcrumb">
      <a href="<?= e(url($lang)) ?>"><?= e(t('nav.home')) ?></a><span>/</span>
      <a href="<?= e(url($lang, 'modules')) ?>"><?= e(t('nav.modules')) ?></a><span>/</span>
      <a href="<?= e(url($lang, 'modules#' . $m['group'])) ?>"><?= e($group['title']) ?></a>
    </nav>
    <div class="module-title">
      <span class="ico-badge lg"><?= icon($m['icon']) ?></span>
      <div>
        <p class="eyebrow"><?= e($group['title']) ?> · <?= e(digits(sprintf('%02d', $m['num']), $lang)) ?> / <?= e(digits((string) count($mods), $lang)) ?></p>
        <h1><?= e($m['title']) ?></h1>
      </div>
    </div>
    <p class="tagline"><?= e($m['tagline']) ?></p>
    <p class="lead"><?= e($m['desc']) ?></p>
    <div class="actions">
      <a class="btn btn-primary btn-lg" href="<?= e(url($lang, 'demo')) ?>?module=<?= e($slug) ?>&edition=<?= e($m['edition']) ?>#form"><?= e(t('cta.demo')) ?></a>
      <a class="btn btn-outline btn-lg" href="<?= e(url($lang, 'pricing')) ?>"><?= e(t('cta.pricing')) ?></a>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap module-body">
    <div>
      <h2><?= e($mp['key_features']) ?></h2>
      <ol class="feature-list">
        <?php foreach ($m['features'] as $i => $f): ?><li><span class="n"><?= e(digits(sprintf('%02d', $i + 1), $lang)) ?></span><span><?= e($f) ?></span></li><?php endforeach; ?>
      </ol>
    </div>
    <aside class="module-side">
      <div class="card">
        <h3><?= e($mp['audience']) ?></h3>
        <p><?= e($m['audience']) ?></p>
      </div>
      <div class="card">
        <h3><?= e($mp['availability']) ?></h3>
        <div class="ed-row">
          <?php foreach (EDITIONS as $ed): ?><span class="ed-pill<?= in_array($ed, $in, true) ? ' on' : '' ?>"><?= in_array($ed, $in, true) ? icon('check') : '' ?><?= e(t('editions.' . $ed)) ?></span><?php endforeach; ?>
        </div>
        <p class="muted small"><?= e(t('common.from_edition')) ?> <?= e(t('editions.' . $m['edition'])) ?> · <?= e(sprintf(t('common.up_to_users'), digits(edition_users($m['edition']), $lang))) ?></p>
      </div>
      <div class="card accent">
        <h3><?= e($mp['box_title']) ?></h3>
        <p><?= e($mp['box_text']) ?></p>
        <a class="btn btn-ink btn-block" href="<?= e(url($lang, 'demo')) ?>?module=<?= e($slug) ?>&edition=<?= e($m['edition']) ?>#form"><?= e(t('cta.demo')) ?></a>
        <a class="link-arrow" href="<?= e(url($lang, 'catalog')) ?>"><?= e(t('cta.catalog')) ?></a>
      </div>
    </aside>
  </div>
</section>

<?php if ($related): ?>
<section class="section alt">
  <div class="wrap">
    <div class="sec-head"><h2><?= e($mp['related']) ?></h2></div>
    <div class="mod-grid"><?php foreach ($related as $slug2 => $m2) { $s0 = $slug; $m0 = $m; $slug = $slug2; $m = $m2; require ROOT . '/views/partials/mod_chip.php'; $slug = $s0; $m = $m0; } ?></div>
    <nav class="prev-next">
      <?php if ($prevSlug): ?><a href="<?= e(url($lang, 'modules/' . $prevSlug)) ?>"><?= icon('arrow', 'ico back-ico') ?><span><small><?= e(t('common.prev')) ?></small><?= e($mods[$prevSlug]['title']) ?></span></a><?php else: ?><span></span><?php endif; ?>
      <?php if ($nextSlug): ?><a class="next" href="<?= e(url($lang, 'modules/' . $nextSlug)) ?>"><span><small><?= e(t('common.next')) ?></small><?= e($mods[$nextSlug]['title']) ?></span><?= icon('arrow', 'ico flip') ?></a><?php endif; ?>
    </nav>
  </div>
</section>
<?php endif; ?>
<?php require ROOT . '/views/partials/cta_band.php'; ?>
