<?php /** Blog card. Expects $p (post row), $lang */ ?>
<article class="post-card">
  <a class="post-cover" href="<?= e(url($lang, 'blog/' . rawurlencode($p['slug']))) ?>" tabindex="-1" aria-hidden="true">
    <?php if ($p['cover']): ?>
      <img src="<?= e(media_url($p['cover'])) ?>" alt="" loading="lazy">
    <?php else: ?>
      <span class="cover-fallback"><?= brand_mark() ?></span>
    <?php endif; ?>
  </a>
  <div class="post-body">
    <time datetime="<?= e(substr((string) $p['published_at'], 0, 10)) ?>"><?= e(format_date($p['published_at'], $lang)) ?></time>
    <h3><a href="<?= e(url($lang, 'blog/' . rawurlencode($p['slug']))) ?>"><?= e($p['title']) ?></a></h3>
    <p><?= e($p['excerpt'] ?: excerpt_from_html((string) $p['body'], 140)) ?></p>
  </div>
</article>
