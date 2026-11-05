<?php
/**
 * Staff page: preview the signatures, copy them, and follow the setup steps for each mail app.
 * php email-signature/install.php > website/email-signature.html
 */
declare(strict_types=1);
$sigFa = shell_exec('php ' . escapeshellarg(__DIR__ . '/signature.php') . ' fa');
$sigEn = shell_exec('php ' . escapeshellarg(__DIR__ . '/signature.php') . ' en');
?><!doctype html>
<html lang="fa" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>امضای ایمیل راستین</title>
<link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml">
<style>
@font-face{font-family:"Vazirmatn";src:url("/assets/fonts/Vazirmatn-Regular.woff2") format("woff2");font-weight:400}
@font-face{font-family:"Vazirmatn";src:url("/assets/fonts/Vazirmatn-Bold.woff2") format("woff2");font-weight:700}
@font-face{font-family:"Vazirmatn";src:url("/assets/fonts/Vazirmatn-Black.woff2") format("woff2");font-weight:900}
:root{--coral:#FF5A36;--coral-600:#E8461F;--coral-50:#FFF1EC;--ink:#1F1630;--muted:#6B6375;--line:#ECE6E9;--paper:#FBF8F7}
*{box-sizing:border-box}
body{margin:0;font-family:"Vazirmatn",Tahoma,sans-serif;background:var(--paper);color:var(--ink);line-height:1.9}
.wrap{max-width:900px;margin:0 auto;padding:40px max(16px,env(safe-area-inset-left,0px))}
h1{font-size:30px;font-weight:900;margin:0 0 6px}
h2{font-size:20px;font-weight:900;margin:0}
p{margin:0}
.muted{color:var(--muted)}
.card{background:#fff;border:1px solid var(--line);border-radius:16px;padding:24px;display:grid;gap:16px;margin-top:20px}
.sig{border:1px dashed #d8d0d6;border-radius:12px;padding:20px;overflow-x:auto;background:#fff}
.row{display:flex;gap:10px;flex-wrap:wrap;align-items:center}
button{font:inherit;font-weight:700;border:0;border-radius:10px;padding:10px 18px;cursor:pointer;background:var(--coral);color:#fff}
button.alt{background:#fff;color:var(--ink);border:1.5px solid var(--line)}
button:focus-visible{outline:3px solid rgba(255,90,54,.4);outline-offset:2px}
.ok{color:#1E7F5C;font-weight:700;font-size:14px}
textarea{width:100%;min-height:120px;font-family:ui-monospace,monospace;font-size:12px;direction:ltr;border:1px solid var(--line);border-radius:10px;padding:10px}
details{background:#fff;border:1px solid var(--line);border-radius:12px;padding:14px 18px;margin-top:10px}
summary{font-weight:700;cursor:pointer}
ol{margin:10px 0 0;padding-inline-start:22px;display:grid;gap:4px}
code{background:var(--coral-50);padding:1px 6px;border-radius:6px;direction:ltr;display:inline-block}
</style>
</head>
<body>
<div class="wrap">
  <h1>امضای ایمیل راستین</h1>
  <p class="muted">امضای مناسب را کپی کنید و طبق راهنمای پایین صفحه در برنامه‌ی ایمیل خود بچسبانید.</p>

  <?php foreach (['fa' => ['امضای فارسی', $sigFa], 'en' => ['امضای انگلیسی', $sigEn]] as $k => [$title, $sig]): ?>
  <section class="card">
    <h2><?= $title ?></h2>
    <div class="sig" id="sig-<?= $k ?>" dir="<?= $k === 'en' ? 'ltr' : 'rtl' ?>"><?= $sig ?></div>
    <div class="row">
      <button type="button" data-copy="sig-<?= $k ?>">کپی امضا</button>
      <button type="button" class="alt" data-html="sig-<?= $k ?>">نمایش کد HTML</button>
      <span class="ok" aria-live="polite"></span>
    </div>
    <textarea hidden readonly aria-label="HTML"></textarea>
  </section>
  <?php endforeach; ?>

  <section class="card">
    <h2>راهنمای نصب</h2>
    <details open><summary>جیمیل (Gmail)</summary><ol>
      <li>روی «کپی امضا» بزنید.</li>
      <li>در جیمیل: چرخ‌دنده ⚙ ← <b>See all settings</b> ← زبانه‌ی <b>General</b> ← بخش <b>Signature</b>.</li>
      <li><b>Create new</b> را بزنید، نامی بدهید و در کادر امضا <code>Ctrl+V</code> بزنید.</li>
      <li>پایین صفحه <b>Save Changes</b> را بزنید.</li>
    </ol></details>
    <details><summary>اوت‌لوک (Outlook) ویندوز</summary><ol>
      <li>روی «کپی امضا» بزنید.</li>
      <li>در اوت‌لوک: <b>File ← Options ← Mail ← Signatures</b>.</li>
      <li><b>New</b> را بزنید، نامی بدهید و در کادر ویرایش <code>Ctrl+V</code> بزنید.</li>
      <li>در «Choose default signature» امضا را برای پیام‌های جدید و پاسخ‌ها انتخاب کنید و <b>OK</b> بزنید.</li>
    </ol></details>
    <details><summary>اوت‌لوک وب (Outlook.com / Microsoft 365)</summary><ol>
      <li>تنظیمات ⚙ ← <b>Mail ← Compose and reply</b>.</li>
      <li>امضا را در کادر <b>Email signature</b> بچسبانید و <b>Save</b> بزنید.</li>
    </ol></details>
    <details><summary>راندکیوب (Roundcube) — ایمیل روی هاست</summary><ol>
      <li><b>تنظیمات ← هویت‌ها (Identities)</b> را باز کنید و هویت خود را انتخاب کنید.</li>
      <li>کنار کادر امضا، گزینه‌ی <b>امضای HTML</b> را فعال کنید.</li>
      <li>در نوار ابزار ویرایشگر دکمه‌ی <b>&lt;&gt; (Source code)</b> را بزنید، کد HTML را از دکمه‌ی «نمایش کد HTML» همین صفحه کپی و جایگزین کنید.</li>
      <li><b>ذخیره</b> را بزنید.</li>
    </ol></details>
    <details><summary>اپل‌میل (Apple Mail)</summary><ol>
      <li><b>Mail ← Settings ← Signatures</b> و با <b>+</b> امضای جدید بسازید.</li>
      <li>تیک <b>Always match my default message font</b> را بردارید و امضا را بچسبانید.</li>
    </ol></details>
    <p class="muted">تصویرهای امضا از سایت rastingroup.ir بارگذاری می‌شوند. اگر گیرنده تصاویر را نبیند، برنامه‌ی ایمیلش نمایش تصاویر را مسدود کرده است؛ متن امضا همچنان خوانا می‌ماند.</p>
  </section>
</div>
<script>
document.querySelectorAll('[data-copy]').forEach(function (b) {
  b.addEventListener('click', function () {
    var el = document.getElementById(b.dataset.copy), ok = b.parentNode.querySelector('.ok');
    var html = el.innerHTML;
    function done() { ok.textContent = 'کپی شد. حالا در تنظیمات امضا بچسبانید.'; }
    if (navigator.clipboard && window.ClipboardItem) {
      navigator.clipboard.write([new ClipboardItem({ 'text/html': new Blob([html], { type: 'text/html' }), 'text/plain': new Blob([el.innerText], { type: 'text/plain' }) })]).then(done, fallback);
    } else fallback();
    function fallback() {
      var r = document.createRange(); r.selectNodeContents(el);
      var s = getSelection(); s.removeAllRanges(); s.addRange(r);
      try { document.execCommand('copy'); done(); } catch (e) { ok.textContent = 'امضا انتخاب شد؛ Ctrl+C را بزنید.'; }
    }
  });
});
document.querySelectorAll('[data-html]').forEach(function (b) {
  b.addEventListener('click', function () {
    var ta = b.closest('.card').querySelector('textarea');
    ta.value = document.getElementById(b.dataset.html).innerHTML.trim();
    ta.hidden = !ta.hidden; if (!ta.hidden) ta.select();
  });
});
</script>
</body>
</html>
