<?php
declare(strict_types=1);

/**
 * RastDesk lead forms: demo (full), quick (call-me-back on the landing page), contact, catalog (download gate).
 * Every submission is stored (admin → leads) and, when an address is set in the admin settings,
 * forwarded by the server to that address.
 */

function form_types(): array
{
    return [
        'demo' => 'درخواست دمو',
        'quick' => 'درخواست تماس سریع',
        'contact' => 'تماس با ما',
        'catalog' => 'دانلود کاتالوگ',
    ];
}

function form_fields(string $form): array
{
    $opt = fn(string $key) => array_keys((array) t($key));
    switch ($form) {
        case 'demo':
            return [
                'name' => ['required' => true, 'max' => 120],
                'company' => ['required' => true, 'max' => 160],
                'position' => ['required' => false, 'max' => 120],
                'phone' => ['required' => true, 'max' => 30, 'type' => 'phone'],
                'email' => ['required' => false, 'max' => 160, 'type' => 'email'],
                'edition' => ['required' => false, 'options' => $opt('demo.editions')],
                'users' => ['required' => false, 'options' => $opt('demo.users')],
                'deploy' => ['required' => false, 'options' => $opt('demo.deploy')],
                'module' => ['required' => false, 'options' => array_keys(modules_data()['modules'])],
                'message' => ['required' => false, 'max' => 3000],
            ];
        case 'quick':
            return [
                'name' => ['required' => true, 'max' => 120],
                'phone' => ['required' => true, 'max' => 30, 'type' => 'phone'],
                'company' => ['required' => false, 'max' => 160],
            ];
        case 'catalog':
            return [
                'name' => ['required' => true, 'max' => 120],
                'phone' => ['required' => true, 'max' => 30, 'type' => 'phone'],
                'company' => ['required' => false, 'max' => 160],
                'email' => ['required' => false, 'max' => 160, 'type' => 'email'],
            ];
        default:
            return [
                'name' => ['required' => true, 'max' => 120],
                'company' => ['required' => false, 'max' => 160],
                'phone' => ['required' => true, 'max' => 30, 'type' => 'phone'],
                'email' => ['required' => false, 'max' => 160, 'type' => 'email'],
                'message' => ['required' => true, 'max' => 3000],
            ];
    }
}

function normalize_digits(string $s): string
{
    return strtr($s, [
        '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
        '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
    ]);
}

/**
 * Handle a POST to a form. Returns [errors, old values] on failure; redirects to $backUrl (+ $anchor) on success.
 */
function handle_form(string $form, string $lang, string $backUrl, string $anchor = '#form'): array
{
    $fields = form_fields($form);
    $old = [];
    $errors = [];

    if (!csrf_ok()) {
        return [['_form' => t('form.err_expired')], $_POST];
    }
    if (trim((string) ($_POST['website'] ?? '')) !== '') {   // honeypot
        flash('form_success_' . $form, true);
        redirect($backUrl . $anchor);
    }
    if (too_many_attempts('form', 8, 10)) {
        return [['_form' => t('form.err_rate')], $_POST];
    }

    foreach ($fields as $name => $rule) {
        $v = trim((string) ($_POST[$name] ?? ''));
        $v = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $v);
        $old[$name] = $v;
        if ($v === '') {
            if ($rule['required']) {
                $errors[$name] = t('form.err_required');
            }
            continue;
        }
        if (isset($rule['max']) && mb_strlen($v) > $rule['max']) {
            $old[$name] = $v = mb_substr($v, 0, $rule['max']);
        }
        if (isset($rule['options']) && !in_array($v, $rule['options'], true)) {
            $old[$name] = '';
            if ($rule['required']) {
                $errors[$name] = t('form.err_required');
            }
        }
        $type = $rule['type'] ?? '';
        if ($type === 'email' && !filter_var($v, FILTER_VALIDATE_EMAIL)) {
            $errors[$name] = t('form.err_email');
        }
        if ($type === 'phone') {
            $digitsOnly = preg_replace('/\D/', '', normalize_digits($v));
            if (strlen($digitsOnly) < 7 || strlen($digitsOnly) > 15) {
                $errors[$name] = t('form.err_phone');
            } else {
                $old[$name] = normalize_digits($v);
            }
        }
    }
    if ($errors) {
        $errors['_form'] = t('form.err_general');
        return [$errors, $old];
    }

    record_attempt('form');
    $data = array_filter($old, fn($v) => $v !== '');
    // human-readable labels for the selected options (always in Persian, for the sales team)
    foreach (['edition' => 'demo.editions', 'users' => 'demo.users', 'deploy' => 'demo.deploy'] as $f => $key) {
        if (!empty($data[$f])) {
            $data[$f . '_label'] = (string) (t($key, 'fa')[$data[$f]] ?? $data[$f]);
        }
    }
    if (!empty($data['module'])) {
        $data['module_label'] = (string) (modules_data('fa')['modules'][$data['module']]['title'] ?? $data['module']);
    }
    $data['page'] = absolute_url($backUrl);

    $id = save_submission($form, $lang, $data);
    $status = forward_submission($form, $lang, $data, $id);
    db()->prepare('UPDATE submissions SET forward_status = ? WHERE id = ?')->execute([$status, $id]);

    flash('form_success_' . $form, true);
    redirect($backUrl . $anchor);
    return [[], []];
}

function save_submission(string $form, string $lang, array $data): int
{
    db()->prepare('INSERT INTO submissions (form, lang, data, ip, forward_status, is_read, created_at) VALUES (?, ?, ?, ?, ?, 0, ?)')
        ->execute([$form, $lang, json_encode($data, JSON_UNESCAPED_UNICODE), client_ip(), '', now()]);
    return (int) db()->lastInsertId();
}

/** Send the submission to the address set in the admin panel. Quick call requests use the demo address. */
function forward_submission(string $form, string $lang, array $data, int $id): string
{
    $key = ($form === 'quick' ? 'demo' : $form) . '_endpoint';
    $endpoint = trim(setting($key));
    if ($endpoint === '') {
        return 'stored';
    }
    if (!preg_match('#^https?://#i', $endpoint)) {
        return 'error: invalid endpoint';
    }
    if (!function_exists('curl_init')) {
        return 'error: curl missing';
    }
    $payload = ['form' => $form, 'product' => 'rastdesk', 'lang' => $lang, 'id' => $id, 'submitted_at' => now()] + $data;
    $asJson = setting('endpoint_format', 'json') === 'json';

    $ch = curl_init($endpoint);
    $headers = ['Accept: application/json'];
    $token = setting('endpoint_token');
    if ($token !== '') {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    if ($asJson) {
        $headers[] = 'Content-Type: application/json; charset=utf-8';
        $body = json_encode($payload, JSON_UNESCAPED_UNICODE);
    } else {
        $body = http_build_query($payload);
    }
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_FOLLOWLOCATION => false,
    ]);
    curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);
    if ($err !== '') {
        return 'error: ' . mb_substr($err, 0, 200);
    }
    return ($code >= 200 && $code < 300) ? 'sent ' . $code : 'error: HTTP ' . $code;
}
