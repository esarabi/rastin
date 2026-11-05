<?php
/**
 * Writes an HTML sheet of the signature images (logo + coral icons) for rasterising with
 * tools/email_assets.js. Output PNGs go to website/assets/img/email/ so the live site hosts them.
 */
declare(strict_types=1);
require __DIR__ . '/../website/app/bootstrap.php';

$icons = ['mobile', 'building', 'mail', 'globe', 'pin', 'instagram'];
$logo = function (string $file): string {
    $s = file_get_contents(__DIR__ . '/../brand/logo/' . $file);
    $s = preg_replace('/<\?xml[^>]*>/', '', $s);
    return preg_replace('/ width="[\d.]+" height="[\d.]+"/', '', $s, 1);
};
?><!doctype html>
<html><head><meta charset="utf-8"><style>
body{margin:0;background:transparent}
.item{display:inline-block;margin:4px;line-height:0}
.ic{width:16px;height:16px;color:#FF5A36}
.ic svg{width:16px;height:16px;stroke-width:2}
.logo svg{height:44px;width:auto;display:block}
</style></head><body>
<?php foreach ($icons as $i): ?><div class="item ic" data-name="icon-<?= $i ?>"><?= icon($i) ?></div><?php endforeach; ?>
<div class="item logo" data-name="logo-fa"><?= $logo('rastin-horizontal-fa-color.svg') ?></div>
<div class="item logo" data-name="logo-en"><?= $logo('rastin-horizontal-en-color.svg') ?></div>
</body></html>
