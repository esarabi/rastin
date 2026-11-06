<?php
$labels = ['name' => 'نام', 'company' => 'سازمان', 'position' => 'سمت', 'phone' => 'تلفن', 'email' => 'ایمیل', 'edition_label' => 'نسخه', 'users_label' => 'تعداد کاربران', 'deploy_label' => 'نوع استقرار', 'module_label' => 'ماژول مورد علاقه', 'message' => 'پیام', 'page' => 'صفحه'];
?>
<div class="page-title"><h1><?= e(form_label($row['form'])) ?> — <?= e($data['name'] ?? '') ?></h1><a class="btn" href="<?= e(admin_url('messages')) ?>">بازگشت به فهرست</a></div>
<section class="panel">
  <dl class="kv">
    <?php foreach ($labels as $k => $lb): if (($data[$k] ?? '') === '') continue; ?>
      <dt><?= e($lb) ?></dt>
      <dd<?= in_array($k, ['phone', 'email', 'page'], true) ? ' dir="ltr" class="ltr-cell"' : '' ?>>
        <?php if ($k === 'phone'): ?><a href="tel:<?= e(preg_replace('/[^\d+]/', '', $data[$k])) ?>"><?= e($data[$k]) ?></a>
        <?php elseif ($k === 'email'): ?><a href="mailto:<?= e($data[$k]) ?>"><?= e($data[$k]) ?></a>
        <?php else: ?><?= nl2br(e($data[$k])) ?><?php endif; ?>
      </dd>
    <?php endforeach; ?>
    <dt>زبان سایت</dt><dd><?= e(lang_label($row['lang'])) ?></dd>
    <dt>تاریخ</dt><dd><?= e(admin_dt($row['created_at'])) ?></dd>
    <dt>IP</dt><dd dir="ltr" class="ltr-cell"><?= e($row['ip']) ?></dd>
    <dt>ارسال به مقصد</dt><dd dir="ltr" class="ltr-cell"><?= e($row['forward_status'] ?: '—') ?></dd>
  </dl>
</section>
<div class="actions-row">
  <form method="post" action="<?= e(admin_url('messages/' . $row['id'] . '/resend')) ?>"><?= csrf_field() ?><button class="btn" type="submit">ارسال دوباره به نشانی مقصد</button></form>
  <form method="post" action="<?= e(admin_url('messages/' . $row['id'] . '/delete')) ?>" data-confirm="این پیام حذف شود؟"><?= csrf_field() ?><button class="btn danger" type="submit">حذف پیام</button></form>
</div>
