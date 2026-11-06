<div class="page-title"><h1>تنظیمات سایت</h1></div>
<form method="post" action="<?= e(admin_url('settings')) ?>" class="settings" enctype="multipart/form-data">
  <?= csrf_field() ?>
  <?php foreach ($groups as $gk => $g): ?>
    <section class="panel">
      <h2><?= e($g['title']) ?></h2>
      <?php if (!empty($g['help'])): ?><p class="muted"><?= e($g['help']) ?></p><?php endif; ?>
      <div class="grid2">
        <?php foreach ($g['fields'] as $key => $f): $val = setting_raw($key, $f['default']); ?>
          <label><?= e($f['label']) ?>
            <?php if (isset($f['options'])): ?>
              <select name="s[<?= e($key) ?>]"><?php foreach ($f['options'] as $ok => $ol): ?><option value="<?= e($ok) ?>"<?= $val === $ok ? ' selected' : '' ?>><?= e($ol) ?></option><?php endforeach; ?></select>
            <?php else: ?>
              <input name="s[<?= e($key) ?>]" value="<?= e($val) ?>" type="<?= e(($f['type'] ?? 'text') === 'url' ? 'text' : ($f['type'] ?? 'text')) ?>"<?= !empty($f['ltr']) ? ' dir="ltr"' : '' ?><?= !empty($f['placeholder']) ? ' placeholder="' . e($f['placeholder']) . '"' : '' ?>>
            <?php endif; ?>
          </label>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endforeach; ?>
  <section class="panel">
    <h2>فایل کاتالوگ (PDF)</h2>
    <?php $cf = setting('catalog_file'); ?>
    <p class="muted">فایل فعلی: <?= $cf !== '' ? '<a href="' . e(url('fa', 'catalog/download')) . '" target="_blank" rel="noopener" dir="ltr">' . e(basename($cf)) . '</a>' : 'کاتالوگ پیش‌فرض ۱۴۰۵ (همراه سایت)' ?></p>
    <label>آپلود کاتالوگ جدید (حداکثر ۳۰ مگابایت)<input type="file" name="catalog_pdf" accept="application/pdf"></label>
    <?php if ($cf !== ''): ?><label class="check"><input type="checkbox" name="catalog_reset" value="1"> بازگشت به کاتالوگ پیش‌فرض</label><?php endif; ?>
  </section>
  <section class="panel">
    <h2>متن‌های اصلی سایت</h2>
    <p class="muted">متن هر زبان جداگانه است. اگر متنی را پاک کنید، متن پیش‌فرض نمایش داده می‌شود.</p>
    <div class="tabs" role="tablist">
      <?php foreach (languages() as $i => $l): ?><button type="button" role="tab" data-tab="<?= e($l) ?>" aria-selected="<?= $i === 0 ? 'true' : 'false' ?>"><?= e(lang_label($l)) ?></button><?php endforeach; ?>
    </div>
    <?php foreach (languages() as $i => $l): ?>
      <div class="tab-panel" data-panel="<?= e($l) ?>"<?= $i === 0 ? '' : ' hidden' ?>>
        <?php foreach ($texts as $key => $f): $val = site_text($key, $f['key'], $l); ?>
          <label><?= e($f['label']) ?>
            <?php if (!empty($f['textarea'])): ?>
              <textarea name="t[<?= e($key) ?>][<?= e($l) ?>]" rows="3" dir="<?= e(dir_attr($l)) ?>"><?= e($val) ?></textarea>
            <?php else: ?>
              <input name="t[<?= e($key) ?>][<?= e($l) ?>]" value="<?= e($val) ?>" dir="<?= e(dir_attr($l)) ?>">
            <?php endif; ?>
          </label>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  </section>
  <div class="sticky-save"><button class="btn primary" type="submit">ذخیره‌ی تنظیمات</button></div>
</form>
