<?php

$ok = flash('ok');
$err = flash('error');
$path = trim(substr(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '', strlen(admin_url())), '/');
$sec = explode('/', $path)[0];
?><!doctype html>
<html lang="fa" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>پنل مدیریت راستین</title>
<link rel="icon" href="<?= e(asset('assets/img/favicon.svg')) ?>" type="image/svg+xml">
<link rel="stylesheet" href="<?= e(asset('assets/css/admin.css')) ?>">
</head>
<body class="<?= !empty($bare) ? 'bare' : '' ?>">
<?php if (empty($bare)): ?>
<aside class="side">
  <a class="side-logo" href="<?= e(admin_url()) ?>"><?= brand_mark() ?><span>پنل راستین</span></a>
  <nav>
    <a href="<?= e(admin_url()) ?>"<?= $sec === '' ? ' aria-current="page"' : '' ?>><?= icon('chart') ?>پیشخوان</a>
    <a href="<?= e(admin_url('posts')) ?>"<?= $sec === 'posts' ? ' aria-current="page"' : '' ?>><?= icon('megaphone') ?>وبلاگ</a>
    <a href="<?= e(admin_url('messages')) ?>"<?= $sec === 'messages' ? ' aria-current="page"' : '' ?>><?= icon('mail') ?>پیام‌ها و درخواست‌ها<?php if ($unread): ?><b class="count"><?= e(digits((string) $unread, 'fa')) ?></b><?php endif; ?></a>
    <a href="<?= e(admin_url('settings')) ?>"<?= $sec === 'settings' ? ' aria-current="page"' : '' ?>><?= icon('flow') ?>تنظیمات سایت</a>
    <a href="<?= e(admin_url('account')) ?>"<?= $sec === 'account' ? ' aria-current="page"' : '' ?>><?= icon('shield') ?>حساب کاربری</a>
  </nav>
  <div class="side-foot">
    <a class="view-site" href="<?= e(url('fa')) ?>" target="_blank" rel="noopener">مشاهده‌ی سایت ↗</a>
    <form method="post" action="<?= e(admin_url('logout')) ?>"><?= csrf_field() ?><button type="submit">خروج <?= $user ? '(' . e($user['display_name'] ?: $user['username']) . ')' : '' ?></button></form>
  </div>
</aside>
<?php endif; ?>
<main class="main">
  <?php if ($ok): ?><div class="flash ok" role="status"><?= e($ok) ?></div><?php endif; ?>
  <?php if ($err): ?><div class="flash err" role="alert"><?= e($err) ?></div><?php endif; ?>
  <?= $content ?>
</main>
<script src="<?= e(asset('assets/js/admin.js')) ?>" defer></script>
</body>
</html>
