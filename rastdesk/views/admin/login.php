<div class="auth">
  <form class="auth-card" method="post" action="<?= e(admin_url('login')) ?>">
    <div class="auth-logo"><img class="mark" src="<?= e(asset('assets/img/rastdesk-icon.svg')) ?>" alt="" width="64" height="64"></div>
    <h1>ورود به پنل مدیریت</h1>
    <?php if ($error): ?><div class="flash err" role="alert"><?= e($error) ?></div><?php endif; ?>
    <?= csrf_field() ?>
    <label>نام کاربری<input name="username" value="<?= e($username) ?>" dir="ltr" autocomplete="username" required autofocus></label>
    <label>رمز عبور<input name="password" type="password" dir="ltr" autocomplete="current-password" required></label>
    <button class="btn primary block" type="submit">ورود</button>
  </form>
</div>
