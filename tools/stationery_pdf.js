// Render a stationery HTML file to PDF and per-face PNG previews.
// Usage: node tools/stationery_pdf.js <html> <pdf> <width_mm> <height_mm> [png_prefix]
const path = require('path');
const { chromium } = require('playwright');
const [html, pdf, w, h, png] = process.argv.slice(2);

(async () => {
  const b = await chromium.launch();
  const p = await b.newPage();
  await p.goto('file://' + path.resolve(html));
  await p.evaluate(() => document.fonts.ready);
  await p.waitForTimeout(300);
  await p.pdf({ path: path.resolve(pdf), width: w + 'mm', height: h + 'mm', printBackground: true, preferCSSPageSize: true });
  if (png) {
    const v = await b.newPage({ deviceScaleFactor: 4 });
    await v.goto('file://' + path.resolve(html));
    await v.evaluate(() => document.fonts.ready);
    await v.addStyleTag({ content: 'body{display:block!important;padding:0!important}.card,.sheet{box-shadow:none!important;margin:0 0 10px}' });
    await v.waitForTimeout(300);
    const faces = await v.$$('.card, .sheet');
    for (let i = 0; i < faces.length; i++) await faces[i].screenshot({ path: `${path.resolve(png)}-${i + 1}.png` });
  }
  await b.close();
})();
