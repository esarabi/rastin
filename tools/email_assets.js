// Rasterise the email-signature images at 3x for sharp display on retina screens.
// Usage: node tools/email_assets.js <sheet.html> <out_dir>
const path = require('path');
const { chromium } = require('playwright');
const [sheet, out] = process.argv.slice(2);
(async () => {
  const b = await chromium.launch();
  const p = await b.newPage({ deviceScaleFactor: 3 });
  await p.goto('file://' + path.resolve(sheet));
  await p.waitForTimeout(200);
  for (const el of await p.$$('[data-name]')) {
    const name = await el.getAttribute('data-name');
    await el.screenshot({ path: path.join(path.resolve(out), name + '.png'), omitBackground: true });
  }
  await b.close();
})();
