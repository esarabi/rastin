<div class="page-title"><h1>پیشخوان</h1><a class="btn primary" href="<?= e(admin_url('posts/new')) ?>">+ مطلب جدید</a></div>
<?php if ($endpointsMissing): ?>
  <div class="notice">نشانی مقصد یکی از فرم‌ها هنوز وارد نشده است. پیام‌ها در همین پنل ذخیره می‌شوند و بعد از وارد کردن نشانی در <a href="<?= e(admin_url('settings')) ?>">تنظیمات</a>، به آن نشانی هم ارسال می‌شوند.</div>
<?php endif; ?>
<div class="stats">
  <a href="<?= e(admin_url('messages')) ?>"><b><?= e(digits((string) $stats['unread'], 'fa')) ?></b><span>پیام خوانده‌نشده</span></a>
  <a href="<?= e(admin_url('messages')) ?>"><b><?= e(digits((string) $stats['messages'], 'fa')) ?></b><span>کل پیام‌ها و درخواست‌ها</span></a>
  <a href="<?= e(admin_url('posts')) ?>"><b><?= e(digits((string) $stats['published'], 'fa')) ?></b><span>مطلب منتشرشده</span></a>
  <a href="<?= e(admin_url('posts')) ?>"><b><?= e(digits((string) $stats['drafts'], 'fa')) ?></b><span>پیش‌نویس</span></a>
</div>
<div class="two">
  <section class="panel">
    <h2>آخرین پیام‌ها</h2>
    <?php if (!$recent): ?><p class="muted">هنوز پیامی نرسیده است.</p><?php endif; ?>
    <ul class="list">
      <?php foreach ($recent as $r): $d = json_decode($r['data'], true) ?: []; ?>
        <li class="<?= $r['is_read'] ? '' : 'unread' ?>"><a href="<?= e(admin_url('messages/' . $r['id'])) ?>"><b><?= e($d['name'] ?? '—') ?></b><span class="tag <?= e($r['form']) ?>"><?= e(form_label($r['form'])) ?></span><small><?= e(admin_dt($r['created_at'])) ?></small></a></li>
      <?php endforeach; ?>
    </ul>
  </section>
  <section class="panel">
    <h2>آخرین مطالب</h2>
    <?php if (!$posts): ?><p class="muted">هنوز مطلبی ننوشته‌اید. <a href="<?= e(admin_url('posts/new')) ?>">اولین مطلب را بنویسید</a>.</p><?php endif; ?>
    <ul class="list">
      <?php foreach ($posts as $p): ?>
        <li><a href="<?= e(admin_url('posts/' . $p['id'])) ?>"><b><?= e($p['title']) ?></b><span class="tag <?= e($p['status']) ?>"><?= $p['status'] === 'published' ? 'منتشرشده' : 'پیش‌نویس' ?></span><small><?= e(lang_label($p['lang'])) ?></small></a></li>
      <?php endforeach; ?>
    </ul>
  </section>
</div>
