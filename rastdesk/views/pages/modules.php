<?php
$mp = (array) t('modules_page');
$data = modules_data();
$eyebrow = $mp['eyebrow'];
$heading = $mp['heading'];
$sub = $mp['sub'];
$actions = '<div class="actions"><a class="btn btn-primary btn-lg" href="' . e(url($lang, 'demo')) . '">' . e(t('cta.demo')) . '</a><a class="btn btn-outline btn-lg" href="' . e(url($lang, 'catalog')) . '">' . e(t('cta.catalog')) . '</a></div>';
require ROOT . '/views/partials/page_head.php';
?>
<section class="section">
  <div class="wrap">
    <div class="filter-bar" role="group" aria-label="<?= e($mp['filter_label']) ?>">
      <span><?= e($mp['filter_label']) ?>:</span>
      <button type="button" data-filter="all" aria-pressed="true"><?= e($mp['filter_all']) ?></button>
      <?php foreach (EDITIONS as $ed): ?>
        <button type="button" data-filter="<?= e($ed) ?>" aria-pressed="false"><?= e(t('editions.' . $ed)) ?> <small><?= e(digits((string) count(modules_in_edition($ed)), $lang)) ?></small></button>
      <?php endforeach; ?>
      <nav class="group-jump"><?php foreach ($data['groups'] as $gid => $g): ?><a href="#<?= e($gid) ?>"><?= e($g['title']) ?></a><?php endforeach; ?></nav>
    </div>
    <?php foreach ($data['groups'] as $gid => $g): $gm = array_filter($data['modules'], fn($m) => $m['group'] === $gid); ?>
      <div class="mod-group" id="<?= e($gid) ?>">
        <h2><span class="ico-badge sm"><?= icon($g['icon']) ?></span><?= e($g['title']) ?> <small><?= e(sprintf(t('common.modules_count'), digits((string) count($gm), $lang))) ?></small></h2>
        <div class="mod-grid"><?php foreach ($gm as $slug => $m) require ROOT . '/views/partials/mod_chip.php'; ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<?php require ROOT . '/views/partials/cta_band.php'; ?>
