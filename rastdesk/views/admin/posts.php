<div class="page-title"><h1>وبلاگ</h1><a class="btn primary" href="<?= e(admin_url('posts/new')) ?><?= $filter ? '?lang=' . e($filter) : '' ?>">+ مطلب جدید</a></div>
<div class="filters">
  <a href="<?= e(admin_url('posts')) ?>"<?= $filter === '' ? ' aria-current="true"' : '' ?>>همه</a>
  <?php foreach (languages() as $l): ?><a href="<?= e(admin_url('posts')) ?>?lang=<?= e($l) ?>"<?= $filter === $l ? ' aria-current="true"' : '' ?>><?= e(lang_label($l)) ?></a><?php endforeach; ?>
</div>
<p class="muted">هر زبان وبلاگ جداگانه دارد. هر مطلب فقط در زبانی که انتخاب می‌کنید نمایش داده می‌شود.</p>
<div class="table-wrap">
<table class="table">
  <thead><tr><th>عنوان</th><th>زبان</th><th>وضعیت</th><th>تاریخ انتشار</th><th></th></tr></thead>
  <tbody>
  <?php if (!$posts): ?><tr><td colspan="5" class="muted">مطلبی پیدا نشد.</td></tr><?php endif; ?>
  <?php foreach ($posts as $p): ?>
    <tr>
      <td><a href="<?= e(admin_url('posts/' . $p['id'])) ?>"><b><?= e($p['title']) ?></b></a></td>
      <td><?= e(lang_label($p['lang'])) ?></td>
      <td><span class="tag <?= e($p['status']) ?>"><?= $p['status'] === 'published' ? (strtotime((string) $p['published_at']) > time() ? 'زمان‌بندی‌شده' : 'منتشرشده') : 'پیش‌نویس' ?></span></td>
      <td><?= e(admin_dt($p['published_at'])) ?></td>
      <td class="row-actions">
        <?php if ($p['status'] === 'published'): ?><a href="<?= e(url($p['lang'], 'blog/' . rawurlencode($p['slug']))) ?>" target="_blank" rel="noopener">مشاهده ↗</a><?php endif; ?>
        <a href="<?= e(admin_url('posts/' . $p['id'])) ?>">ویرایش</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</div>
