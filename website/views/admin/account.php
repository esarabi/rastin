<div class="page-title"><h1>حساب کاربری</h1></div>
<?php foreach ($errors as $er): ?><div class="flash err"><?= e($er) ?></div><?php endforeach; ?>
<form class="panel narrow-form" method="post" action="<?= e(admin_url('account')) ?>">
  <?= csrf_field() ?>
  <label>نام کاربری<input value="<?= e($user['username']) ?>" dir="ltr" disabled></label>
  <label>نام نمایشی<input name="display_name" value="<?= e($user['display_name']) ?>"></label>
  <h2>تغییر رمز عبور</h2>
  <p class="muted small">اگر نمی‌خواهید رمز را عوض کنید، این بخش را خالی بگذارید.</p>
  <label>رمز عبور فعلی<input name="current_password" type="password" dir="ltr" autocomplete="current-password"></label>
  <label>رمز عبور جدید (حداقل ۱۰ کاراکتر)<input name="new_password" type="password" dir="ltr" autocomplete="new-password"></label>
  <label>تکرار رمز عبور جدید<input name="new_password2" type="password" dir="ltr" autocomplete="new-password"></label>
  <button class="btn primary" type="submit">ذخیره</button>
</form>
