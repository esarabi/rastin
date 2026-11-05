<?php
declare(strict_types=1);

// Local development with `php -S localhost:8000 index.php`: serve real files directly.
if (PHP_SAPI === 'cli-server') {
    $file = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($file) && !preg_match('#/(app|lang|views|storage|tools)/|config#', $file)) {
        return false;
    }
}

require __DIR__ . '/app/bootstrap.php';

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$uri = rawurldecode($uri);
if (base_path() !== '' && starts_with($uri, base_path())) {
    $uri = substr($uri, strlen(base_path()));
}
$uri = '/' . trim($uri, '/');
if ($uri === '/index.php') {
    $uri = '/';
}
$parts = $uri === '/' ? [] : explode('/', trim($uri, '/'));
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    // ----- admin panel -----
    if (($parts[0] ?? '') === config('admin_path', 'admin')) {
        require __DIR__ . '/app/admin.php';
        admin_dispatch(array_slice($parts, 1), $method);
        exit;
    }

    // ----- technical files -----
    if ($uri === '/sitemap.xml') {
        require __DIR__ . '/app/sitemap.php';
        exit;
    }
    if ($uri === '/robots.txt') {
        header('Content-Type: text/plain; charset=utf-8');
        echo "User-agent: *\nDisallow: /" . config('admin_path', 'admin') . "/\nSitemap: " . absolute_url(base_path() . '/sitemap.xml') . "\n";
        exit;
    }

    // ----- root: send visitors to their language -----
    if (!$parts) {
        redirect(url(detect_browser_lang()));
    }

    $lang = $parts[0];
    if (!in_array($lang, languages(), true)) {
        current_lang(config('default_lang', 'fa'));
        not_found();
    }
    current_lang($lang);
    $page = $parts[1] ?? '';
    $extra = $parts[2] ?? null;
    if (isset($parts[3]) || ($extra !== null && $page !== 'blog')) {
        not_found();
    }

    switch ($page) {
        case '':
            render_page('home', ['posts' => latest_posts($lang, 3)]);
            break;
        case 'rastdesk':
        case 'solutions':
        case 'pricing':
        case 'about':
            render_page($page);
            break;
        case 'blog':
            if ($extra === null) {
                $per = 9;
                $p = max(1, (int) ($_GET['page'] ?? 1));
                $st = db()->prepare("SELECT COUNT(*) FROM posts WHERE lang = ? AND status = 'published' AND published_at <= ?");
                $st->execute([$lang, now()]);
                $total = (int) $st->fetchColumn();
                $pages = max(1, (int) ceil($total / $per));
                if ($p > $pages) {
                    not_found();
                }
                $st = db()->prepare("SELECT * FROM posts WHERE lang = ? AND status = 'published' AND published_at <= ? ORDER BY published_at DESC LIMIT $per OFFSET " . (($p - 1) * $per));
                $st->execute([$lang, now()]);
                render_page('blog', ['posts' => $st->fetchAll(), 'pageNum' => $p, 'pages' => $pages]);
            } else {
                $st = db()->prepare("SELECT * FROM posts WHERE lang = ? AND slug = ? AND status = 'published' AND published_at <= ?");
                $st->execute([$lang, $extra, now()]);
                $post = $st->fetch();
                if (!$post) {
                    not_found();
                }
                render_page('post', ['post' => $post, 'more' => array_values(array_filter(latest_posts($lang, 4), fn($x) => $x['id'] !== $post['id']))]);
            }
            break;
        case 'contact':
        case 'demo':
            $errors = [];
            $old = [];
            if ($method === 'POST') {
                [$errors, $old] = handle_form($page, $lang, url($lang, $page));
            } elseif (!empty($_GET['interest'])) {
                $old['interest'] = (string) $_GET['interest'];
            }
            start_session();
            render_page($page, ['errors' => $errors, 'old' => $old, 'success' => flash('form_success_' . $page)]);
            break;
        default:
            not_found();
    }
} catch (Throwable $ex) {
    error_log('[rastin] ' . $ex);
    http_response_code(500);
    if (config('debug')) {
        echo '<pre>' . e((string) $ex) . '</pre>';
    } else {
        echo '<!doctype html><meta charset="utf-8"><title>Error</title><p style="font-family:sans-serif;padding:2rem">خطایی رخ داد. لطفاً کمی بعد دوباره تلاش کنید. / Something went wrong. Please try again later.</p>';
    }
}

function latest_posts(string $lang, int $n): array
{
    $st = db()->prepare("SELECT * FROM posts WHERE lang = ? AND status = 'published' AND published_at <= ? ORDER BY published_at DESC LIMIT " . (int) $n);
    $st->execute([$lang, now()]);
    return $st->fetchAll();
}

function render_page(string $name, array $data = []): void
{
    $lang = current_lang();
    $data['lang'] = $lang;
    $data['pageName'] = $name;
    $content = view('pages/' . $name, $data);
    $title = $data['title'] ?? null;
    if ($title === null) {
        $title = $name === 'home'
            ? t('brand.company_full') . ' | ' . site_text('tagline', 'brand.tagline')
            : (string) t($name . '.title') . ' | ' . t('brand.company_full');
    }
    if ($name === 'post') {
        $title = $data['post']['title'] . ' | ' . t('brand.company_full');
    }
    $desc = $name === 'post'
        ? ($data['post']['excerpt'] ?: excerpt_from_html((string) $data['post']['body']))
        : site_text('meta_description', 'meta.description');
    $path = trim(substr(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/', strlen(base_path())), '/');
    echo view('layout', $data + [
        'content' => $content,
        'title' => $title,
        'description' => $desc,
        'pathAfterLang' => trim(substr($path, strlen($lang)), '/'),
        'ogImage' => $name === 'post' && $data['post']['cover'] ? absolute_url(media_url($data['post']['cover'])) : absolute_url(asset('assets/img/og-image.png')),
    ]);
}

function not_found(): void
{
    http_response_code(404);
    render_page('404', ['title' => t('errors.not_found_title') . ' | ' . t('brand.company_full')]);
    exit;
}
