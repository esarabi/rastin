// RastDesk website: mobile menu, team tabs, module edition filter, node-network backgrounds.
(function () {
  'use strict';
  document.documentElement.classList.add('js');

  // mobile menu
  var toggle = document.querySelector('.nav-toggle'), nav = document.getElementById('main-nav');
  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var open = toggle.getAttribute('aria-expanded') !== 'true';
      toggle.setAttribute('aria-expanded', String(open));
      nav.classList.toggle('open', open);
      document.body.classList.toggle('nav-open', open);
    });
    nav.querySelectorAll('a').forEach(function (a) {
      a.addEventListener('click', function () {
        toggle.setAttribute('aria-expanded', 'false'); nav.classList.remove('open'); document.body.classList.remove('nav-open');
      });
    });
  }

  // tabs (team use cases). Without JS every panel is visible.
  document.querySelectorAll('[data-tabs]').forEach(function (box) {
    var tabs = box.querySelectorAll('[role="tab"]');
    function select(t) {
      tabs.forEach(function (x) {
        var on = x === t;
        x.setAttribute('aria-selected', String(on));
        x.tabIndex = on ? 0 : -1;
        document.getElementById(x.getAttribute('aria-controls')).hidden = !on;
      });
    }
    tabs.forEach(function (t, i) {
      t.addEventListener('click', function () { select(t); });
      t.addEventListener('keydown', function (e) {
        var dir = document.documentElement.dir === 'rtl' ? -1 : 1, n = null;
        if (e.key === 'ArrowRight') n = tabs[(i + dir + tabs.length) % tabs.length];
        if (e.key === 'ArrowLeft') n = tabs[(i - dir + tabs.length) % tabs.length];
        if (n) { e.preventDefault(); n.focus(); select(n); }
      });
    });
    select(box.querySelector('[aria-selected="true"]') || tabs[0]);
  });

  // module edition filter: dims modules that are not in the chosen edition
  var order = ['basic', 'pro', 'plus', 'max'];
  var fbtns = document.querySelectorAll('[data-filter]');
  fbtns.forEach(function (b) {
    b.addEventListener('click', function () {
      fbtns.forEach(function (x) { x.setAttribute('aria-pressed', String(x === b)); });
      var f = b.dataset.filter;
      document.querySelectorAll('.mod-chip[data-edition]').forEach(function (c) {
        var on = f === 'all' || order.indexOf(c.dataset.edition) <= order.indexOf(f);
        c.classList.toggle('dim', !on);
      });
    });
  });

  // node network backgrounds
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  function net(c) {
    var x = c.getContext('2d'), nodes = [], w, h, seed = 7;
    function rnd() { seed = (seed * 9301 + 49297) % 233280; return seed / 233280; }
    function build() {
      var r = c.getBoundingClientRect(), d = Math.min(window.devicePixelRatio || 1, 2);
      w = r.width; h = r.height; c.width = w * d; c.height = h * d; x.setTransform(d, 0, 0, d, 0, 0);
      seed = 7; nodes = [];
      var n = Math.max(12, Math.min(44, Math.round(w * h / 18000)));
      for (var i = 0; i < n; i++) nodes.push({ x: rnd() * w, y: rnd() * h, r: 1.5 + rnd() * 4, vx: (rnd() - .5) * .16, vy: (rnd() - .5) * .16, k: i % 9 === 4 ? '#FF5A36' : i % 13 === 7 ? '#FFB547' : 'rgba(255,255,255,.22)' });
    }
    function draw() {
      x.clearRect(0, 0, w, h); x.lineCap = 'round';
      for (var i = 0; i < nodes.length; i++) for (var j = i + 1; j < nodes.length; j++) {
        var a = nodes[i], b = nodes[j], dd = Math.hypot(a.x - b.x, a.y - b.y);
        if (dd < 130) { x.strokeStyle = 'rgba(255,255,255,' + (.08 * (1 - dd / 130)).toFixed(3) + ')'; x.lineWidth = 1.2; x.beginPath(); x.moveTo(a.x, a.y); x.lineTo(b.x, b.y); x.stroke(); }
      }
      nodes.forEach(function (p) { x.fillStyle = p.k; x.beginPath(); x.arc(p.x, p.y, p.r, 0, 7); x.fill(); });
    }
    function step() { nodes.forEach(function (p) { p.x += p.vx; p.y += p.vy; if (p.x < -10 || p.x > w + 10) p.vx *= -1; if (p.y < -10 || p.y > h + 10) p.vy *= -1; }); draw(); }
    build(); draw();
    return { build: function () { build(); draw(); }, step: step, el: c };
  }
  var nets = Array.prototype.map.call(document.querySelectorAll('canvas.net'), net);
  var t; window.addEventListener('resize', function () { clearTimeout(t); t = setTimeout(function () { nets.forEach(function (n) { n.build(); }); }, 150); });
  if (!reduce && nets.length && 'IntersectionObserver' in window) {
    var vis = new Set();
    var io = new IntersectionObserver(function (es) { es.forEach(function (e) { var n = nets.filter(function (k) { return k.el === e.target; })[0]; if (e.isIntersecting) vis.add(n); else vis.delete(n); }); });
    nets.forEach(function (n) { io.observe(n.el); });
    (function loop() { vis.forEach(function (n) { n.step(); }); requestAnimationFrame(loop); })();
  }
})();
