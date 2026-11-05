// Render catalog/build/*.html to PDF (digital + print with bleed) and PNG page previews.
// Usage: node tools/catalog_pdf.js catalog/build
const path = require('path');
const { chromium } = require('playwright');
const out = path.resolve(process.argv[2] || 'catalog/build');

(async () => {
  const b = await chromium.launch();
  const p = await b.newPage();
  const jobs = [
    ['catalog.html', 'rastin-catalog-digital.pdf', 210, 297],
    ['catalog-print.html', 'rastin-catalog-print-3mm-bleed.pdf', 216, 303],
  ];
  for (const [src, pdf, w, h] of jobs) {
    await p.goto('file://' + out + '/' + src);
    await p.evaluate(() => document.fonts.ready);
    await p.waitForTimeout(300);
    await p.pdf({ path: out + '/' + pdf, width: w + 'mm', height: h + 'mm', printBackground: true, preferCSSPageSize: true });
  }
  const v = await b.newPage({ viewport: { width: 794, height: 1123 }, deviceScaleFactor: 1.5 });
  await v.goto('file://' + out + '/catalog.html');
  await v.evaluate(() => document.fonts.ready);
  await v.addStyleTag({ content: 'body{display:block!important;padding:0!important;background:#fff!important}.sheet{box-shadow:none!important}' });
  await v.waitForTimeout(300);
  const sheets = await v.$$('.sheet');
  for (let i = 0; i < sheets.length; i++) await sheets[i].screenshot({ path: `${out}/page-${i + 1}.png` });
  await b.close();
})();
