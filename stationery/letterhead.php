<?php
/**
 * Rastin letterhead, A4.
 * php stationery/letterhead.php <bleed_mm> <fa|en> <print|bg>
 *   print = full design with field labels (for PDF / print shop)
 *   bg    = background only, no field labels (used behind text in the Word template)
 */
declare(strict_types=1);
require __DIR__ . '/../website/app/bootstrap.php';

$bleed = (float) ($argv[1] ?? 0);
$lang = ($argv[2] ?? 'fa') === 'en' ? 'en' : 'fa';
$mode = ($argv[3] ?? 'print') === 'bg' ? 'bg' : 'print';
$rtl = $lang === 'fa';
$R = '../..';
$svg = function (string $file): string {
    $s = file_get_contents(__DIR__ . '/../' . $file);
    $s = preg_replace('/<\?xml[^>]*>/', '', $s);
    return preg_replace('/ width="[\d.]+" height="[\d.]+"/', '', $s, 1);
};
$T = $rtl ? [
    'fields' => ['شماره', 'تاریخ', 'پیوست'],
    'address' => 'تهران، جردن، بلوار آفریقا، ابتدای خیابان دستگردی غربی، پلاک ۲۶۹، طبقه‌ی ۳',
    'phone' => '۰۲۱-۸۸۱۰۰۰۵۰',
    'tagline' => t('brand.tagline', 'fa'),
    'logo' => 'brand/logo/rastin-horizontal-fa-color.svg',
] : [
    'fields' => ['Ref.', 'Date', 'Encl.'],
    'address' => '3rd Floor, No. 269, W. Dastgerdi St., Africa Blvd. (Jordan), Tehran, Iran',
    'phone' => '+98 21 8810 0050',
    'tagline' => t('brand.tagline', 'en'),
    'logo' => 'brand/logo/rastin-horizontal-en-color.svg',
];
?><!doctype html>
<html lang="<?= $lang ?>" dir="<?= $rtl ? 'rtl' : 'ltr' ?>">
<head>
<meta charset="utf-8">
<title>Rastin letterhead</title>
<style>
@font-face{font-family:"Vazirmatn";src:url("<?= $R ?>/website/assets/fonts/Vazirmatn-Regular.woff2") format("woff2");font-weight:400}
@font-face{font-family:"Vazirmatn";src:url("<?= $R ?>/website/assets/fonts/Vazirmatn-Medium.woff2") format("woff2");font-weight:500}
@font-face{font-family:"Vazirmatn";src:url("<?= $R ?>/website/assets/fonts/Vazirmatn-Bold.woff2") format("woff2");font-weight:700}
:root{--b:<?= $bleed ?>mm;--coral:#FF5A36;--amber:#FFB547;--ink:#1F1630;--muted:#6B6375;--line:#E6DFE3}
@page{size:<?= 210 + 2 * $bleed ?>mm <?= 297 + 2 * $bleed ?>mm;margin:0}
*{box-sizing:border-box;-webkit-print-color-adjust:exact;print-color-adjust:exact}
html,body{margin:0}
body{font-family:"Vazirmatn",Tahoma,sans-serif;color:var(--ink);background:#999}
@media screen{body{display:grid;justify-content:center;padding:8mm}.sheet{box-shadow:0 2mm 8mm rgba(0,0,0,.3)}}
.sheet{position:relative;width:calc(210mm + 2 * var(--b));height:calc(297mm + 2 * var(--b));overflow:hidden;background:#fff}
.band{position:absolute;top:0;bottom:0;inset-inline-start:0;width:calc(var(--b) + 2.6mm);background:var(--coral)}
.band::after{content:"";position:absolute;inset-inline:0;top:calc(var(--b) + 14mm);height:16mm;background:var(--amber)}
.mark-wm{position:absolute;width:120mm;height:120mm;inset-inline-end:calc(var(--b) - 22mm);bottom:calc(var(--b) + 6mm);opacity:.035}
.head{position:absolute;top:calc(var(--b) + 13mm);inset-inline:calc(var(--b) + 16mm) calc(var(--b) + 16mm);display:flex;justify-content:space-between;align-items:flex-start}
.head .logo svg{height:17mm;width:auto;display:block}
.fields{display:grid;gap:1.6mm;font-size:8.5pt;color:var(--muted);min-width:46mm;padding-top:1mm}
.fields div{display:flex;gap:2mm;align-items:baseline}
.fields b{font-weight:700;color:var(--ink);min-width:11mm}
.fields i{flex:1;border-bottom:.25mm dotted #b9b0c4;height:3.5mm}
.rule{position:absolute;top:calc(var(--b) + 38mm);inset-inline:calc(var(--b) + 16mm) calc(var(--b) + 16mm);height:.3mm;background:var(--line)}
.rule::before{content:"";position:absolute;inset-inline-start:0;top:-.25mm;width:22mm;height:.8mm;background:var(--coral);border-radius:1mm}
.foot{position:absolute;bottom:calc(var(--b) + 10mm);inset-inline:calc(var(--b) + 16mm) calc(var(--b) + 16mm);border-top:.3mm solid var(--line);padding-top:3mm;display:flex;justify-content:space-between;align-items:flex-end;gap:6mm}
.foot ul{list-style:none;margin:0;padding:0;display:grid;gap:1mm;font-size:7.6pt;color:var(--ink)}
.foot li{display:flex;gap:1.6mm;align-items:center}
.foot .row{display:flex;gap:5mm;flex-wrap:wrap}
.foot .row span{display:flex;gap:1.4mm;align-items:center}
.foot .ico{width:3.2mm;height:3.2mm;color:var(--coral);flex:none}
.foot .ltr{direction:ltr;unicode-bidi:isolate}
.foot .tag{font-size:8pt;font-weight:700;color:var(--coral);white-space:nowrap}
</style>
</head>
<body>
<section class="sheet">
  <span class="band"></span>
  <?= str_replace('class="mark"', 'class="mark mark-wm"', brand_mark()) ?>
  <div class="head">
    <div class="logo"><?= $svg($T['logo']) ?></div>
    <?php if ($mode === 'print'): ?>
      <div class="fields"><?php foreach ($T['fields'] as $f): ?><div><b><?= e($f) ?>:</b><i></i></div><?php endforeach; ?></div>
    <?php endif; ?>
  </div>
  <div class="rule"></div>
  <div class="foot">
    <ul>
      <li><?= icon('pin') ?><span><?= e($T['address']) ?></span></li>
      <li class="row">
        <span><?= icon('phone') ?><span class="ltr"><?= e($T['phone']) ?></span></span>
        <span><?= icon('mail') ?><span class="ltr">info@rastingroup.ir</span></span>
        <span><?= icon('globe') ?><span class="ltr">rastingroup.ir</span></span>
      </li>
    </ul>
    <span class="tag"><?= e($T['tagline']) ?></span>
  </div>
</section>
</body>
</html>
