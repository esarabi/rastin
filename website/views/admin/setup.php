<div class="auth">
  <form class="auth-card" method="post" action="<?= e(admin_url('setup')) ?>">
    <div class="auth-logo"><?= brand_mark() ?></div>
    <h1>راه‌اندازی پنل مدیریت</h1>
    <p class="muted">این صفحه فقط یک بار نمایش داده می‌شود. حساب مدیر اصلی سایت را بسازید.</p>
    <?php foreach ($errors as $er): ?><div class="flash err"><?= e($er) ?></div><?php endforeach; ?>
    <?= csrf_field() ?>
    <label>نام نمایشی<input name="display_name" value="<?= e($old['display_name']) ?>"></label>
    <label>نام کاربری (انگلیسی)<input name="username" value="<?= e($old['username']) ?>" dir="ltr" autocomplete="username" required></label>
    <label>رمز عبور (حداقل ۱۰ کاراکتر)<input name="password" type="password" dir="ltr" autocomplete="new-password" minlength="10" required></label>
    <label>تکرار رمز عبور<input name="password2" type="password" dir="ltr" autocomplete="new-password" minlength="10" required></label>
    <button class="btn primary block" type="submit">ساخت حساب و ورود</button>
  </form>
</div>
