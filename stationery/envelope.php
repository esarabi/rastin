<?php
/**
 * Rastin envelopes (front side).
 * php stationery/envelope.php <bleed_mm> <dl|c4>
 *   dl = 220×110 mm letter envelope (landscape)
 *   c4 = 229×324 mm A4 envelope (portrait)
 */
declare(strict_types=1);
require __DIR__ . '/../website/app/bootstrap.php';

$bleed = (float) ($argv[1] ?? 0);
$size = ($argv[2] ?? 'dl') === 'c4' ? 'c4' : 'dl';
[$W, $H] = $size === 'c4' ? [229, 324] : [220, 110];
$R = '../..';
$svg = function (string $file): string {
    $s = file_get_contents(__DIR__ . '/../' . $file);
    $s = preg_replace('/<\?xml[^>]*>/', '', $s);
    return preg_replace('/ width="[\d.]+" height="[\d.]+"/', '', $s, 1);
};
?><!doctype html>
<html lang="fa" dir="rtl">
<head>
<meta charset="utf-8">
<title>Rastin envelope <?= strtoupper($size) ?></title>
<style>
@font-face{font-family:"Vazirmatn";src:url("<?= $R ?>/website/assets/fonts/Vazirmatn-Regular.woff2") format("woff2");font-weight:400}
@font-face{font-family:"Vazirmatn";src:url("<?= $R ?>/website/assets/fonts/Vazirmatn-Bold.woff2") format("woff2");font-weight:700}
:root{--b:<?= $bleed ?>mm;--coral:#FF5A36;--amber:#FFB547;--ink:#1F1630;--muted:#6B6375;--line:#E6DFE3}
@page{size:<?= $W + 2 * $bleed ?>mm <?= $H + 2 * $bleed ?>mm;margin:0}
*{box-sizing:border-box;-webkit-print-color-adjust:exact;print-color-adjust:exact}
html,body{margin:0}
body{font-family:"Vazirmatn",Tahoma,sans-serif;color:var(--ink);background:#999}
@media screen{body{display:grid;justify-content:center;padding:8mm}.sheet{box-shadow:0 2mm 8mm rgba(0,0,0,.3)}}
.sheet{position:relative;width:calc(<?= $W ?>mm + 2 * var(--b));height:calc(<?= $H ?>mm + 2 * var(--b));overflow:hidden;background:#fff}
.band{position:absolute;inset-inline:0;bottom:0;height:calc(var(--b) + 4mm);background:var(--coral)}
.band::after{content:"";position:absolute;inset-inline-start:calc(var(--b) + 14mm);top:0;bottom:0;width:22mm;background:var(--amber)}
.mark-wm{position:absolute;opacity:.05}
.from{position:absolute;top:calc(var(--b) + 11mm);inset-inline-start:calc(var(--b) + 12mm);display:grid;gap:2.2mm;max-width:80mm}
.from .logo svg{height:<?= $size === 'c4' ? 17 : 13 ?>mm;width:auto;display:block}
.from p{margin:0;font-size:<?= $size === 'c4' ? 8 : 7 ?>pt;color:var(--muted);line-height:1.7}
.from .ltr{direction:ltr;unicode-bidi:isolate}
.to{position:absolute;display:grid;gap:<?= $size === 'c4' ? 9 : 7 ?>mm;font-size:<?= $size === 'c4' ? 9 : 8 ?>pt;color:var(--muted)}
.to div{display:flex;gap:3mm;align-items:baseline}
.to b{color:var(--ink);font-weight:700;min-width:12mm}
.to i{flex:1;border-bottom:.25mm dotted #b9b0c4}
.tag{position:absolute;font-weight:700;color:var(--coral);font-size:<?= $size === 'c4' ? 9 : 7.5 ?>pt}
<?php if ($size === 'dl'): ?>
.mark-wm{width:70mm;height:70mm;inset-inline-end:calc(var(--b) - 12mm);top:calc(var(--b) - 10mm)}
.to{inset-inline-end:calc(var(--b) + 14mm);bottom:calc(var(--b) + 15mm);width:92mm}
.tag{inset-inline-start:calc(var(--b) + 12mm);bottom:calc(var(--b) + 8.5mm)}
<?php else: ?>
.mark-wm{width:150mm;height:150mm;inset-inline-end:calc(var(--b) - 30mm);bottom:calc(var(--b) + 10mm)}
.to{inset-inline:calc(var(--b) + 40mm);top:calc(var(--b) + 125mm)}
.from{max-width:130mm}
.tag{inset-inline-start:calc(var(--b) + 12mm);bottom:calc(var(--b) + 11mm)}
<?php endif; ?>
</style>
</head>
<body>
<section class="sheet">
  <?= str_replace('class="mark"', 'class="mark mark-wm"', brand_mark()) ?>
  <div class="from">
    <div class="logo"><?= $svg('brand/logo/rastin-horizontal-fa-color.svg') ?></div>
    <p>تهران، جردن، بلوار آفریقا، ابتدای خیابان دستگردی غربی، پلاک&nbsp;۲۶۹، طبقه‌ی&nbsp;۳<br>
      تلفن <span class="ltr">۰۲۱-۸۸۱۰۰۰۵۰</span> · <span class="ltr">rastingroup.ir</span></p>
  </div>
  <div class="to">
    <div><b>گیرنده:</b><i></i></div>
    <div><b>نشانی:</b><i></i></div>
    <?php if ($size === 'c4'): ?><div><b></b><i></i></div><?php endif; ?>
  </div>
  <span class="tag"><?= e(t('brand.tagline', 'fa')) ?></span>
  <span class="band"></span>
</section>
</body>
</html>
