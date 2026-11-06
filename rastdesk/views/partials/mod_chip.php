<?php /** Module link chip. Expects $slug, $m, $lang */ ?>
<a class="mod-chip" href="<?= e(url($lang, 'modules/' . $slug)) ?>" data-edition="<?= e($m['edition']) ?>">
  <span class="mc-ico"><?= icon($m['icon']) ?></span>
  <span class="mc-txt"><b><?= e($m['title']) ?></b><small><?= e($m['tagline']) ?></small></span>
  <span class="ed-badge ed-<?= e($m['edition']) ?>"><?= e(t('editions.' . $m['edition'])) ?></span>
</a>
