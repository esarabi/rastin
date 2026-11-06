<?php
declare(strict_types=1);

/** Admin panel (Persian, RTL). Routes live under /admin/. */

if (!function_exists('admin_dt')) {
    function admin_dt(?string $dt): string
    {
        return $dt ? format_date($dt, 'fa') . ' · ' . digits(substr($dt, 11, 5), 'fa') : '';
    }
    function form_label(string $f): string
    {
        return form_types()[$f] ?? $f;
    }
    function lang_label(string $l): string
    {
        return ['fa' => 'فارسی', 'en' => 'English', 'ar' => 'العربية', 'tr' => 'Türkçe'][$l] ?? $l;
    }
}


function admin_user(): ?array
{
    start_session();
    if (empty($_SESSION['uid'])) {
        return null;
    }
    $st = db()->prepare('SELECT id, username, display_name FROM users WHERE id = ?');
    $st->execute([$_SESSION['uid']]);
    return $st->fetch() ?: null;
}

function admin_render(string $name, array $data = []): void
{
    $data['user'] = $data['user'] ?? admin_user();
    $data['unread'] = $data['user'] ? (int) db()->query('SELECT COUNT(*) FROM submissions WHERE is_read = 0')->fetchColumn() : 0;
    $data['content'] = view('admin/' . $name, $data);
    header('X-Robots-Tag: noindex, nofollow');
    header('Cache-Control: no-store');
    echo view('admin/layout', $data);
}

function require_post_csrf(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST' || !csrf_ok()) {
        flash('error', 'زمان فرم تمام شده بود. دوباره تلاش کنید.');
        redirect($_SERVER['HTTP_REFERER'] ?? admin_url());
    }
}

function admin_dispatch(array $parts, string $method): void
{
    start_session();
    $has_users = (int) db()->query('SELECT COUNT(*) FROM users')->fetchColumn() > 0;
    $route = $parts[0] ?? '';

    // First run: create the administrator account.
    if (!$has_users) {
        if ($route !== 'setup') {
            redirect(admin_url('setup'));
        }
        admin_setup($method);
        return;
    }
    if ($route === 'setup') {
        redirect(admin_url());
    }
    if ($route === 'login') {
        admin_login($method);
        return;
    }

    $user = admin_user();
    if (!$user) {
        redirect(admin_url('login'));
    }

    switch ($route) {
        case '':
            admin_dashboard();
            break;
        case 'logout':
            require_post_csrf();
            $_SESSION = [];
            session_regenerate_id(true);
            redirect(admin_url('login'));
            break;
        case 'posts':
            admin_posts($parts, $method);
            break;
        case 'upload':
            admin_upload();
            break;
        case 'settings':
            admin_settings($method);
            break;
        case 'messages':
            admin_messages($parts, $method);
            break;
        case 'account':
            admin_account($method, $user);
            break;
        default:
            http_response_code(404);
            admin_render('notfound');
    }
}

/* ---------- auth ---------- */

function admin_setup(string $method): void
{
    $errors = [];
    $old = ['username' => '', 'display_name' => ''];
    if ($method === 'POST') {
        if (!csrf_ok()) {
            $errors[] = 'زمان فرم تمام شده بود. دوباره تلاش کنید.';
        }
        $old['username'] = trim((string) ($_POST['username'] ?? ''));
        $old['display_name'] = trim((string) ($_POST['display_name'] ?? ''));
        $pass = (string) ($_POST['password'] ?? '');
        if (!preg_match('/^[a-zA-Z0-9_.-]{3,40}$/', $old['username'])) {
            $errors[] = 'نام کاربری باید ۳ تا ۴۰ حرف انگلیسی، عدد یا . _ - باشد.';
        }
        if (mb_strlen($pass) < 10) {
            $errors[] = 'رمز عبور باید حداقل ۱۰ کاراکتر باشد.';
        }
        if ($pass !== (string) ($_POST['password2'] ?? '')) {
            $errors[] = 'تکرار رمز عبور یکسان نیست.';
        }
        if (!$errors) {
            db()->prepare('INSERT INTO users (username, password_hash, display_name, created_at) VALUES (?, ?, ?, ?)')
                ->execute([$old['username'], password_hash($pass, PASSWORD_DEFAULT), $old['display_name'], now()]);
            session_regenerate_id(true);
            $_SESSION['uid'] = (int) db()->lastInsertId();
            flash('ok', 'حساب مدیر ساخته شد. خوش آمدید!');
            redirect(admin_url('settings'));
        }
    }
    admin_render('setup', ['errors' => $errors, 'old' => $old, 'user' => null, 'bare' => true]);
}

function admin_login(string $method): void
{
    if (admin_user()) {
        redirect(admin_url());
    }
    $error = '';
    $username = '';
    if ($method === 'POST') {
        $username = trim((string) ($_POST['username'] ?? ''));
        if (!csrf_ok()) {
            $error = 'زمان فرم تمام شده بود. دوباره تلاش کنید.';
        } elseif (too_many_attempts('login', 5, 15)) {
            $error = 'تعداد تلاش‌های ناموفق زیاد است. ۱۵ دقیقه‌ی دیگر دوباره امتحان کنید.';
        } else {
            $st = db()->prepare('SELECT * FROM users WHERE username = ?');
            $st->execute([$username]);
            $u = $st->fetch();
            if ($u && password_verify((string) ($_POST['password'] ?? ''), $u['password_hash'])) {
                clear_attempts('login');
                if (password_needs_rehash($u['password_hash'], PASSWORD_DEFAULT)) {
                    db()->prepare('UPDATE users SET password_hash = ? WHERE id = ?')->execute([password_hash((string) $_POST['password'], PASSWORD_DEFAULT), $u['id']]);
                }
                session_regenerate_id(true);
                $_SESSION['uid'] = (int) $u['id'];
                redirect(admin_url());
            }
            record_attempt('login');
            $error = 'نام کاربری یا رمز عبور درست نیست.';
        }
    }
    admin_render('login', ['error' => $error, 'username' => $username, 'user' => null, 'bare' => true]);
}

function admin_account(string $method, array $user): void
{
    $errors = [];
    if ($method === 'POST') {
        require_post_csrf();
        $st = db()->prepare('SELECT password_hash FROM users WHERE id = ?');
        $st->execute([$user['id']]);
        $hash = (string) $st->fetchColumn();
        $new = (string) ($_POST['new_password'] ?? '');
        $name = trim((string) ($_POST['display_name'] ?? ''));
        if ($new !== '') {
            if (!password_verify((string) ($_POST['current_password'] ?? ''), $hash)) {
                $errors[] = 'رمز عبور فعلی درست نیست.';
            }
            if (mb_strlen($new) < 10) {
                $errors[] = 'رمز عبور جدید باید حداقل ۱۰ کاراکتر باشد.';
            }
            if ($new !== (string) ($_POST['new_password2'] ?? '')) {
                $errors[] = 'تکرار رمز عبور جدید یکسان نیست.';
            }
        }
        if (!$errors) {
            db()->prepare('UPDATE users SET display_name = ? WHERE id = ?')->execute([$name, $user['id']]);
            if ($new !== '') {
                db()->prepare('UPDATE users SET password_hash = ? WHERE id = ?')->execute([password_hash($new, PASSWORD_DEFAULT), $user['id']]);
            }
            flash('ok', 'تغییرات حساب ذخیره شد.');
            redirect(admin_url('account'));
        }
        $user['display_name'] = $name;
    }
    admin_render('account', ['errors' => $errors, 'user' => $user]);
}

/* ---------- dashboard ---------- */

function admin_dashboard(): void
{
    $pdo = db();
    $stats = [
        'published' => (int) $pdo->query("SELECT COUNT(*) FROM posts WHERE status = 'published'")->fetchColumn(),
        'drafts' => (int) $pdo->query("SELECT COUNT(*) FROM posts WHERE status = 'draft'")->fetchColumn(),
        'messages' => (int) $pdo->query('SELECT COUNT(*) FROM submissions')->fetchColumn(),
        'unread' => (int) $pdo->query('SELECT COUNT(*) FROM submissions WHERE is_read = 0')->fetchColumn(),
    ];
    $recent = $pdo->query('SELECT * FROM submissions ORDER BY id DESC LIMIT 6')->fetchAll();
    $posts = $pdo->query('SELECT id, lang, title, status, updated_at FROM posts ORDER BY updated_at DESC LIMIT 6')->fetchAll();
    $endpointsMissing = setting('contact_endpoint') === '' || setting('demo_endpoint') === '' || setting('catalog_endpoint') === '';
    admin_render('dashboard', compact('stats', 'recent', 'posts', 'endpointsMissing'));
}

/* ---------- posts ---------- */

function admin_posts(array $parts, string $method): void
{
    $id = $parts[1] ?? null;
    $action = $parts[2] ?? null;

    if ($id === null) {
        $lang = (string) ($_GET['lang'] ?? '');
        $sql = 'SELECT id, lang, slug, title, status, published_at, updated_at FROM posts';
        $args = [];
        if (in_array($lang, languages(), true)) {
            $sql .= ' WHERE lang = ?';
            $args[] = $lang;
        }
        $st = db()->prepare($sql . ' ORDER BY updated_at DESC');
        $st->execute($args);
        admin_render('posts', ['posts' => $st->fetchAll(), 'filter' => $lang]);
        return;
    }

    if ($action === 'delete') {
        require_post_csrf();
        db()->prepare('DELETE FROM posts WHERE id = ?')->execute([(int) $id]);
        flash('ok', 'مطلب حذف شد.');
        redirect(admin_url('posts'));
    }

    $post = ['id' => null, 'lang' => (string) ($_GET['lang'] ?? 'fa'), 'slug' => '', 'title' => '', 'excerpt' => '', 'body' => '', 'cover' => '', 'status' => 'draft', 'published_at' => now()];
    if ($id !== 'new') {
        $st = db()->prepare('SELECT * FROM posts WHERE id = ?');
        $st->execute([(int) $id]);
        $post = $st->fetch();
        if (!$post) {
            http_response_code(404);
            admin_render('notfound');
            return;
        }
    }
    if (!in_array($post['lang'], languages(), true)) {
        $post['lang'] = 'fa';
    }

    $errors = [];
    if ($method === 'POST') {
        require_post_csrf();
        $post['lang'] = in_array($_POST['lang'] ?? '', languages(), true) ? $_POST['lang'] : 'fa';
        $post['title'] = trim((string) ($_POST['title'] ?? ''));
        $post['slug'] = slugify(trim((string) ($_POST['slug'] ?? '')) ?: $post['title']);
        $post['excerpt'] = trim((string) ($_POST['excerpt'] ?? ''));
        $post['body'] = sanitize_html((string) ($_POST['body'] ?? ''));
        $post['status'] = ($_POST['status'] ?? '') === 'published' ? 'published' : 'draft';
        $pub = trim((string) ($_POST['published_at'] ?? ''));
        $post['published_at'] = $pub !== '' && strtotime(str_replace('T', ' ', $pub)) ? date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $pub))) : now();

        if (!empty($_POST['remove_cover'])) {
            $post['cover'] = '';
        }
        if (!empty($_FILES['cover']['name'])) {
            try {
                $post['cover'] = store_image_upload($_FILES['cover']);
            } catch (RuntimeException $ex) {
                $errors[] = $ex->getMessage();
            }
        }
        if ($post['title'] === '') {
            $errors[] = 'عنوان مطلب را وارد کنید.';
        }
        $st = db()->prepare('SELECT id FROM posts WHERE lang = ? AND slug = ? AND id <> ?');
        $st->execute([$post['lang'], $post['slug'], (int) ($post['id'] ?? 0)]);
        if ($st->fetch()) {
            $errors[] = 'نشانی (اسلاگ) تکراری است. یک نشانی دیگر انتخاب کنید.';
        }
        if (!$errors) {
            if ($post['id']) {
                db()->prepare('UPDATE posts SET lang=?, slug=?, title=?, excerpt=?, body=?, cover=?, status=?, published_at=?, updated_at=? WHERE id=?')
                    ->execute([$post['lang'], $post['slug'], $post['title'], $post['excerpt'], $post['body'], $post['cover'], $post['status'], $post['published_at'], now(), $post['id']]);
            } else {
                db()->prepare('INSERT INTO posts (lang, slug, title, excerpt, body, cover, status, published_at, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?,?,?)')
                    ->execute([$post['lang'], $post['slug'], $post['title'], $post['excerpt'], $post['body'], $post['cover'], $post['status'], $post['published_at'], now(), now()]);
                $post['id'] = (int) db()->lastInsertId();
            }
            flash('ok', $post['status'] === 'published' ? 'مطلب ذخیره و منتشر شد.' : 'پیش‌نویس ذخیره شد.');
            redirect(admin_url('posts/' . $post['id']));
        }
    }
    admin_render('post_edit', ['post' => $post, 'errors' => $errors]);
}

/** Store an uploaded catalog PDF under uploads/catalog/. Returns the public path or throws. */
function store_pdf_upload(array $file): string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('آپلود فایل کاتالوگ انجام نشد.');
    }
    if ($file['size'] > 30 * 1024 * 1024) {
        throw new RuntimeException('حجم کاتالوگ باید کمتر از ۳۰ مگابایت باشد.');
    }
    $head = (string) file_get_contents($file['tmp_name'], false, null, 0, 5);
    if ($head !== '%PDF-') {
        throw new RuntimeException('فقط فایل PDF قابل آپلود است.');
    }
    $dir = 'storage/catalog';   // not web-accessible: served only through /{lang}/catalog/download
    if (!is_dir(ROOT . '/' . $dir)) {
        mkdir(ROOT . '/' . $dir, 0775, true);
    }
    $name = 'rastdesk-catalog-' . date('Ymd') . '-' . bin2hex(random_bytes(4)) . '.pdf';
    if (!move_uploaded_file($file['tmp_name'], ROOT . '/' . $dir . '/' . $name)) {
        throw new RuntimeException('ذخیرهٔ فایل انجام نشد. دسترسی پوشهٔ uploads را بررسی کنید.');
    }
    return '/' . $dir . '/' . $name;
}

function admin_upload(): void
{
    header('Content-Type: application/json; charset=utf-8');
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST' || !csrf_ok()) {
        http_response_code(400);
        echo json_encode(['error' => 'درخواست نامعتبر است.'], JSON_UNESCAPED_UNICODE);
        return;
    }
    try {
        $path = store_image_upload($_FILES['image'] ?? []);
        echo json_encode(['url' => media_url($path)], JSON_UNESCAPED_UNICODE);
    } catch (RuntimeException $ex) {
        http_response_code(422);
        echo json_encode(['error' => $ex->getMessage()], JSON_UNESCAPED_UNICODE);
    }
}

/* ---------- settings ---------- */

function admin_setting_fields(): array
{
    return [
        'contact' => [
            'title' => 'اطلاعات تماس',
            'fields' => [
                'phone' => ['label' => 'تلفن', 'default' => '021-88100050', 'ltr' => true],
                'mobile' => ['label' => 'موبایل فروش', 'default' => '0912 601 8213', 'ltr' => true],
                'email' => ['label' => 'ایمیل', 'default' => 'info@rastdesk.ir', 'ltr' => true, 'type' => 'email'],
                'map_url' => ['label' => 'لینک نقشه (گوگل‌مپ یا نشان)', 'default' => '', 'ltr' => true, 'type' => 'url'],
                'rastin_url' => ['label' => 'نشانی سایت راستین', 'default' => 'https://rastingroup.ir', 'ltr' => true, 'type' => 'url'],
            ],
        ],
        'forms' => [
            'title' => 'ارسال فرم‌ها',
            'help' => 'همهٔ فرم‌ها در بخش «سرنخ‌ها و پیام‌ها» ذخیره می‌شوند. اگر نشانی مقصد وارد کنید، سرور سایت اطلاعات فرم را با درخواست POST به آن نشانی هم می‌فرستد (مثلاً وب‌هوک خود راست‌دسک). فرم «تماس سریع» صفحهٔ اصلی به نشانی فرم دمو ارسال می‌شود.',
            'fields' => [
                'demo_endpoint' => ['label' => 'نشانی مقصد فرم «درخواست دمو» و «تماس سریع»', 'default' => '', 'ltr' => true, 'type' => 'url', 'placeholder' => 'https://...'],
                'contact_endpoint' => ['label' => 'نشانی مقصد فرم «تماس با ما»', 'default' => '', 'ltr' => true, 'type' => 'url', 'placeholder' => 'https://...'],
                'catalog_endpoint' => ['label' => 'نشانی مقصد فرم «دانلود کاتالوگ»', 'default' => '', 'ltr' => true, 'type' => 'url', 'placeholder' => 'https://...'],
                'endpoint_format' => ['label' => 'قالب ارسال', 'default' => 'json', 'options' => ['json' => 'JSON', 'form' => 'فرم معمولی (x-www-form-urlencoded)']],
                'endpoint_token' => ['label' => 'توکن احراز هویت (اختیاری، به صورت Bearer ارسال می‌شود)', 'default' => '', 'ltr' => true],
            ],
        ],
        'editions' => [
            'title' => 'قیمت و سقف کاربر نسخه‌ها',
            'help' => 'فقط عدد وارد کنید. اگر قیمتی را خالی بگذارید، به‌جای آن «تماس با فروش» نمایش داده می‌شود.',
            'fields' => [
                'price_basic' => ['label' => 'قیمت نسخهٔ پایه (میلیون تومان)', 'default' => EDITION_DEFAULTS['basic']['price'], 'ltr' => true, 'number' => true],
                'users_basic' => ['label' => 'سقف کاربر نسخهٔ پایه', 'default' => EDITION_DEFAULTS['basic']['users'], 'ltr' => true, 'number' => true],
                'price_pro' => ['label' => 'قیمت نسخهٔ پرو (میلیون تومان)', 'default' => EDITION_DEFAULTS['pro']['price'], 'ltr' => true, 'number' => true],
                'users_pro' => ['label' => 'سقف کاربر نسخهٔ پرو', 'default' => EDITION_DEFAULTS['pro']['users'], 'ltr' => true, 'number' => true],
                'price_plus' => ['label' => 'قیمت نسخهٔ پلاس (میلیون تومان)', 'default' => EDITION_DEFAULTS['plus']['price'], 'ltr' => true, 'number' => true],
                'users_plus' => ['label' => 'سقف کاربر نسخهٔ پلاس', 'default' => EDITION_DEFAULTS['plus']['users'], 'ltr' => true, 'number' => true],
                'price_max' => ['label' => 'قیمت نسخهٔ مکس (میلیون تومان)', 'default' => EDITION_DEFAULTS['max']['price'], 'ltr' => true, 'number' => true],
                'users_max' => ['label' => 'سقف کاربر نسخهٔ مکس', 'default' => EDITION_DEFAULTS['max']['users'], 'ltr' => true, 'number' => true],
                'extra_user_price' => ['label' => 'قیمت هر کاربر اضافه (تومان)', 'default' => EXTRA_USER_DEFAULT, 'ltr' => true, 'number' => true],
            ],
        ],
        'catalog' => [
            'title' => 'کاتالوگ',
            'help' => 'اگر «با فرم» را انتخاب کنید، بازدیدکننده پیش از دانلود نام و شماره‌اش را ثبت می‌کند و در سرنخ‌ها می‌بینید. فایل جدید کاتالوگ را از پایین همین صفحه آپلود کنید.',
            'fields' => [
                'catalog_gate' => ['label' => 'دانلود کاتالوگ', 'default' => 'form', 'options' => ['form' => 'با فرم (دریافت نام و شماره)', 'direct' => 'دانلود مستقیم']],
            ],
        ],
        'social' => [
            'title' => 'شبکه‌های اجتماعی',
            'help' => 'هر کدام را خالی بگذارید، در سایت نمایش داده نمی‌شود.',
            'fields' => [
                'social_instagram' => ['label' => 'اینستاگرام', 'default' => SOCIAL_DEFAULTS['instagram'], 'ltr' => true, 'type' => 'url'],
                'social_linkedin' => ['label' => 'لینکدین', 'default' => '', 'ltr' => true, 'type' => 'url'],
                'social_telegram' => ['label' => 'تلگرام', 'default' => '', 'ltr' => true, 'type' => 'url'],
                'social_whatsapp' => ['label' => 'واتس‌اپ', 'default' => '', 'ltr' => true, 'type' => 'url'],
                'social_aparat' => ['label' => 'آپارات', 'default' => '', 'ltr' => true, 'type' => 'url'],
            ],
        ],
    ];
}

function admin_text_fields(): array
{
    return [
        'hero_title' => ['label' => 'تیتر اصلی صفحه‌ی خانه', 'key' => 'home.hero_title'],
        'hero_sub' => ['label' => 'متن زیر تیتر خانه', 'key' => 'home.hero_sub', 'textarea' => true],
        'tagline' => ['label' => 'شعار', 'key' => 'brand.tagline'],
        'meta_description' => ['label' => 'توضیح سایت برای گوگل', 'key' => 'meta.description', 'textarea' => true],
        'address' => ['label' => 'نشانی', 'key' => 'defaults.address', 'textarea' => true],
    ];
}

function admin_settings(string $method): void
{
    $groups = admin_setting_fields();
    $texts = admin_text_fields();
    if ($method === 'POST') {
        require_post_csrf();
        foreach ($groups as $g) {
            foreach ($g['fields'] as $key => $f) {
                $v = trim((string) ($_POST['s'][$key] ?? ''));
                if (isset($f['options']) && !isset($f['options'][$v])) {
                    $v = $f['default'];
                }
                if (($f['type'] ?? '') === 'url' && $v !== '' && !preg_match('#^https?://#i', $v)) {
                    $v = 'https://' . $v;
                }
                if (!empty($f['number'])) {
                    $v = preg_replace('/[^\d.]/', '', normalize_digits($v));
                }
                setting_save($key, $v);
            }
        }
        if (!empty($_FILES['catalog_pdf']['name'])) {
            try {
                setting_save('catalog_file', store_pdf_upload($_FILES['catalog_pdf']));
            } catch (RuntimeException $ex) {
                flash('error', $ex->getMessage());
            }
        }
        if (!empty($_POST['catalog_reset'])) {
            setting_save('catalog_file', '');
        }
        foreach ($texts as $key => $f) {
            foreach (languages() as $l) {
                $v = trim((string) ($_POST['t'][$key][$l] ?? ''));
                // Only store real overrides; identical-to-default text stays in the language file.
                setting_save($key . '.' . $l, $v === (string) t($f['key'], $l) ? '' : $v);
            }
        }
        flash('ok', 'تنظیمات ذخیره شد.');
        redirect(admin_url('settings'));
    }
    admin_render('settings', compact('groups', 'texts'));
}

/* ---------- messages ---------- */

function admin_messages(array $parts, string $method): void
{
    $id = $parts[1] ?? null;
    $action = $parts[2] ?? null;

    if ($id === 'export') {
        $rows = db()->query('SELECT * FROM submissions ORDER BY id DESC')->fetchAll();
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="rastdesk-leads-' . date('Y-m-d') . '.csv"');
        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM so Excel shows Persian correctly
        fputcsv($out, ['id', 'form', 'lang', 'created_at', 'name', 'company', 'position', 'phone', 'email', 'edition', 'users', 'deploy', 'module', 'message', 'forward_status']);
        foreach ($rows as $r) {
            $d = json_decode($r['data'], true) ?: [];
            fputcsv($out, [$r['id'], $r['form'], $r['lang'], $r['created_at'], $d['name'] ?? '', $d['company'] ?? '', $d['position'] ?? '', $d['phone'] ?? '', $d['email'] ?? '', $d['edition_label'] ?? '', $d['users_label'] ?? '', $d['deploy_label'] ?? '', $d['module_label'] ?? '', $d['message'] ?? '', $r['forward_status']]);
        }
        fclose($out);
        return;
    }

    if ($id === null) {
        $form = (string) ($_GET['form'] ?? '');
        $sql = 'SELECT * FROM submissions';
        $args = [];
        if (isset(form_types()[$form])) {
            $sql .= ' WHERE form = ?';
            $args[] = $form;
        }
        $st = db()->prepare($sql . ' ORDER BY id DESC LIMIT 500');
        $st->execute($args);
        admin_render('messages', ['rows' => $st->fetchAll(), 'filter' => $form]);
        return;
    }

    $st = db()->prepare('SELECT * FROM submissions WHERE id = ?');
    $st->execute([(int) $id]);
    $row = $st->fetch();
    if (!$row) {
        http_response_code(404);
        admin_render('notfound');
        return;
    }
    if ($action === 'delete') {
        require_post_csrf();
        db()->prepare('DELETE FROM submissions WHERE id = ?')->execute([(int) $id]);
        flash('ok', 'پیام حذف شد.');
        redirect(admin_url('messages'));
    }
    if ($action === 'resend') {
        require_post_csrf();
        $status = forward_submission($row['form'], $row['lang'], json_decode($row['data'], true) ?: [], (int) $row['id']);
        db()->prepare('UPDATE submissions SET forward_status = ? WHERE id = ?')->execute([$status, $row['id']]);
        flash(starts_with($status, 'sent') ? 'ok' : 'error', 'نتیجه‌ی ارسال دوباره: ' . $status);
        redirect(admin_url('messages/' . $row['id']));
    }
    if (!$row['is_read']) {
        db()->prepare('UPDATE submissions SET is_read = 1 WHERE id = ?')->execute([$row['id']]);
    }
    admin_render('message', ['row' => $row, 'data' => json_decode($row['data'], true) ?: []]);
}
