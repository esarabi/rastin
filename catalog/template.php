<?php
/**
 * Rastin company catalog (Persian, A4, 8 pages).
 * Rendered by tools/build_catalog.sh:  php catalog/template.php [bleed_mm] > catalog/build/catalog.html
 * Reuses the website's icons and language file so the catalog and the site never drift apart.
 */
declare(strict_types=1);
require __DIR__ . '/../website/app/bootstrap.php';
current_lang('fa');

$bleed = (float) ($argv[1] ?? 0);           // 0 = digital PDF, 3 = print PDF
$R = '../..';                                // repo root, relative to catalog/build/
$h = (array) t('home');
$s = (array) t('solutions');
$r = (array) t('rastdesk');
$pr = (array) t('pricing');
$a = (array) t('about');
$fa = fn($x) => digits((string) $x, 'fa');
$logoRev = file_get_contents(__DIR__ . '/../brand/logo/rastin-horizontal-fa-reverse-transparent.svg');
$logoColor = file_get_contents(__DIR__ . '/../brand/logo/rastin-horizontal-fa-color.svg');
$qr = file_get_contents(__DIR__ . '/qr-rastingroup.svg');
$qr2 = file_get_contents(__DIR__ . '/qr-rastdesk.svg');
$strip = function (string $svg): string {
    $svg = preg_replace('/<\?xml[^>]*>/', '', $svg);
    if (strpos($svg, 'viewBox') === false && preg_match('/<svg[^>]* width="([\d.]+)" height="([\d.]+)"/', $svg, $m)) {
        $svg = preg_replace('/<svg /', '<svg viewBox="0 0 ' . $m[1] . ' ' . $m[2] . '" ', $svg, 1);
    }
    return preg_replace('/ width="[\d.]+" height="[\d.]+"/', '', $svg, 1);
};
$pages = 8;
$folio = function (int $n, bool $dark = false) use ($fa, $pages) {
    return '<div class="folio' . ($dark ? ' on-dark' : '') . '"><span>توسعه هوشمند راستین</span><span>' . $fa($n) . ' / ' . $fa($pages) . '</span></div>';
};
?><!doctype html>
<html lang="fa" dir="rtl">
<head>
<meta charset="utf-8">
<title>کاتالوگ توسعه هوشمند راستین</title>
<style>
@font-face{font-family:"Vazirmatn";src:url("<?= $R ?>/website/assets/fonts/Vazirmatn-Regular.woff2") format("woff2");font-weight:400}
@font-face{font-family:"Vazirmatn";src:url("<?= $R ?>/website/assets/fonts/Vazirmatn-Medium.woff2") format("woff2");font-weight:500}
@font-face{font-family:"Vazirmatn";src:url("<?= $R ?>/website/assets/fonts/Vazirmatn-Bold.woff2") format("woff2");font-weight:700}
@font-face{font-family:"Vazirmatn";src:url("<?= $R ?>/website/assets/fonts/Vazirmatn-ExtraBold.woff2") format("woff2");font-weight:800}
@font-face{font-family:"Vazirmatn";src:url("<?= $R ?>/website/assets/fonts/Vazirmatn-Black.woff2") format("woff2");font-weight:900}
:root{
  --b:<?= $bleed ?>mm;
  --coral:#FF5A36; --coral-600:#E8461F; --coral-50:#FFF1EC; --coral-100:#FFE1D7; --amber:#FFB547;
  --ink:#1F1630; --ink-2:#2C2140; --ink-3:#3A2E4F; --paper:#FBF8F7; --line:#ECE6E9; --muted:#6B6375; --text:#2A2236;
}
@page{size:calc(210mm + 2 * var(--b)) calc(297mm + 2 * var(--b));margin:0}
@page{size:<?= 210 + 2 * $bleed ?>mm <?= 297 + 2 * $bleed ?>mm;margin:0}
*{box-sizing:border-box;-webkit-print-color-adjust:exact;print-color-adjust:exact}
html,body{margin:0;padding:0}
body{font-family:"Vazirmatn",Tahoma,sans-serif;color:var(--text);font-size:9.6pt;line-height:1.85;background:#888}
h1,h2,h3,h4{margin:0;color:var(--ink);line-height:1.4}
p{margin:0}
ul,ol{margin:0;padding:0;list-style:none}
/* sheet = trimmed page + bleed on every side; backgrounds fill the sheet, content stays in the safe area */
.sheet{position:relative;width:calc(210mm + 2 * var(--b));height:calc(297mm + 2 * var(--b));overflow:hidden;background:#fff;page-break-after:always;break-after:page}
.sheet:last-child{page-break-after:auto;break-after:auto}
@media screen{body{display:grid;gap:10mm;justify-content:center;padding:10mm 0}.sheet{box-shadow:0 4px 24px rgba(0,0,0,.25)}}
.page{position:absolute;inset:var(--b);padding:18mm 17mm 20mm;display:flex;flex-direction:column;gap:7mm}
.ico{width:5.5mm;height:5.5mm;flex:none}
.eyebrow{font-size:8.5pt;font-weight:800;color:var(--coral);letter-spacing:.02em}
.h1{font-size:25pt;font-weight:900;line-height:1.35}
.lead{font-size:11pt;color:var(--muted);line-height:1.9}
.folio{position:absolute;inset-inline:17mm;bottom:9mm;display:flex;justify-content:space-between;font-size:7.5pt;color:var(--muted);border-top:.3mm solid var(--line);padding-top:2.5mm}
.folio.on-dark{color:#b9b0c4;border-color:var(--ink-3)}
.badge{width:11mm;height:11mm;border-radius:3.2mm;background:var(--coral-50);color:var(--coral-600);display:grid;place-items:center;flex:none}
.badge .ico{width:6mm;height:6mm}
.net{position:absolute;inset:0;width:100%;height:100%}
.check{display:flex;gap:2mm;align-items:flex-start}
.check .ico{width:4.2mm;height:4.2mm;color:var(--coral);margin-top:1mm}

/* ---- 1. cover ---- */
.cover{background:var(--ink)}
.cover .page{padding:20mm 18mm 22mm;justify-content:space-between}
.cover-logo svg{height:20mm;width:auto}
.cover-title{display:grid;gap:6mm;position:relative}
.cover-title .kicker{color:var(--coral);font-weight:800;font-size:11pt}
.cover-title h1{color:#fff;font-size:44pt;font-weight:900;line-height:1.3}
.cover-title h1 em{font-style:normal;color:var(--coral)}
.cover-title p{color:#c9c1d2;font-size:13pt;max-width:120mm}
.cover-foot{display:flex;justify-content:space-between;align-items:end;color:#c9c1d2;font-size:9pt;position:relative}
.cover-foot b{display:block;color:#fff;font-size:11pt}
.cover-mark{--mk-ink:rgba(255,255,255,.92);position:absolute;width:120mm;height:120mm;left:calc(var(--b) - 34mm);top:calc(var(--b) + 78mm);opacity:.95}

/* ---- 2. about ---- */
.facts{display:grid;grid-template-columns:repeat(3,1fr);gap:5mm}
.facts div{border-inline-start:1.2mm solid var(--coral);padding-inline-start:4mm;display:grid}
.facts b{font-size:18pt;font-weight:900;color:var(--ink);line-height:1.4}
.facts span{color:var(--muted);font-size:8.5pt}
.about-cols{display:grid;grid-template-columns:1.25fr 1fr;gap:8mm;align-items:start}
.prose{display:grid;gap:3.5mm;font-size:10pt}
.mission{background:var(--coral);color:#fff;border-radius:5mm;padding:8mm;position:relative;overflow:hidden;--mk-ink:#fff;--mk-b:var(--ink);--mk-b2:var(--amber)}
.mission h3{color:#fff;font-size:13pt;font-weight:900;margin-bottom:2mm}
.mission p{font-size:11pt;font-weight:500;line-height:1.9}
.mission .mark{position:absolute;width:40mm;height:40mm;left:-8mm;bottom:-10mm;opacity:.18}
.values{display:grid;grid-template-columns:repeat(4,1fr);gap:4mm}
.values div{background:var(--paper);border-radius:4mm;padding:5mm;display:grid;gap:1.5mm;align-content:start}
.values h4{font-size:11pt;font-weight:800}
.values p{color:var(--muted);font-size:8.5pt;line-height:1.75}
.serve{background:var(--ink);border-radius:5mm;padding:6mm 7mm;display:grid;gap:3mm;margin-top:auto}
.serve h3{color:#fff;font-size:11pt}
.serve ul{display:flex;flex-wrap:wrap;gap:2mm}
.serve li{background:var(--ink-2);border:.3mm solid var(--ink-3);color:#fff;border-radius:99px;padding:1mm 4mm;font-size:8.5pt;font-weight:500}

/* ---- 3. solutions ---- */
.sol-grid{display:grid;grid-template-columns:1fr 1fr;gap:5mm;flex:1}
.sol{border:.3mm solid var(--line);border-radius:5mm;padding:7mm;display:flex;flex-direction:column;gap:3mm}
.sol h3{font-size:13pt;font-weight:900}
.sol .lead2{color:var(--muted);font-size:9.5pt}
.sol ul{display:grid;gap:1.6mm;margin-top:auto;padding-top:3mm;border-top:.3mm dashed var(--line)}
.sol li{font-size:9pt;font-weight:500;color:var(--ink)}
.sol.hl{background:var(--coral-50);border-color:var(--coral-100)}

/* ---- 4. rastdesk ---- */
.rd-head{background:var(--ink);margin:calc(-18mm) -17mm 0;padding:16mm 17mm 9mm;position:relative;overflow:hidden;display:grid;gap:3mm}
.rd-head > :not(.net){position:relative}
.rd-head .h1{color:#fff}
.rd-head .lead{color:#c9c1d2;max-width:150mm}
.rd-stats{display:flex;gap:12mm;margin-top:3mm;padding-top:4mm;border-top:.3mm solid var(--ink-3)}
.rd-stats b{display:block;color:#fff;font-size:17pt;font-weight:900;line-height:1.3}
.rd-stats span{color:#c9c1d2;font-size:8.5pt}
.mods{display:grid;grid-template-columns:repeat(3,1fr);gap:4mm}
.mod{background:var(--paper);border-radius:4mm;padding:5mm;display:grid;gap:2.5mm;align-content:start}
.mod h4{display:flex;align-items:center;gap:2.5mm;font-size:10.5pt;font-weight:800}
.mod h4 .badge{width:8mm;height:8mm;border-radius:2.5mm;background:#fff}
.mod h4 .badge .ico{width:4.5mm;height:4.5mm}
.mod ul{display:grid;gap:1mm}
.mod li{font-size:8.6pt;color:var(--ink)}
.mod li .ico{width:3.6mm;height:3.6mm;margin-top:1.2mm}
.why4{display:grid;grid-template-columns:repeat(4,1fr);gap:4mm}
.why4 div{display:grid;gap:1.2mm;align-content:start}
.why4 .ico{width:6mm;height:6mm;color:var(--coral)}
.why4 h4{font-size:10pt;font-weight:800}
.why4 p{font-size:8.3pt;color:var(--muted);line-height:1.7}

/* ---- 5. editions ---- */
.eds{display:grid;grid-template-columns:repeat(4,1fr);gap:3.5mm}
.ed{border:.3mm solid var(--line);border-radius:4mm;padding:5mm 4.5mm;display:flex;flex-direction:column;gap:2mm;position:relative}
.ed h3{font-size:15pt;font-weight:900}
.ed .ed-lead{font-size:8.2pt;color:var(--muted);line-height:1.6;min-height:10mm}
.ed ul{display:grid;gap:1.3mm;padding-top:2.5mm;border-top:.3mm solid var(--line)}
.ed li{font-size:8.3pt;color:var(--ink);line-height:1.6}
.ed li .ico{width:3.6mm;height:3.6mm;margin-top:1mm}
.ed.feat{border:.6mm solid var(--coral);box-shadow:0 2mm 6mm rgba(255,90,54,.15)}
.ed .flag{position:absolute;top:-3mm;inset-inline-start:4mm;background:var(--coral);color:#fff;font-size:7pt;font-weight:800;border-radius:99px;padding:.3mm 3mm}
.note{display:flex;gap:3mm;align-items:center;background:var(--coral-50);border-radius:4mm;padding:4mm 5mm;font-size:9pt;color:var(--ink)}
.note .ico{color:var(--coral)}
.steps{display:grid;grid-template-columns:repeat(3,1fr);gap:5mm;position:relative}
.steps li{display:grid;gap:1.5mm;align-content:start;position:relative}
.steps .n{width:10mm;height:10mm;border-radius:50%;background:var(--ink);color:#fff;display:grid;place-items:center;font-weight:900;font-size:11pt;position:relative;z-index:1}
.steps li:last-child .n{background:var(--coral)}
.steps li:not(:last-child)::after{content:"";position:absolute;top:5mm;inset-inline-start:11mm;inset-inline-end:-4mm;border-top:.6mm dashed var(--line)}
.steps h4{font-size:10.5pt;font-weight:800}
.steps p{font-size:8.6pt;color:var(--muted);line-height:1.7}
.deploy{display:grid;grid-template-columns:1fr 1fr;gap:5mm}
.deploy div{background:var(--paper);border-radius:4mm;padding:6mm;display:grid;gap:2mm;align-content:start}
.deploy h4{display:flex;align-items:center;gap:3mm;font-size:11pt;font-weight:800}
.deploy p{font-size:9pt;color:var(--muted)}
.more-link{display:flex;justify-content:space-between;align-items:center;gap:5mm;border:.3mm solid var(--line);border-radius:4mm;padding:4mm 5mm;margin-top:auto}
.more-link b{font-size:10.5pt;color:var(--ink)}
.more-link span{font-size:8.5pt;color:var(--muted);display:block}
.more-link svg{width:20mm;height:20mm}
.more-link a{color:var(--coral-600);text-decoration:none;font-weight:800;direction:ltr}

/* ---- 6. speech + ai ---- */
.split{display:grid;grid-template-columns:1.1fr .9fr;gap:8mm;align-items:center}
.pipe{display:grid;gap:3mm}
.pipe li{display:grid;grid-template-columns:auto 1fr;gap:1mm 4mm;align-items:start}
.pipe .n{grid-row:span 2;width:9mm;height:9mm;border-radius:50%;background:var(--ink);color:#fff;display:grid;place-items:center;font-weight:900;font-size:10pt}
.pipe li:last-child .n{background:var(--coral)}
.pipe h4{font-size:10.5pt;font-weight:800}
.pipe p{font-size:8.6pt;color:var(--muted);line-height:1.7}
.report{background:var(--ink);border-radius:5mm;padding:6mm;display:grid;gap:4mm;position:relative;overflow:hidden}
.report-card{background:#fff;border-radius:4mm;padding:5mm;display:grid;gap:3.5mm;position:relative}
.rc-head{display:flex;justify-content:space-between;align-items:center;font-weight:800;font-size:9.5pt;color:var(--ink)}
.rc-head em{font-style:normal;font-size:6.5pt;color:var(--muted);border:.3mm solid var(--line);border-radius:99px;padding:0 2mm}
.rc-stats{display:grid;grid-template-columns:1fr 1fr;gap:2.5mm}
.rc-stats div{background:var(--coral-50);border-radius:3mm;padding:2.5mm 3mm}
.rc-stats b{display:block;font-size:15pt;font-weight:900;color:var(--ink);line-height:1.3}
.rc-stats small{font-size:7pt;color:var(--muted)}
.wave{display:flex;align-items:center;gap:.6mm;height:12mm;direction:ltr}
.wave i{flex:1;background:var(--coral);border-radius:.6mm}
.wave i:nth-child(3n){background:var(--ink);opacity:.25}
.bars{display:grid;gap:2mm}
.bars small{font-size:7pt;color:var(--muted);font-weight:700}
.bar{display:grid;grid-template-columns:22mm 1fr;gap:2mm;align-items:center;font-size:7.5pt}
.bar i{height:2mm;border-radius:99px;background:linear-gradient(90deg,var(--amber),var(--coral))}
.ai-box{background:var(--paper);border-radius:5mm;padding:7mm;display:grid;grid-template-columns:auto 1fr;gap:5mm;align-items:start}
.ai-box h3{font-size:13pt;font-weight:900;margin-bottom:1.5mm}
.ai-box .lead2{font-size:9.5pt;color:var(--muted);margin-bottom:3mm}
.ai-box ul{display:grid;grid-template-columns:1fr 1fr;gap:1.6mm 5mm}
.ai-box li{font-size:9pt;font-weight:500;color:var(--ink)}
.privacy{display:flex;gap:4mm;align-items:center;border:.3mm solid var(--coral-100);background:#fff;border-radius:4mm;padding:4mm 5mm;font-size:9pt;color:var(--ink)}
.privacy .ico{color:var(--coral);width:7mm;height:7mm}

/* ---- 7. custom software + industries ---- */
.proc{display:grid;grid-template-columns:repeat(4,1fr);gap:4mm}
.proc li{display:grid;gap:1.5mm;align-content:start;position:relative;padding-top:3mm;border-top:.9mm solid var(--line)}
.proc li:first-child{border-color:var(--coral)}
.proc .n{font-size:8pt;font-weight:900;color:var(--coral)}
.proc h4{font-size:10.5pt;font-weight:800}
.proc p{font-size:8.5pt;color:var(--muted);line-height:1.7}
.inds{display:grid;grid-template-columns:1fr 1fr;gap:4mm}
.ind{display:flex;gap:4mm;align-items:flex-start;border:.3mm solid var(--line);border-radius:4mm;padding:5mm}
.ind h4{font-size:10.5pt;font-weight:800;margin-bottom:1mm}
.ind p{font-size:8.6pt;color:var(--muted);line-height:1.7}
.why-dark{background:var(--ink);border-radius:5mm;padding:7mm;display:grid;gap:5mm;margin-top:auto}
.why-dark h3{color:#fff;font-size:13pt;font-weight:900}
.why-dark .grid{display:grid;grid-template-columns:repeat(4,1fr);gap:4mm}
.why-dark .grid div{display:grid;gap:1.2mm;align-content:start}
.why-dark .ico{color:var(--coral);width:6mm;height:6mm}
.why-dark h4{color:#fff;font-size:9.5pt;font-weight:800}
.why-dark p{color:#c9c1d2;font-size:8pt;line-height:1.7}

/* ---- 8. back cover ---- */
.back{background:var(--coral)}
.back .page{justify-content:space-between;padding:22mm 18mm}
.back h2{color:#fff;font-size:32pt;font-weight:900;line-height:1.35}
.back .lead{color:#fff;opacity:.92;max-width:130mm;margin-top:5mm}
.contact-card{background:#fff;border-radius:6mm;padding:9mm;display:grid;grid-template-columns:1fr auto;gap:8mm;align-items:center}
.contact-card ul{display:grid;gap:4mm}
.contact-card li{display:flex;gap:3.5mm;align-items:flex-start;font-size:10.5pt;color:var(--ink);font-weight:500}
.contact-card li .ico{color:var(--coral);width:6mm;height:6mm;margin-top:.6mm}
.contact-card li small{display:block;color:var(--muted);font-size:8pt;font-weight:700;line-height:1.4}
.contact-card a{color:var(--ink);text-decoration:none}
.qr{display:grid;justify-items:center;gap:2mm;font-size:8pt;color:var(--muted);text-align:center}
.qr svg{width:30mm;height:30mm}
.back-foot{display:flex;justify-content:space-between;align-items:end;color:#fff}
.back-foot svg{height:17mm;width:auto}
.back-foot .tag{font-weight:800;font-size:11pt}
.back .bg-ring{position:absolute;width:150mm;height:150mm;border-radius:50%;background:rgba(255,255,255,.08);left:-50mm;top:-50mm}
.back .bg-ring.r2{width:90mm;height:90mm;left:auto;right:-30mm;top:auto;bottom:40mm}
</style>
</head>
<body>

<!-- 1. Cover -->
<section class="sheet cover">
  <canvas class="net" data-seed="7" data-density="1"></canvas>
  <?= str_replace('class="mark"', 'class="mark cover-mark"', brand_mark()) ?>
  <div class="page">
    <div class="cover-logo"><?= $strip($logoRev) ?></div>
    <div class="cover-title">
      <span class="kicker">معرفی شرکت · ۱۴۰۵</span>
      <h1>توسعه هوشمند،<br><em>آینده مطمئن</em></h1>
      <p>نرم‌افزار سازمانی و هوش مصنوعی برای سازمان‌ها و کسب‌وکارهای ایرانی؛ از راست‌دسک تا تحلیل صوت مکالمات.</p>
    </div>
    <div class="cover-foot">
      <div><b>شرکت توسعه هوشمند راستین</b>Rastin Intelligent Development</div>
      <div dir="ltr">rastingroup.ir</div>
    </div>
  </div>
</section>

<!-- 2. About -->
<section class="sheet">
  <div class="page">
    <div style="display:grid;gap:3mm">
      <span class="eyebrow"><?= e($a['eyebrow']) ?></span>
      <h1 class="h1"><?= e($a['heading']) ?></h1>
      <p class="lead"><?= e($a['sub']) ?></p>
    </div>
    <div class="facts"><?php foreach ($a['facts'] as $f): ?><div><b><?= e($f['value']) ?></b><span><?= e($f['label']) ?></span></div><?php endforeach; ?></div>
    <div class="about-cols">
      <div class="prose">
        <h3 style="font-size:13pt;font-weight:900"><?= e($a['story_title']) ?></h3>
        <?php foreach ($a['story'] as $para): ?><p><?= e($para) ?></p><?php endforeach; ?>
      </div>
      <div class="mission"><?= brand_mark() ?><h3><?= e($a['mission_title']) ?></h3><p><?= e($a['mission']) ?></p></div>
    </div>
    <div style="display:grid;gap:4mm">
      <h3 style="font-size:13pt;font-weight:900"><?= e($a['values_title']) ?></h3>
      <div class="values"><?php foreach ($a['values'] as $v): ?><div><h4><?= e($v['title']) ?></h4><p><?= e($v['text']) ?></p></div><?php endforeach; ?></div>
    </div>
    <div class="serve">
      <h3><?= e($h['industries_title']) ?></h3>
      <ul><?php foreach ($h['industries'] as $ind): ?><li><?= e($ind) ?></li><?php endforeach; ?></ul>
    </div>
  </div>
  <?= $folio(2) ?>
</section>

<!-- 3. Solutions -->
<section class="sheet">
  <div class="page">
    <div style="display:grid;gap:3mm">
      <span class="eyebrow"><?= e($h['services_eyebrow']) ?></span>
      <h1 class="h1"><?= e($h['services_title']) ?></h1>
      <p class="lead"><?= e($s['sub']) ?></p>
    </div>
    <div class="sol-grid">
      <?php foreach ($s['items'] as $i => $it): ?>
        <div class="sol<?= $i === 0 ? ' hl' : '' ?>">
          <span class="badge"><?= icon($it['icon']) ?></span>
          <h3><?= e($it['title']) ?></h3>
          <p class="lead2"><?= e($it['lead']) ?></p>
          <ul><?php foreach ($it['points'] as $pt): ?><li class="check"><?= icon('check') ?><span><?= e($pt) ?></span></li><?php endforeach; ?></ul>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?= $folio(3) ?>
</section>

<!-- 4. RastDesk -->
<section class="sheet">
  <div class="page">
    <div class="rd-head">
      <canvas class="net" data-seed="21" data-density=".7"></canvas>
      <span class="eyebrow"><?= e($h['rastdesk_eyebrow']) ?> · <?= e($r['eyebrow']) ?></span>
      <h1 class="h1">راست‌دسک؛ <?= e($r['heading']) ?></h1>
      <p class="lead"><?= e($r['sub']) ?></p>
      <div class="rd-stats"><?php foreach ($r['stats'] as $st): ?><div><b><?= e($st['value']) ?></b><span><?= e($st['label']) ?></span></div><?php endforeach; ?></div>
    </div>
    <div class="why4">
      <?php foreach ($r['why'] as $w): ?><div><?= icon($w['icon']) ?><h4><?= e($w['title']) ?></h4><p><?= e($w['text']) ?></p></div><?php endforeach; ?>
    </div>
    <div style="display:grid;gap:4mm">
      <h2 style="font-size:15pt;font-weight:900"><?= e($r['modules_title']) ?></h2>
      <div class="mods">
        <?php foreach ($r['groups'] as $g): ?>
          <div class="mod">
            <h4><span class="badge"><?= icon($g['icon']) ?></span><?= e($g['title']) ?></h4>
            <ul><?php foreach ($g['items'] as $it): ?><li class="check"><?= icon('check') ?><span><?= e($it) ?></span></li><?php endforeach; ?></ul>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <?= $folio(4) ?>
</section>

<!-- 5. RastDesk editions, onboarding, deployment -->
<section class="sheet">
  <div class="page">
    <div style="display:grid;gap:3mm">
      <span class="eyebrow">نسخه‌های راست‌دسک</span>
      <h1 class="h1"><?= e($pr['heading']) ?></h1>
      <p class="lead">هر نسخه شامل تمام ماژول‌های نسخه‌ی پایین‌تر است و با لایسنس دائم ارائه می‌شود. هر ماژول را هم می‌توانید جداگانه به نسخه‌ی خود اضافه کنید.</p>
    </div>
    <div class="eds">
      <?php foreach ($pr['editions'] as $ed): ?>
        <div class="ed<?= $ed['featured'] ? ' feat' : '' ?>">
          <?php if ($ed['featured']): ?><span class="flag"><?= e($pr['featured_label']) ?></span><?php endif; ?>
          <h3><?= e($ed['name']) ?></h3>
          <p class="ed-lead"><?= e($ed['lead']) ?></p>
          <ul><?php foreach ($ed['points'] as $pt): ?><li class="check"><?= icon('check') ?><span><?= e($pt) ?></span></li><?php endforeach; ?></ul>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="note"><?= icon('check') ?><span><?= e($pr['included']) ?></span></div>
    <div style="display:grid;gap:5mm">
      <div style="display:grid;gap:2mm">
        <h2 style="font-size:15pt;font-weight:900"><?= e($r['onboard_title']) ?></h2>
        <p style="color:var(--muted);font-size:9.5pt"><?= e($r['onboard_sub']) ?></p>
      </div>
      <ol class="steps"><?php foreach ($r['onboard'] as $i => $st): ?><li><span class="n"><?= $fa($i + 1) ?></span><h4><?= e($st['title']) ?></h4><p><?= e($st['text']) ?></p></li><?php endforeach; ?></ol>
    </div>
    <div class="more-link">
      <div><b>قیمت و مقایسه‌ی کامل نسخه‌ها</b><span>برای دیدن قیمت روز، جزئیات ماژول‌ها و درخواست دمو، کد را اسکن کنید یا به سایت راست‌دسک بروید.</span><a href="https://rastdesk.ir">rastdesk.ir</a></div>
      <?= $strip($qr2) ?>
    </div>
  </div>
  <?= $folio(5) ?>
</section>

<!-- 6. Speech analytics + on-prem AI -->
<?php $sp = $s['items'][3]; $ai = $s['items'][2]; ?>
<section class="sheet">
  <div class="page">
    <div style="display:grid;gap:3mm">
      <span class="eyebrow"><?= e($h['speech_eyebrow']) ?></span>
      <h1 class="h1"><?= e($h['speech_title']) ?></h1>
      <p class="lead"><?= e($h['speech_text']) ?> <?= e($sp['lead']) ?></p>
    </div>
    <div class="split">
      <ol class="pipe">
        <?php foreach ($h['speech_steps'] as $i => $st): ?><li><span class="n"><?= $fa($i + 1) ?></span><h4><?= e($st['title']) ?></h4><p><?= e($st['text']) ?></p></li><?php endforeach; ?>
      </ol>
      <div class="report">
        <canvas class="net" data-seed="5" data-density=".5"></canvas>
        <div class="report-card">
          <div class="rc-head"><span><?= e($h['mock_title']) ?></span><em><?= e($h['mock_note']) ?></em></div>
          <div class="rc-stats"><div><b><?= $fa('1,284') ?></b><small><?= e($h['mock_calls']) ?></small></div><div><b><?= $fa('87%') ?></b><small><?= e($h['mock_satisfaction']) ?></small></div></div>
          <div class="wave"><?php for ($i = 0; $i < 34; $i++): $v = 18 + (int) (abs(sin($i * 0.7) * cos($i * 0.23)) * 70); ?><i style="height:<?= $v ?>%"></i><?php endfor; ?></div>
          <div class="bars"><small><?= e($h['mock_topics']) ?></small><?php foreach ($h['mock_topic_list'] as $k => $tp): ?><div class="bar"><span><?= e($tp) ?></span><i style="width:<?= [78, 54, 33][$k] ?>%"></i></div><?php endforeach; ?></div>
        </div>
      </div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:3mm">
      <?php foreach ($sp['points'] as $pt): ?><div class="check" style="background:var(--paper);border-radius:3mm;padding:3mm 3.5mm;font-size:8.8pt;font-weight:500;color:var(--ink)"><?= icon('check') ?><span><?= e($pt) ?></span></div><?php endforeach; ?>
    </div>
    <div class="ai-box">
      <span class="badge"><?= icon('ai') ?></span>
      <div>
        <h3><?= e($ai['title']) ?></h3>
        <p class="lead2"><?= e($ai['lead']) ?></p>
        <ul><?php foreach ($ai['points'] as $pt): ?><li class="check"><?= icon('check') ?><span><?= e($pt) ?></span></li><?php endforeach; ?></ul>
      </div>
    </div>
    <div class="privacy"><?= icon('shield') ?><span><b>داده‌ها در اختیار شما.</b> تحلیل صوت و مدل‌های هوش مصنوعی را می‌توانیم کاملاً درون شبکه‌ی سازمان شما اجرا کنیم، بدون اینکه هیچ فایل یا متنی از سازمان خارج شود.</span></div>
  </div>
  <?= $folio(6) ?>
</section>

<!-- 7. Custom software + industries -->
<?php $cs = $s['items'][1]; ?>
<section class="sheet">
  <div class="page">
    <div style="display:grid;gap:3mm">
      <span class="eyebrow">توسعه‌ی نرم‌افزار سفارشی</span>
      <h1 class="h1">نرم‌افزاری که دقیقاً برای کار شما ساخته می‌شود</h1>
      <p class="lead"><?= e($cs['lead']) ?> <?= e($h['services'][1]['text']) ?></p>
    </div>
    <ol class="proc">
      <li><span class="n">۰۱</span><h4>بررسی نیاز</h4><p>جلسه با تیم‌های شما، تحلیل فرایندها و تعریف دقیق مسئله.</p></li>
      <li><span class="n">۰۲</span><h4>طراحی راهکار</h4><p>معماری، طراحی تجربه‌ی کاربری و پیشنهاد فنی و مالی با زمان‌بندی مشخص.</p></li>
      <li><span class="n">۰۳</span><h4>توسعه و یکپارچه‌سازی</h4><p>ساخت نسخه‌های وب و موبایل و اتصال به سامانه‌های فعلی سازمان.</p></li>
      <li><span class="n">۰۴</span><h4>استقرار و پشتیبانی</h4><p>نصب، آموزش تیم‌ها و پشتیبانی و توسعه‌ی مستمر پس از تحویل.</p></li>
    </ol>
    <div style="display:grid;gap:4mm">
      <h2 style="font-size:15pt;font-weight:900"><?= e($s['industries_title']) ?></h2>
      <div class="inds">
        <?php foreach ($s['industries'] as $i => $ind): ?>
          <div class="ind"><span class="badge"><?= icon(['shield', 'flow', 'map', 'crm'][$i] ?? 'check') ?></span><div><h4><?= e($ind['title']) ?></h4><p><?= e($ind['text']) ?></p></div></div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="why-dark">
      <h3><?= e($h['why_title']) ?></h3>
      <div class="grid">
        <?php foreach ($h['why'] as $i => $w): ?><div><?= icon(['shield', 'lang', 'puzzle', 'map'][$i]) ?><h4><?= e($w['title']) ?></h4><p><?= e($w['text']) ?></p></div><?php endforeach; ?>
      </div>
    </div>
  </div>
  <?= $folio(7) ?>
</section>

<!-- 8. Back cover -->
<section class="sheet back">
  <span class="bg-ring"></span><span class="bg-ring r2"></span>
  <div class="page">
    <div>
      <h2><?= e($h['cta_title']) ?></h2>
      <p class="lead"><?= e($h['cta_text']) ?></p>
    </div>
    <div class="contact-card">
      <ul>
        <li><?= icon('phone') ?><div><small>تلفن</small><a href="tel:+982188100050" dir="ltr">۰۲۱-۸۸۱۰۰۰۵۰</a></div></li>
        <li><?= icon('mail') ?><div><small>ایمیل</small><a href="mailto:info@rastingroup.ir" dir="ltr">info@rastingroup.ir</a></div></li>
        <li><?= icon('globe') ?><div><small>وب‌سایت</small><a href="https://rastingroup.ir" dir="ltr">rastingroup.ir</a> · <a href="https://rastdesk.ir" dir="ltr">rastdesk.ir</a></div></li>
        <li><?= icon('instagram') ?><div><small>اینستاگرام</small><a href="https://instagram.com/rastdesk" dir="ltr">@rastdesk</a></div></li>
        <li><?= icon('pin') ?><div><small>نشانی</small><?= e(t('defaults.address')) ?></div></li>
      </ul>
      <div class="qr"><?= $strip($qr) ?><span>درخواست دمو و مشاوره<br>در rastingroup.ir</span></div>
    </div>
    <div class="back-foot">
      <div style="--mk-ink:#fff"><?= preg_replace('/<rect width="[\d.]+" height="[\d.]+" fill="#[0-9A-Fa-f]+"\/>/', '', $strip(file_get_contents(__DIR__ . '/../brand/logo/rastin-horizontal-fa-mono-white.svg')), 1) ?></div>
      <span class="tag"><?= e(t('brand.tagline')) ?></span>
    </div>
  </div>
</section>

<script>
// Node-network texture from the brand book (deterministic, so every export looks the same).
document.querySelectorAll('canvas.net').forEach(function (c) {
  var r = c.getBoundingClientRect(), d = 3;
  c.width = r.width * d; c.height = r.height * d;
  var x = c.getContext('2d'); x.scale(d, d);
  var W = r.width, H = r.height, s = +c.dataset.seed || 7, den = +c.dataset.density || 1;
  function rnd() { s = (s * 9301 + 49297) % 233280; return s / 233280; }
  var n = Math.round((W * H) / 9000 * den), pts = [];
  for (var i = 0; i < n; i++) { var py = rnd() * H; pts.push({ x: rnd() * W, y: py, r: 1.5 + rnd() * 5 * (0.35 + (1 - py / H) * .65), k: i % 9 === 4 ? '#FF5A36' : i % 13 === 7 ? '#FFB547' : 'rgba(255,255,255,.32)' }); }
  x.lineCap = 'round';
  pts.forEach(function (p, i) { pts.forEach(function (q, j) { if (j > i) { var dd = Math.hypot(p.x - q.x, p.y - q.y); if (dd < 110) { x.strokeStyle = 'rgba(255,255,255,' + (0.13 * (1 - dd / 110)) + ')'; x.lineWidth = 1.1; x.beginPath(); x.moveTo(p.x, p.y); x.lineTo(q.x, q.y); x.stroke(); } } }); });
  pts.forEach(function (p) { x.fillStyle = p.k; x.beginPath(); x.arc(p.x, p.y, p.r, 0, 7); x.fill(); });
});
</script>
</body>
</html>
