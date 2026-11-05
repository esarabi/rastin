// Rastin website: mobile menu, language menu, and the node-network background drawn from the brand mark.
(function () {
  'use strict';

  // ----- mobile menu -----
  var toggle = document.querySelector('.nav-toggle');
  var nav = document.getElementById('main-nav');
  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var open = toggle.getAttribute('aria-expanded') !== 'true';
      toggle.setAttribute('aria-expanded', String(open));
      nav.classList.toggle('open', open);
      document.body.classList.toggle('nav-open', open);
    });
  }

  // ----- close the language menu on outside click / Escape -----
  var lang = document.querySelector('.lang-switch');
  if (lang) {
    document.addEventListener('click', function (e) { if (!lang.contains(e.target)) lang.removeAttribute('open'); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') lang.removeAttribute('open'); });
  }

  // ----- node network -----
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var canvases = Array.prototype.slice.call(document.querySelectorAll('canvas.net'));

  function makeNet(canvas) {
    var ctx = canvas.getContext('2d');
    var nodes = [], w = 0, h = 0, seed = 11;
    function rnd() { seed = (seed * 9301 + 49297) % 233280; return seed / 233280; }

    function build() {
      var r = canvas.getBoundingClientRect();
      var dpr = Math.min(window.devicePixelRatio || 1, 2);
      w = r.width; h = r.height;
      canvas.width = Math.round(w * dpr); canvas.height = Math.round(h * dpr);
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
      seed = 11; nodes = [];
      var count = Math.max(14, Math.min(46, Math.round((w * h) / 16000)));
      for (var i = 0; i < count; i++) {
        var x = rnd() * w, y = rnd() * h;
        // nodes grow toward the upper "growth" corner, like the mark
        var grow = 0.4 + (1 - y / h) * 0.6;
        nodes.push({
          x: x, y: y, r: 2 + rnd() * 6 * grow,
          vx: (rnd() - 0.5) * 0.18, vy: (rnd() - 0.5) * 0.18,
          c: i % 9 === 4 ? 'coral' : (i % 13 === 7 ? 'amber' : 'base')
        });
      }
    }

    function draw() {
      ctx.clearRect(0, 0, w, h);
      var max = 130;
      ctx.lineCap = 'round';
      for (var i = 0; i < nodes.length; i++) {
        for (var j = i + 1; j < nodes.length; j++) {
          var dx = nodes[i].x - nodes[j].x, dy = nodes[i].y - nodes[j].y;
          var d = Math.sqrt(dx * dx + dy * dy);
          if (d < max) {
            ctx.strokeStyle = 'rgba(255,255,255,' + (0.14 * (1 - d / max)).toFixed(3) + ')';
            ctx.lineWidth = 1.5;
            ctx.beginPath(); ctx.moveTo(nodes[i].x, nodes[i].y); ctx.lineTo(nodes[j].x, nodes[j].y); ctx.stroke();
          }
        }
      }
      for (var k = 0; k < nodes.length; k++) {
        var n = nodes[k];
        ctx.fillStyle = n.c === 'coral' ? '#FF5A36' : n.c === 'amber' ? '#FFB547' : 'rgba(255,255,255,.35)';
        ctx.beginPath(); ctx.arc(n.x, n.y, n.r, 0, Math.PI * 2); ctx.fill();
      }
    }

    function step() {
      for (var i = 0; i < nodes.length; i++) {
        var n = nodes[i];
        n.x += n.vx; n.y += n.vy;
        if (n.x < -10 || n.x > w + 10) n.vx *= -1;
        if (n.y < -10 || n.y > h + 10) n.vy *= -1;
      }
      draw();
    }

    build(); draw();
    return { build: function () { build(); draw(); }, step: step, el: canvas };
  }

  var nets = canvases.map(makeNet);
  var resizeTimer;
  window.addEventListener('resize', function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function () { nets.forEach(function (n) { n.build(); }); }, 150);
  });

  if (!reduce && nets.length && 'IntersectionObserver' in window) {
    var visible = new Set();
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        var net = nets.filter(function (n) { return n.el === en.target; })[0];
        if (en.isIntersecting) visible.add(net); else visible.delete(net);
      });
    });
    nets.forEach(function (n) { io.observe(n.el); });
    (function loop() {
      visible.forEach(function (n) { n.step(); });
      requestAnimationFrame(loop);
    })();
  }
})();
