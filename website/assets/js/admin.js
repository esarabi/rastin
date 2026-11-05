// Rastin admin: confirm dialogs, settings tabs, and a small rich-text editor for blog posts.
(function () {
  'use strict';

  // In-page confirmation for destructive forms (two clicks instead of a browser dialog).
  document.querySelectorAll('form[data-confirm]').forEach(function (f) {
    f.addEventListener('submit', function (e) {
      if (f.dataset.armed === '1') return;
      e.preventDefault();
      f.dataset.armed = '1';
      var btn = f.querySelector('button[type=submit]');
      var original = btn.textContent;
      btn.textContent = f.dataset.confirm + ' — برای تأیید دوباره بزنید';
      setTimeout(function () { f.dataset.armed = ''; btn.textContent = original; }, 5000);
    });
  });

  // Settings language tabs
  var tabs = document.querySelectorAll('.tabs [data-tab]');
  tabs.forEach(function (t) {
    t.addEventListener('click', function () {
      tabs.forEach(function (x) { x.setAttribute('aria-selected', x === t ? 'true' : 'false'); });
      document.querySelectorAll('.tab-panel').forEach(function (p) { p.hidden = p.dataset.panel !== t.dataset.tab; });
    });
  });

  // ----- editor -----
  var editor = document.querySelector('.editor');
  if (!editor) return;
  var content = editor.querySelector('.content');
  var src = editor.querySelector('.html-src');
  var body = document.getElementById('body');
  var status = editor.querySelector('.editor-status');
  var linkBar = editor.querySelector('.link-bar');
  var linkInput = linkBar.querySelector('input');
  var imgInput = editor.querySelector('.img-input');
  var htmlMode = false;
  var savedRange = null;

  try { document.execCommand('defaultParagraphSeparator', false, 'p'); } catch (e) {}

  function saveSel() {
    var s = window.getSelection();
    if (s.rangeCount && content.contains(s.anchorNode)) savedRange = s.getRangeAt(0).cloneRange();
  }
  function restoreSel() {
    content.focus();
    if (savedRange) { var s = window.getSelection(); s.removeAllRanges(); s.addRange(savedRange); }
  }
  content.addEventListener('keyup', saveSel);
  content.addEventListener('mouseup', saveSel);

  editor.querySelector('.toolbar').addEventListener('click', function (e) {
    var b = e.target.closest('button');
    if (!b) return;
    if (b.dataset.cmd) {
      if (htmlMode) return;
      restoreSel();
      var val = b.dataset.val ? '<' + b.dataset.val + '>' : null;
      document.execCommand(b.dataset.cmd, false, val);
      saveSel();
      return;
    }
    if (b.dataset.act === 'link') { if (htmlMode) return; saveSel(); linkBar.hidden = false; linkInput.value = ''; linkInput.focus(); }
    if (b.dataset.act === 'image') { if (htmlMode) return; saveSel(); imgInput.click(); }
    if (b.dataset.act === 'html') {
      htmlMode = !htmlMode;
      b.setAttribute('aria-pressed', String(htmlMode));
      if (htmlMode) { src.value = content.innerHTML; src.hidden = false; content.hidden = true; }
      else { content.innerHTML = src.value; src.hidden = true; content.hidden = false; }
    }
  });

  linkBar.addEventListener('click', function (e) {
    var b = e.target.closest('button');
    if (!b) return;
    if (b.dataset.act === 'link-ok' && linkInput.value.trim()) {
      restoreSel();
      var url = linkInput.value.trim();
      if (!/^(https?:\/\/|\/|mailto:|tel:|#)/i.test(url)) url = 'https://' + url;
      if (window.getSelection().isCollapsed) document.execCommand('insertHTML', false, '<a href="' + url.replace(/"/g, '&quot;') + '">' + url.replace(/</g, '&lt;') + '</a>');
      else document.execCommand('createLink', false, url);
    }
    linkBar.hidden = true;
  });
  linkInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') { e.preventDefault(); linkBar.querySelector('[data-act=link-ok]').click(); }
    if (e.key === 'Escape') linkBar.hidden = true;
  });

  imgInput.addEventListener('change', function () {
    var file = imgInput.files[0];
    if (!file) return;
    var fd = new FormData();
    fd.append('image', file);
    fd.append('_token', editor.dataset.token);
    status.textContent = 'در حال آپلود تصویر…';
    fetch(editor.dataset.upload, { method: 'POST', body: fd, credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (j) {
        if (j.url) {
          restoreSel();
          document.execCommand('insertHTML', false, '<figure><img src="' + j.url + '" alt=""></figure><p><br></p>');
          status.textContent = 'تصویر اضافه شد.';
        } else status.textContent = j.error || 'آپلود انجام نشد.';
      })
      .catch(function () { status.textContent = 'آپلود انجام نشد. اتصال را بررسی کنید.'; })
      .finally(function () { imgInput.value = ''; });
  });

  // Paste as clean text paragraphs (Word/Google Docs paste brings a lot of junk styling)
  content.addEventListener('paste', function (e) {
    var text = (e.clipboardData || window.clipboardData).getData('text/plain');
    if (!text) return;
    e.preventDefault();
    var html = text.split(/\n{2,}/).map(function (p) {
      return '<p>' + p.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/\n/g, '<br>') + '</p>';
    }).join('');
    document.execCommand('insertHTML', false, html);
  });

  // Language change switches the editing direction
  var langSel = document.getElementById('lang');
  if (langSel) langSel.addEventListener('change', function () {
    content.dir = (langSel.value === 'fa' || langSel.value === 'ar') ? 'rtl' : 'ltr';
  });

  document.getElementById('post-form').addEventListener('submit', function () {
    body.value = htmlMode ? src.value : content.innerHTML;
  });
})();
