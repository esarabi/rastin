<?php
/**
 * Rastin business card — 85×55 mm, three faces: front (logo), back in Persian, back in English.
 * Render: php stationery/business-card.php [bleed_mm] > stationery/build/business-card.html
 * Edit the $person array to make a card for someone else.
 */
declare(strict_types=1);
require __DIR__ . '/../website/app/bootstrap.php';

$bleed = (float) ($argv[1] ?? 0);
$person = [
    'fa' => ['name' => 'ارشاد سرابی', 'title' => 'مدیرعامل'],
    'en' => ['name' => 'Ershad Sarabi', 'title' => 'Chief Executive Officer'],
    'mobile' => '0912 601 8213',
    'office' => '021 8810 0050',
    'email' => 'info@rastingroup.ir',
    'web' => 'rastingroup.ir',
];
$R = '../..';
$svg = function (string $file, bool $dropBg = false): string {
    $s = file_get_contents(__DIR__ . '/../' . $file);
    $s = preg_replace('/<\?xml[^>]*>/', '', $s);
    if (strpos($s, 'viewBox') === false && preg_match('/<svg[^>]* width="([\d.]+)" height="([\d.]+)"/', $s, $m)) {
        $s = preg_replace('/<svg /', '<svg viewBox="0 0 ' . $m[1] . ' ' . $m[2] . '" ', $s, 1);
    }
    $s = preg_replace('/ width="[\d.]+" height="[\d.]+"/', '', $s, 1);
    if ($dropBg) {
        $s = preg_replace('/<rect width="[\d.]+" height="[\d.]+"( rx="[\d.]+")? fill="#[0-9A-Fa-f]+"\/>/', '', $s, 1);
    }
    return $s;
};
$fa = fn($x) => digits($x, 'fa');
?><!doctype html>
<html lang="fa" dir="rtl">
<head>
<meta charset="utf-8">
<title>کارت ویزیت — <?= e($person['fa']['name']) ?></title>
<style>
@font-face{font-family:"Vazirmatn";src:url("<?= $R ?>/website/assets/fonts/Vazirmatn-Regular.woff2") format("woff2");font-weight:400}
@font-face{font-family:"Vazirmatn";src:url("<?= $R ?>/website/assets/fonts/Vazirmatn-Medium.woff2") format("woff2");font-weight:500}
@font-face{font-family:"Vazirmatn";src:url("<?= $R ?>/website/assets/fonts/Vazirmatn-Bold.woff2") format("woff2");font-weight:700}
@font-face{font-family:"Vazirmatn";src:url("<?= $R ?>/website/assets/fonts/Vazirmatn-Black.woff2") format("woff2");font-weight:900}
:root{--b:<?= $bleed ?>mm;--coral:#FF5A36;--amber:#FFB547;--ink:#1F1630;--ink-3:#3A2E4F;--muted:#6B6375;--line:#ECE6E9}
@page{size:<?= 85 + 2 * $bleed ?>mm <?= 55 + 2 * $bleed ?>mm;margin:0}
*{box-sizing:border-box;-webkit-print-color-adjust:exact;print-color-adjust:exact}
html,body{margin:0}
body{font-family:"Vazirmatn",Tahoma,sans-serif;background:#999}
@media screen{body{display:grid;gap:8mm;justify-content:center;padding:8mm}.card{box-shadow:0 2mm 8mm rgba(0,0,0,.3)}}
.card{position:relative;width:calc(85mm + 2 * var(--b));height:calc(55mm + 2 * var(--b));overflow:hidden;background:#fff;break-after:page}
.card:last-child{break-after:auto}
.safe{position:absolute;inset:calc(var(--b) + 4.5mm)}
.ico{width:2.9mm;height:2.9mm;flex:none}

/* front */
.front{background:var(--ink)}
.front canvas{position:absolute;inset:0;width:100%;height:100%}
.front .safe{display:flex;flex-direction:column;justify-content:center;align-items:center;gap:3.2mm}
.front .logo svg{height:14.5mm;width:auto;display:block}
.front .tag{color:var(--coral);font-weight:700;font-size:7.2pt;letter-spacing:.02em}
.front .web{position:absolute;bottom:0;left:0;right:0;text-align:center;color:#b9b0c4;font-size:6pt;letter-spacing:.08em}

/* backs */
.back .band{position:absolute;top:0;bottom:0;inset-inline-start:0;width:calc(var(--b) + 2.2mm);background:var(--coral)}
.back .safe{display:grid;grid-template-columns:1fr auto;grid-template-rows:auto 1fr;column-gap:4mm;inset-inline-start:calc(var(--b) + 7mm)}
.who{grid-column:1/-1;display:flex;justify-content:space-between;align-items:flex-start;padding-bottom:2.6mm;border-bottom:.25mm solid var(--line)}
.who h1{margin:0;font-size:12.5pt;font-weight:900;color:var(--ink);line-height:1.35}
.who p{margin:0;font-size:7.4pt;font-weight:700;color:var(--coral);line-height:1.5}
.who .mk{width:8mm;height:8mm;flex:none}
.contacts{list-style:none;margin:0;padding:2.8mm 0 0;display:grid;gap:1.15mm;align-content:start}
.contacts li{display:flex;align-items:center;gap:1.6mm;font-size:6.9pt;color:var(--ink);line-height:1.45;white-space:nowrap}
.contacts li .ico{color:var(--coral)}
.contacts .ltr{direction:ltr;unicode-bidi:isolate}
.contacts .addr{white-space:normal;font-size:6pt;color:var(--muted);align-items:flex-start;line-height:1.55}
.contacts .addr .ico{margin-top:.4mm}
.qr{align-self:end;display:grid;justify-items:center;gap:.8mm}
.qr svg{width:20.5mm;height:20.5mm;display:block}
.qr span{font-size:4.8pt;color:var(--muted);text-align:center;line-height:1.3}
.en{direction:ltr}
.en .who h1{font-size:11.5pt;letter-spacing:-.005em}
.en .who p{font-size:6.8pt}
.en .contacts li{font-size:6.8pt}
</style>
</head>
<body>

<!-- Front -->
<section class="card front">
  <canvas data-seed="13"></canvas>
  <div class="safe">
    <div class="logo"><?= $svg('brand/logo/rastin-horizontal-fa-reverse-transparent.svg') ?></div>
    <div class="tag"><?= e(t('brand.tagline', 'fa')) ?></div>
    <div class="web" dir="ltr"><?= e($person['web']) ?></div>
  </div>
</section>

<!-- Back, Persian -->
<section class="card back" dir="rtl">
  <span class="band"></span>
  <div class="safe">
    <div class="who">
      <div><h1><?= e($person['fa']['name']) ?></h1><p><?= e($person['fa']['title']) ?></p></div>
      <?= str_replace('class="mark"', 'class="mark mk"', brand_mark()) ?>
    </div>
    <ul class="contacts">
      <li><?= icon('mobile') ?><span class="ltr"><?= e($fa($person['mobile'])) ?></span></li>
      <li><?= icon('building') ?><span class="ltr"><?= e($fa($person['office'])) ?></span></li>
      <li><?= icon('mail') ?><span class="ltr"><?= e($person['email']) ?></span></li>
      <li><?= icon('globe') ?><span class="ltr"><?= e($person['web']) ?></span></li>
      <li class="addr"><?= icon('pin') ?><span>تهران، جردن، بلوار آفریقا، خیابان دستگردی غربی، پلاک&nbsp;۲۶۹، طبقه‌ی&nbsp;۳</span></li>
    </ul>
    <div class="qr"><?= $svg('stationery/qr-vcard-fa.svg') ?><span>ذخیره‌ی شماره</span></div>
  </div>
</section>

<!-- Back, English -->
<section class="card back en" dir="ltr">
  <span class="band"></span>
  <div class="safe">
    <div class="who">
      <div><h1><?= e($person['en']['name']) ?></h1><p><?= e($person['en']['title']) ?></p></div>
      <?= str_replace('class="mark"', 'class="mark mk"', brand_mark()) ?>
    </div>
    <ul class="contacts">
      <li><?= icon('mobile') ?><span>+98 912 601 8213</span></li>
      <li><?= icon('building') ?><span>+98 21 8810 0050</span></li>
      <li><?= icon('mail') ?><span><?= e($person['email']) ?></span></li>
      <li><?= icon('globe') ?><span><?= e($person['web']) ?></span></li>
      <li class="addr"><?= icon('pin') ?><span>3rd Floor, No. 269, W. Dastgerdi St., Africa&nbsp;Blvd., Tehran,&nbsp;Iran</span></li>
    </ul>
    <div class="qr"><?= $svg('stationery/qr-vcard-en.svg') ?><span>Save contact</span></div>
  </div>
</section>

<script>
document.querySelectorAll('.front canvas').forEach(function (c) {
  var r = c.getBoundingClientRect(), d = 6; c.width = r.width * d; c.height = r.height * d;
  var x = c.getContext('2d'); x.scale(d, d);
  var W = r.width, H = r.height, s = +c.dataset.seed;
  function rnd() { s = (s * 9301 + 49297) % 233280; return s / 233280; }
  var pts = [];
  for (var i = 0; i < 26; i++) {
    var px = rnd() * W, py = rnd() * H;
    // keep the centre (logo) quiet
    var cx = Math.abs(px - W / 2) / (W / 2), cy = Math.abs(py - H / 2) / (H / 2);
    if ((cx < .62 && cy < .55) || (py > H * .74 && cx < .4)) { i--; continue; }
    pts.push({ x: px, y: py, r: .8 + rnd() * 2.6, k: i % 8 === 3 ? '#FF5A36' : i % 11 === 6 ? '#FFB547' : 'rgba(255,255,255,.3)' });
  }
  x.lineCap = 'round';
  pts.forEach(function (p, i) { pts.forEach(function (q, j) { if (j > i) { var dd = Math.hypot(p.x - q.x, p.y - q.y); if (dd < 70) { x.strokeStyle = 'rgba(255,255,255,' + (.14 * (1 - dd / 70)) + ')'; x.lineWidth = .7; x.beginPath(); x.moveTo(p.x, p.y); x.lineTo(q.x, q.y); x.stroke(); } } }); });
  pts.forEach(function (p) { x.fillStyle = p.k; x.beginPath(); x.arc(p.x, p.y, p.r, 0, 7); x.fill(); });
});
</script>
</body>
</html>
