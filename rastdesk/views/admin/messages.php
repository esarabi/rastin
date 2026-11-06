<div class="page-title"><h1>سرنخ‌ها و پیام‌ها</h1><a class="btn" href="<?= e(admin_url('messages/export')) ?>">دریافت فایل اکسل (CSV)</a></div>
<div class="filters">
  <a href="<?= e(admin_url('messages')) ?>"<?= $filter === '' ? ' aria-current="true"' : '' ?>>همه</a>
  <?php foreach (form_types() as $fk => $fl): ?><a href="<?= e(admin_url('messages')) ?>?form=<?= e($fk) ?>"<?= $filter === $fk ? ' aria-current="true"' : '' ?>><?= e($fl) ?></a><?php endforeach; ?>
</div>
<div class="table-wrap">
<table class="table">
  <thead><tr><th>نام</th><th>سازمان</th><th>تلفن</th><th>فرم</th><th>زبان</th><th>تاریخ</th><th>ارسال به مقصد</th></tr></thead>
  <tbody>
  <?php if (!$rows): ?><tr><td colspan="7" class="muted">پیامی وجود ندارد.</td></tr><?php endif; ?>
  <?php foreach ($rows as $r): $d = json_decode($r['data'], true) ?: []; ?>
    <tr class="<?= $r['is_read'] ? '' : 'unread' ?>">
      <td><a href="<?= e(admin_url('messages/' . $r['id'])) ?>"><b><?= e($d['name'] ?? '—') ?></b></a></td>
      <td><?= e($d['company'] ?? '') ?></td>
      <td dir="ltr" class="ltr-cell"><?= e($d['phone'] ?? '') ?></td>
      <td><span class="tag <?= e($r['form']) ?>"><?= e(form_label($r['form'])) ?></span></td>
      <td><?= e(lang_label($r['lang'])) ?></td>
      <td><?= e(admin_dt($r['created_at'])) ?></td>
      <td><span class="fw <?= starts_with($r['forward_status'], 'sent') ? 'ok' : (starts_with($r['forward_status'], 'error') ? 'err' : '') ?>"><?= e(starts_with($r['forward_status'], 'sent') ? 'ارسال شد' : (starts_with($r['forward_status'], 'error') ? 'خطا' : 'فقط ذخیره')) ?></span></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</div>
