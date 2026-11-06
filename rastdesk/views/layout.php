<?php
$dir = dir_attr($lang);
$logo = $lang === 'fa' ? 'rastdesk-fa-on-dark.svg' : 'rastdesk-en-on-dark.svg';
$nav = ['modules' => 'modules', 'pricing' => 'pricing', 'blog' => 'blog', 'contact' => 'contact'];
$section = explode('/', $pathAfterLang)[0] ?? '';
$altPath = $pageName === 'post' ? 'blog' : (in_array($pageName, ['404'], true) || $section === 'quick' ? '' : $pathAfterLang);
$canonical = absolute_url(url($lang, $section === 'quick' ? '' : $pathAfterLang));
$phone = setting('phone', '021-88100050');
$mobile = setting('mobile', '0912 601 8213');
$email = setting('email', 'info@rastdesk.ir');
$rastinUrl = setting('rastin_url', 'https://rastingroup.ir');
$tel = fn($n) => 'tel:' . preg_replace('/[^\d+]/', '', $n);
$socials = [];
foreach (['instagram', 'linkedin', 'telegram', 'whatsapp', 'aparat'] as $s) {
    if (($u = setting_raw('social_' . $s, SOCIAL_DEFAULTS[$s] ?? '')) !== '') {
        $socials[$s] = $u;
    }
}
$groups = modules_data()['groups'];
?><!doctype html>
<html lang="<?= e($lang) ?>" dir="<?= e($dir) ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= e($title) ?></title>
<meta name="description" content="<?= e($description) ?>">
<link rel="canonical" href="<?= e($canonical) ?>">
<?php if (!in_array($pageName, ['post', '404'], true)): foreach (languages() as $l): ?>
<link rel="alternate" hreflang="<?= e($l) ?>" href="<?= e(absolute_url(url($l, $altPath))) ?>">
<?php endforeach; ?>
<link rel="alternate" hreflang="x-default" href="<?= e(absolute_url(url('fa', $altPath))) ?>">
<?php endif; ?>
<meta property="og:type" content="<?= $pageName === 'post' ? 'article' : 'website' ?>">
<meta property="og:title" content="<?= e($title) ?>">
<meta property="og:description" content="<?= e($description) ?>">
<meta property="og:url" content="<?= e($canonical) ?>">
<meta property="og:image" content="<?= e($ogImage) ?>">
<meta property="og:site_name" content="<?= e(t('brand.product')) ?>">
<meta name="twitter:card" content="summary_large_image">
<meta name="theme-color" content="#140F1E">
<link rel="icon" href="<?= e(asset('assets/img/favicon.svg')) ?>" type="image/svg+xml">
<link rel="icon" href="<?= e(asset('assets/img/favicon.ico')) ?>" sizes="any">
<link rel="apple-touch-icon" href="<?= e(asset('assets/img/apple-touch-icon.png')) ?>">
<link rel="preload" href="<?= e(asset('assets/fonts/Vazirmatn-Regular.woff2')) ?>" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="<?= e(asset('assets/fonts/Vazirmatn-Black.woff2')) ?>" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="<?= e(asset('assets/css/site.css')) ?>">
<script type="application/ld+json"><?= json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'SoftwareApplication',
    'name' => t('brand.product'),
    'alternateName' => 'RastDesk',
    'applicationCategory' => 'BusinessApplication',
    'operatingSystem' => 'Web, Android, iOS (PWA)',
    'url' => absolute_url(url($lang)),
    'description' => site_text('meta_description', 'meta.description'),
    'offers' => array_map(fn($ed) => ['@type' => 'Offer', 'name' => t('editions.' . $ed), 'price' => (int) edition_price($ed) * 1000000, 'priceCurrency' => 'IRR'], array_filter(EDITIONS, fn($ed) => is_numeric(edition_price($ed)))),
    'publisher' => ['@type' => 'Organization', 'name' => t('brand.company_full'), 'url' => $rastinUrl],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
</head>
<body class="page-<?= e($pageName) ?>">
<a class="skip" href="#main"><?= e(t('common.skip')) ?></a>

<header class="site-header">
  <div class="wrap header-row">
    <a class="logo" href="<?= e(url($lang)) ?>" aria-label="<?= e(t('brand.product')) ?>">
      <img src="<?= e(asset('assets/img/' . $logo)) ?>" alt="<?= e(t('brand.product')) ?>" width="190" height="56">
    </a>
    <nav class="main-nav" id="main-nav" aria-label="<?= e(t('common.menu')) ?>">
      <ul>
        <li><a href="<?= e(url($lang, 'modules')) ?>"<?= $section === 'modules' ? ' aria-current="page"' : '' ?>><?= e(t('nav.modules')) ?></a></li>
        <li><a href="<?= e(url($lang)) ?>#teams"><?= e(t('nav.teams')) ?></a></li>
        <li><a href="<?= e(url($lang, 'pricing')) ?>"<?= $section === 'pricing' ? ' aria-current="page"' : '' ?>><?= e(t('nav.pricing')) ?></a></li>
        <li><a href="<?= e(url($lang, 'blog')) ?>"<?= $section === 'blog' ? ' aria-current="page"' : '' ?>><?= e(t('nav.blog')) ?></a></li>
        <li><a href="<?= e(url($lang, 'contact')) ?>"<?= $section === 'contact' ? ' aria-current="page"' : '' ?>><?= e(t('nav.contact')) ?></a></li>
      </ul>
      <div class="nav-actions">
        <div class="lang-switch" role="group" aria-label="<?= e(t('common.language')) ?>">
          <?php foreach (languages() as $l): ?>
            <a href="<?= e(url($l, $altPath)) ?>" lang="<?= e($l) ?>" hreflang="<?= e($l) ?>"<?= $l === $lang ? ' aria-current="true"' : '' ?>><?= e(strtoupper($l)) ?></a>
          <?php endforeach; ?>
        </div>
        <a class="btn btn-ghost hide-md" href="<?= e(url($lang, 'catalog')) ?>"><?= icon('arrow', 'ico dl') ?><?= e(t('nav.catalog')) ?></a>
        <a class="btn btn-primary" href="<?= e(url($lang, 'demo')) ?>"><?= e(t('cta.demo_short')) ?></a>
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
      <img src="<?= e(asset('assets/img/' . $logo)) ?>" alt="<?= e(t('brand.product')) ?>" width="200" height="60">
      <p><?= e(t('footer.about')) ?></p>
      <div class="footer-cta">
        <a class="btn btn-primary" href="<?= e(url($lang, 'demo')) ?>"><?= e(t('cta.demo')) ?></a>
        <a class="btn btn-ghost" href="<?= e(url($lang, 'catalog')) ?>"><?= e(t('cta.catalog')) ?></a>
      </div>
    </div>
    <div>
      <h2 class="f-title"><?= e(t('footer.col_product')) ?></h2>
      <ul class="f-links">
        <li><a href="<?= e(url($lang, 'modules')) ?>"><?= e(t('nav.modules')) ?></a></li>
        <?php foreach ($groups as $gid => $g): ?><li><a href="<?= e(url($lang, 'modules#' . $gid)) ?>"><?= e($g['title']) ?></a></li><?php endforeach; ?>
        <li><a href="<?= e(url($lang, 'pricing')) ?>"><?= e(t('nav.pricing')) ?></a></li>
      </ul>
    </div>
    <div>
      <h2 class="f-title"><?= e(t('footer.col_company')) ?></h2>
      <ul class="f-links">
        <li><a href="<?= e($rastinUrl) ?>" target="_blank" rel="noopener"><?= e(t('footer.about_rastin')) ?> ↗</a></li>
        <li><a href="<?= e(url($lang, 'blog')) ?>"><?= e(t('nav.blog')) ?></a></li>
        <li><a href="<?= e(url($lang, 'demo')) ?>"><?= e(t('nav.demo')) ?></a></li>
        <li><a href="<?= e(url($lang, 'catalog')) ?>"><?= e(t('nav.catalog')) ?></a></li>
        <li><a href="<?= e(url($lang, 'contact')) ?>"><?= e(t('nav.contact')) ?></a></li>
      </ul>
    </div>
    <div>
      <h2 class="f-title"><?= e(t('footer.col_contact')) ?></h2>
      <ul class="f-contact">
        <li><?= icon('phone') ?><a href="<?= e($tel($phone)) ?>" dir="ltr"><?= e(digits($phone, $lang)) ?></a></li>
        <li><?= icon('mobile') ?><a href="<?= e($tel($mobile)) ?>" dir="ltr"><?= e(digits($mobile, $lang)) ?></a></li>
        <li><?= icon('mail') ?><a href="mailto:<?= e($email) ?>" dir="ltr"><?= e($email) ?></a></li>
        <li><?= icon('pin') ?><span><?= e(site_text('address', 'defaults.address')) ?></span></li>
      </ul>
      <?php if ($socials): ?>
        <div class="socials"><?php foreach ($socials as $s => $u): ?><a href="<?= e($u) ?>" target="_blank" rel="noopener" aria-label="<?= e(ucfirst($s)) ?>"><?= icon($s) ?></a><?php endforeach; ?></div>
      <?php endif; ?>
    </div>
  </div>
  <div class="wrap footer-bottom">
    <span>© <?= e(digits((string) ($lang === 'fa' ? gregorian_to_jalali((int) date('Y'), (int) date('n'), (int) date('j'))[0] : date('Y')), $lang)) ?> · <?= e(t('footer.rights')) ?></span>
    <a href="<?= e($rastinUrl) ?>" target="_blank" rel="noopener" class="by"><?= e(t('footer.made_by')) ?> ↗</a>
  </div>
</footer>

<!-- mobile action bar: the two standard conversion paths, always within thumb reach -->
<div class="mobile-bar" aria-label="<?= e(t('cta.call')) ?>">
  <a class="btn btn-ghost" href="<?= e($tel($mobile)) ?>"><?= icon('phone') ?><?= e(t('cta.call')) ?></a>
  <a class="btn btn-primary" href="<?= e(url($lang, 'demo')) ?>"><?= e(t('cta.demo_short')) ?></a>
</div>
<script src="<?= e(asset('assets/js/site.js')) ?>" defer></script>
</body>
</html>
