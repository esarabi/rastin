<article class="article">
  <header class="article-head">
    <div class="wrap narrow">
      <a class="link-arrow back" href="<?= e(url($lang, 'blog')) ?>"><?= icon('arrow', 'ico back-ico') ?> <?= e(t('common.back_to_blog')) ?></a>
      <h1><?= e($post['title']) ?></h1>
      <p class="meta"><?= e(t('common.published')) ?> <time datetime="<?= e(substr((string) $post['published_at'], 0, 10)) ?>"><?= e(format_date($post['published_at'], $lang)) ?></time></p>
    </div>
  </header>
  <?php if ($post['cover']): ?>
    <div class="wrap narrow"><img class="article-cover" src="<?= e(media_url($post['cover'])) ?>" alt=""></div>
  <?php endif; ?>
  <div class="wrap narrow prose article-body">
    <?= $post['body'] /* sanitized when saved in the admin panel */ ?>
  </div>
</article>
<?php if ($more): ?>
<section class="section tint">
  <div class="wrap">
    <div class="sec-head row"><h2><?= e(t('blog.title')) ?></h2><a class="link-arrow" href="<?= e(url($lang, 'blog')) ?>"><?= e(t('common.all_posts')) ?> <?= icon('arrow', 'ico flip') ?></a></div>
    <div class="posts"><?php foreach (array_slice($more, 0, 3) as $p) require ROOT . '/views/partials/post_card.php'; ?></div>
  </div>
</section>
<?php endif; ?>
<?php require ROOT . '/views/partials/cta_band.php'; ?>
