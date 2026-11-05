<?php

$dir = dir_attr($lang);
$rtl = $dir === 'rtl';
$logo = in_array($lang, ['fa', 'ar'], true) ? 'rastin-horizontal-fa-color.svg' : 'rastin-horizontal-en-color.svg';
$logoReverse = in_array($lang, ['fa', 'ar'], true) ? 'rastin-horizontal-fa-reverse.svg' : 'rastin-horizontal-en-reverse.svg';
$nav = ['' => 'home', 'rastdesk' => 'rastdesk', 'solutions' => 'solutions', 'pricing' => 'pricing', 'about' => 'about', 'blog' => 'blog', 'contact' => 'contact'];
$section = explode('/', $pathAfterLang)[0] ?? '';
// For a blog post the other languages have different posts, so switch to their blog index.
$altPath = $pageName === 'post' ? 'blog' : ($pageName === '404' ? '' : $pathAfterLang);
$canonical = absolute_url(url($lang, $pathAfterLang));
$phone = setting('phone', '021-88100050');
$email = setting('email', 'info@rastingroup.ir');
$rastdeskUrl = setting('rastdesk_url', 'https://rastdesk.ir');
$socials = [];
foreach (['instagram', 'linkedin', 'telegram', 'whatsapp', 'x', 'aparat'] as $s) {
    if (($u = setting_raw('social_' . $s, SOCIAL_DEFAULTS[$s] ?? '')) !== '') {
        $socials[$s] = $u;
    }
}
?><!doctype html>
<html lang="<?= e($lang) ?>" dir="<?= e($dir) ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= e($title) ?></title>
<meta name="description" content="<?= e($description) ?>">
<link rel="canonical" href="<?= e($canonical) ?>">
<?php if ($pageName !== 'post' && $pageName !== '404'): foreach (languages() as $l): ?>
<link rel="alternate" hreflang="<?= e($l) ?>" href="<?= e(absolute_url(url($l, $altPath))) ?>">
<?php endforeach; ?>
<link rel="alternate" hreflang="x-default" href="<?= e(absolute_url(url(config('default_lang', 'fa'), $altPath))) ?>">
<?php endif; ?>
<meta property="og:type" content="<?= $pageName === 'post' ? 'article' : 'website' ?>">
<meta property="og:title" content="<?= e($title) ?>">
<meta property="og:description" content="<?= e($description) ?>">
<meta property="og:url" content="<?= e($canonical) ?>">
<meta property="og:image" content="<?= e($ogImage) ?>">
<meta property="og:locale" content="<?= e(t('meta.locale')) ?>">
<meta property="og:site_name" content="<?= e(t('brand.company_full')) ?>">
<meta name="twitter:card" content="summary_large_image">
<meta name="theme-color" content="#FF5A36">
<link rel="icon" href="<?= e(asset('assets/img/favicon.ico')) ?>" sizes="any">
<link rel="icon" href="<?= e(asset('assets/img/favicon.svg')) ?>" type="image/svg+xml">
<link rel="apple-touch-icon" href="<?= e(asset('assets/img/apple-touch-icon.png')) ?>">
<link rel="preload" href="<?= e(asset('assets/fonts/Vazirmatn-Regular.woff2')) ?>" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="<?= e(asset('assets/fonts/Vazirmatn-Black.woff2')) ?>" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="<?= e(asset('assets/css/site.css')) ?>">
<script type="application/ld+json"><?= json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => t('brand.company_full'),
    'alternateName' => 'Rastin Intelligent Development',
    'url' => absolute_url(url($lang)),
    'logo' => absolute_url(asset('assets/img/icon-512.png')),
    'email' => $email,
    'telephone' => $phone,
    'address' => site_text('address', 'defaults.address'),
    'foundingDate' => '2025',
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
</head>
<body class="page-<?= e($pageName) ?>">
<a class="skip" href="#main"><?= e(t('common.skip')) ?></a>

<header class="site-header" id="top">
  <div class="wrap header-row">
    <a class="logo" href="<?= e(url($lang)) ?>" aria-label="<?= e(t('brand.company_full')) ?>">
      <img src="<?= e(asset('assets/img/' . $logo)) ?>" alt="<?= e(t('brand.company_full')) ?>" width="200" height="58">
    </a>
    <nav class="main-nav" id="main-nav" aria-label="<?= e(t('common.menu')) ?>">
      <ul>
        <?php foreach ($nav as $path => $key): if ($key === 'contact') continue; ?>
          <li><a href="<?= e(url($lang, $path)) ?>"<?= ($section === $path) ? ' aria-current="page"' : '' ?>><?= e(t('nav.' . $key)) ?></a></li>
        <?php endforeach; ?>
        <li class="only-mobile"><a href="<?= e(url($lang, 'contact')) ?>"><?= e(t('nav.contact')) ?></a></li>
      </ul>
      <div class="nav-actions">
        <details class="lang-switch">
          <summary aria-label="<?= e(t('common.language')) ?>"><?= icon('globe') ?><span><?= e(strtoupper($lang)) ?></span></summary>
          <ul>
            <?php foreach (languages() as $l): ?>
              <li><a href="<?= e(url($l, $altPath)) ?>" lang="<?= e($l) ?>" hreflang="<?= e($l) ?>"<?= $l === $lang ? ' aria-current="true"' : '' ?>><?= e(t('meta.name', $l)) ?></a></li>
            <?php endforeach; ?>
          </ul>
        </details>
        <a class="btn btn-ghost hide-sm" href="<?= e(url($lang, 'contact')) ?>"><?= e(t('nav.contact')) ?></a>
        <a class="btn btn-primary" href="<?= e(url($lang, 'demo')) ?>"><?= e(t('nav.demo')) ?></a>
      </div>
    </nav>
    <button class="nav-toggle" type="button" aria-controls="main-nav" aria-expanded="false">
      <?= icon('menu', 'ico open') ?><?= icon('close', 'ico shut') ?><span class="sr"><?= e(t('common.menu')) ?></span>
    </button>
  </div>
</header>

<main id="main">
<?= $content ?>
</main>

<footer class="site-footer">
  <div class="wrap footer-grid">
    <div class="footer-brand">
      <img src="<?= e(asset('assets/img/' . $logoReverse)) ?>" alt="<?= e(t('brand.company_full')) ?>" width="220" height="64">
      <p><?= e(t('footer.about')) ?></p>
      <?php if ($socials): ?>
        <div class="socials" aria-label="<?= e(t('common.follow')) ?>">
          <?php foreach ($socials as $s => $u): ?>
            <a href="<?= e($u) ?>" target="_blank" rel="noopener" aria-label="<?= e(ucfirst($s)) ?>"><?= icon($s) ?></a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
    <div>
      <h2 class="f-title"><?= e(t('footer.col_company')) ?></h2>
      <ul class="f-links">
        <li><a href="<?= e(url($lang, 'about')) ?>"><?= e(t('nav.about')) ?></a></li>
        <li><a href="<?= e(url($lang, 'rastdesk')) ?>"><?= e(t('nav.rastdesk')) ?></a></li>
        <li><a href="<?= e(url($lang, 'pricing')) ?>"><?= e(t('nav.pricing')) ?></a></li>
        <li><a href="<?= e(url($lang, 'blog')) ?>"><?= e(t('nav.blog')) ?></a></li>
        <li><a href="<?= e($rastdeskUrl) ?>" target="_blank" rel="noopener"><?= e(t('common.visit_rastdesk')) ?> ↗</a></li>
      </ul>
    </div>
    <div>
      <h2 class="f-title"><?= e(t('footer.col_solutions')) ?></h2>
      <ul class="f-links">
        <?php foreach ((array) t('solutions.items') as $it): ?>
          <li><a href="<?= e(url($lang, 'solutions#' . $it['id'])) ?>"><?= e($it['title']) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div>
      <h2 class="f-title"><?= e(t('footer.col_contact')) ?></h2>
      <ul class="f-contact">
        <li><?= icon('pin') ?><span><?= e(site_text('address', 'defaults.address')) ?></span></li>
        <li><?= icon('phone') ?><a href="tel:<?= e(preg_replace('/[^\d+]/', '', $phone)) ?>" dir="ltr"><?= e(digits($phone, $lang)) ?></a></li>
        <li><?= icon('mail') ?><a href="mailto:<?= e($email) ?>" dir="ltr"><?= e($email) ?></a></li>
      </ul>
    </div>
  </div>
  <div class="wrap footer-bottom">
    <span>© <?= e(digits((string) ($lang === 'fa' ? gregorian_to_jalali((int) date('Y'), (int) date('n'), (int) date('j'))[0] : date('Y')), $lang)) ?> · <?= e(t('footer.rights')) ?></span>
    <span class="tagline"><?= e(site_text('tagline', 'brand.tagline')) ?></span>
  </div>
</footer>
<script src="<?= e(asset('assets/js/site.js')) ?>" defer></script>
</body>
</html>
