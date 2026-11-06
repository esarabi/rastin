<?php
header('Content-Type: application/xml; charset=utf-8');
$pages = ['', 'modules', 'pricing', 'demo', 'catalog', 'blog', 'contact'];
foreach (array_keys(modules_data('fa')['modules']) as $slug) {
    $pages[] = 'modules/' . $slug;
}
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";
foreach (languages() as $l) {
    foreach ($pages as $p) {
        echo '<url><loc>' . e(absolute_url(url($l, $p))) . '</loc>';
        foreach (languages() as $alt) {
            echo '<xhtml:link rel="alternate" hreflang="' . e($alt) . '" href="' . e(absolute_url(url($alt, $p))) . '"/>';
        }
        echo "</url>\n";
    }
}
$st = db()->prepare("SELECT lang, slug, updated_at FROM posts WHERE status = 'published' AND published_at <= ?");
$st->execute([now()]);
foreach ($st as $r) {
    echo '<url><loc>' . e(absolute_url(url($r['lang'], 'blog/' . rawurlencode($r['slug'])))) . '</loc><lastmod>' . e(substr($r['updated_at'], 0, 10)) . "</lastmod></url>\n";
}
echo '</urlset>';
