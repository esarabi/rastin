<?php
$b = (array) t('blog');
$eyebrow = $b['eyebrow'];
$heading = $b['heading'];
$sub = $b['sub'];
$actions = '';
require ROOT . '/views/partials/page_head.php';
?>
<section class="section">
  <div class="wrap">
    <?php if (!$posts): ?>
      <div class="empty"><?= brand_mark() ?><p><?= e(t('common.no_posts')) ?></p></div>
    <?php else: ?>
      <div class="posts"><?php foreach ($posts as $p) require ROOT . '/views/partials/post_card.php'; ?></div>
      <?php if ($pages > 1): ?>
        <nav class="pager" aria-label="pagination">
          <?php if ($pageNum > 1): ?><a class="btn btn-outline" href="<?= e(url($lang, 'blog')) ?>?page=<?= $pageNum - 1 ?>"><?= e(t('common.prev')) ?></a><?php endif; ?>
          <span><?= e(digits($pageNum . ' / ' . $pages, $lang)) ?></span>
          <?php if ($pageNum < $pages): ?><a class="btn btn-outline" href="<?= e(url($lang, 'blog')) ?>?page=<?= $pageNum + 1 ?>"><?= e(t('common.next')) ?></a><?php endif; ?>
        </nav>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</section>
