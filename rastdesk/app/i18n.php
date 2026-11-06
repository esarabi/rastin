<?php
declare(strict_types=1);

function languages(): array
{
    return config('languages', ['fa', 'en', 'ar', 'tr']);
}

function current_lang(?string $set = null): string
{
    static $lang = null;
    if ($set !== null) {
        $lang = $set;
    }
    return $lang ?? config('default_lang', 'fa');
}

function lang_pack(string $lang): array
{
    static $packs = [];
    if (!isset($packs[$lang])) {
        $file = ROOT . '/lang/' . $lang . '.php';
        $packs[$lang] = is_file($file) ? require $file : [];
    }
    return $packs[$lang];
}

/**
 * Translate by dotted key: t('nav.home'). Returns strings or arrays.
 * Falls back to the default language when a key is missing.
 */
function t(string $key, ?string $lang = null)
{
    $lang = $lang ?? current_lang();
    $v = lang_get(lang_pack($lang), $key);
    if ($v === null && $lang !== config('default_lang', 'fa')) {
        $v = lang_get(lang_pack(config('default_lang', 'fa')), $key);
    }
    return $v ?? $key;
}

function lang_get(array $pack, string $key)
{
    $cur = $pack;
    foreach (explode('.', $key) as $part) {
        if (!is_array($cur) || !array_key_exists($part, $cur)) {
            return null;
        }
        $cur = $cur[$part];
    }
    return $cur;
}

/** Editable text: admin override for this language, else the language file. */
function site_text(string $settingKey, string $langKey, ?string $lang = null): string
{
    $lang = $lang ?? current_lang();
    return setting($settingKey . '.' . $lang, (string) t($langKey, $lang));
}

function dir_attr(?string $lang = null): string
{
    return (string) t('meta.dir', $lang ?? current_lang());
}

function detect_browser_lang(): string
{
    $accept = strtolower((string) ($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? ''));
    foreach (explode(',', $accept) as $part) {
        $code = substr(trim($part), 0, 2);
        if (in_array($code, languages(), true)) {
            return $code;
        }
    }
    return config('default_lang', 'fa');
}
