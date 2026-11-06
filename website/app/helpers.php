<?php
declare(strict_types=1);

function config(string $key, $default = null)
{
    return $GLOBALS['config'][$key] ?? $default;
}

function e($v): string
{
    return htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function is_https(): bool
{
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
        || (($_SERVER['SERVER_PORT'] ?? '') === '443');
}

function client_ip(): string
{
    return substr((string) ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'), 0, 45);
}

/** Path prefix when the site lives in a sub-folder (e.g. /site). Empty on a root install. */
function base_path(): string
{
    static $p = null;
    if ($p === null) {
        $p = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php')), '/');
    }
    return $p;
}

function asset(string $path): string
{
    $file = ROOT . '/' . ltrim($path, '/');
    $v = is_file($file) ? '?v=' . filemtime($file) : '';
    return base_path() . '/' . ltrim($path, '/') . $v;
}

/** Site-relative URL: url('fa', 'blog') -> /fa/blog */
function url(string $lang, string $path = ''): string
{
    $path = trim($path, '/');
    $hash = '';
    if (($pos = strpos($path, '#')) !== false) {
        $hash = substr($path, $pos);
        $path = substr($path, 0, $pos);
    }
    return base_path() . '/' . $lang . ($path !== '' ? '/' . $path : '/') . $hash;
}

function admin_url(string $path = ''): string
{
    return base_path() . '/' . config('admin_path', 'admin') . ($path !== '' ? '/' . ltrim($path, '/') : '');
}

function absolute_url(string $siteRelative): string
{
    $base = rtrim((string) config('base_url', ''), '/');
    if ($base === '') {
        $base = (is_https() ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
        return $base . $siteRelative;
    }
    // base_url already contains any sub-folder, so strip base_path()
    $bp = base_path();
    if ($bp !== '' && strpos($siteRelative, $bp) === 0) {
        $siteRelative = substr($siteRelative, strlen($bp));
    }
    return $base . $siteRelative;
}

function redirect(string $to, int $code = 302): void
{
    header('Location: ' . $to, true, $code);
    exit;
}

function starts_with(string $h, string $n): bool
{
    return $n === '' || strncmp($h, $n, strlen($n)) === 0;
}

/* ---------- views ---------- */

function view(string $name, array $data = []): string
{
    extract($data, EXTR_SKIP);
    ob_start();
    require ROOT . '/views/' . $name . '.php';
    return (string) ob_get_clean();
}

/* ---------- session flash + csrf ---------- */

function flash(string $key, $value = null)
{
    start_session();
    if ($value !== null) {
        $_SESSION['flash'][$key] = $value;
        return null;
    }
    $v = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $v;
}

function csrf_token(): string
{
    start_session();
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
}

function csrf_ok(): bool
{
    start_session();
    $t = (string) ($_POST['_token'] ?? '');
    return !empty($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $t);
}

/* ---------- text ---------- */

function slugify(string $s): string
{
    $s = trim(mb_strtolower($s));
    $s = preg_replace('/[\x{200C}\x{200D}]/u', '-', $s);           // ZWNJ -> dash
    $s = preg_replace('/[^\p{L}\p{N}]+/u', '-', $s);
    $s = trim((string) $s, '-');
    return mb_substr($s, 0, 120) ?: 'post';
}

function excerpt_from_html(string $html, int $len = 180): string
{
    $t = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($html), ENT_QUOTES, 'UTF-8')));
    return mb_strlen($t) > $len ? rtrim(mb_substr($t, 0, $len)) . '…' : $t;
}

function digits(string $s, string $lang): string
{
    if ($lang === 'fa') {
        return strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']);
    }
    return $s;
}

/* ---------- dates ---------- */

/** Gregorian -> Jalali (algorithm by Roozbeh Pournader & Mohammad Toossi, public domain). */
function gregorian_to_jalali(int $gy, int $gm, int $gd): array
{
    $g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
    $gy2 = ($gm > 2) ? ($gy + 1) : $gy;
    $days = 355666 + (365 * $gy) + intdiv($gy2 + 3, 4) - intdiv($gy2 + 99, 100) + intdiv($gy2 + 399, 400) + $gd + $g_d_m[$gm - 1];
    $jy = -1595 + (33 * intdiv($days, 12053));
    $days %= 12053;
    $jy += 4 * intdiv($days, 1461);
    $days %= 1461;
    if ($days > 365) {
        $jy += intdiv($days - 1, 365);
        $days = ($days - 1) % 365;
    }
    if ($days < 186) {
        $jm = 1 + intdiv($days, 31);
        $jd = 1 + ($days % 31);
    } else {
        $jm = 7 + intdiv($days - 186, 30);
        $jd = 1 + (($days - 186) % 30);
    }
    return [$jy, $jm, $jd];
}

function format_date(?string $datetime, string $lang): string
{
    if (!$datetime) {
        return '';
    }
    $ts = strtotime($datetime);
    $months = (array) t('months', $lang);
    $y = (int) date('Y', $ts);
    $m = (int) date('n', $ts);
    $d = (int) date('j', $ts);
    if ($lang === 'fa') {
        [$y, $m, $d] = gregorian_to_jalali($y, $m, $d);
        return digits($d . ' ' . $months[$m - 1] . ' ' . $y, 'fa');
    }
    if ($lang === 'en') {
        return $months[$m - 1] . ' ' . $d . ', ' . $y;
    }
    return $d . ' ' . $months[$m - 1] . ' ' . $y;
}

/* ---------- uploads ---------- */

/** Store an uploaded image under uploads/YYYY/MM/. Returns the public path or throws. */
function store_image_upload(array $file): string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('آپلود فایل انجام نشد.');
    }
    if ($file['size'] > 5 * 1024 * 1024) {
        throw new RuntimeException('حجم تصویر باید کمتر از ۵ مگابایت باشد.');
    }
    $info = @getimagesize($file['tmp_name']);
    $types = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_WEBP => 'webp', IMAGETYPE_GIF => 'gif'];
    if (!$info || !isset($types[$info[2]])) {
        throw new RuntimeException('فقط تصویر JPG، PNG، WebP یا GIF قابل آپلود است.');
    }
    $dir = 'uploads/' . date('Y') . '/' . date('m');
    if (!is_dir(ROOT . '/' . $dir)) {
        mkdir(ROOT . '/' . $dir, 0775, true);
    }
    $name = bin2hex(random_bytes(8)) . '.' . $types[$info[2]];
    if (!move_uploaded_file($file['tmp_name'], ROOT . '/' . $dir . '/' . $name)) {
        throw new RuntimeException('ذخیره‌ی فایل انجام نشد. دسترسی پوشه‌ی uploads را بررسی کنید.');
    }
    return '/' . $dir . '/' . $name;
}

function media_url(string $stored): string
{
    if ($stored === '' || preg_match('#^https?://#', $stored)) {
        return $stored;
    }
    return base_path() . $stored;
}

/* ---------- defaults that the admin panel can override ---------- */

// RastDesk edition prices in million Toman (official 1405 price list).
const PRICE_DEFAULTS = ['basic' => '153', 'pro' => '246', 'plus' => '539', 'max' => '924'];

const SOCIAL_DEFAULTS = ['instagram' => 'https://instagram.com/rastdesk'];
