<?php
declare(strict_types=1);

function db(): PDO
{
    static $pdo = null;
    if ($pdo) {
        return $pdo;
    }
    $c = config('db');
    if (($c['driver'] ?? 'sqlite') === 'mysql') {
        $m = $c['mysql'];
        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $m['host'], (int) $m['port'], $m['name']);
        $pdo = new PDO($dsn, $m['user'], $m['pass']);
    } else {
        $path = $c['sqlite_path'];
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0775, true);
        }
        $pdo = new PDO('sqlite:' . $path);
        $pdo->exec('PRAGMA foreign_keys = ON');
        $pdo->exec('PRAGMA journal_mode = WAL');
    }
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    migrate($pdo);
    return $pdo;
}

function db_driver(): string
{
    return config('db')['driver'] ?? 'sqlite';
}

function migrate(PDO $pdo): void
{
    $mysql = db_driver() === 'mysql';
    $id = $mysql ? 'INT UNSIGNED AUTO_INCREMENT PRIMARY KEY' : 'INTEGER PRIMARY KEY AUTOINCREMENT';
    $text = $mysql ? 'MEDIUMTEXT' : 'TEXT';
    $engine = $mysql ? ' ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci' : '';

    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id $id,
        username VARCHAR(64) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        display_name VARCHAR(120) NOT NULL DEFAULT '',
        created_at VARCHAR(19) NOT NULL
    )$engine");

    $pdo->exec("CREATE TABLE IF NOT EXISTS settings (
        skey VARCHAR(100) NOT NULL PRIMARY KEY,
        svalue $text
    )$engine");

    $pdo->exec("CREATE TABLE IF NOT EXISTS posts (
        id $id,
        lang VARCHAR(5) NOT NULL,
        slug VARCHAR(190) NOT NULL,
        title VARCHAR(255) NOT NULL,
        excerpt TEXT,
        body $text,
        cover VARCHAR(255) NOT NULL DEFAULT '',
        status VARCHAR(10) NOT NULL DEFAULT 'draft',
        published_at VARCHAR(19),
        created_at VARCHAR(19) NOT NULL,
        updated_at VARCHAR(19) NOT NULL,
        UNIQUE (lang, slug)
    )$engine");

    $pdo->exec("CREATE TABLE IF NOT EXISTS submissions (
        id $id,
        form VARCHAR(20) NOT NULL,
        lang VARCHAR(5) NOT NULL,
        data $text NOT NULL,
        ip VARCHAR(45) NOT NULL DEFAULT '',
        forward_status VARCHAR(255) NOT NULL DEFAULT '',
        is_read SMALLINT NOT NULL DEFAULT 0,
        created_at VARCHAR(19) NOT NULL
    )$engine");

    $pdo->exec("CREATE TABLE IF NOT EXISTS attempts (
        id $id,
        kind VARCHAR(20) NOT NULL,
        ip VARCHAR(45) NOT NULL,
        created_at VARCHAR(19) NOT NULL
    )$engine");
}

function now(): string
{
    return date('Y-m-d H:i:s');
}

/* ---------- settings ---------- */

function settings_all(): array
{
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        foreach (db()->query('SELECT skey, svalue FROM settings') as $r) {
            $cache[$r['skey']] = (string) $r['svalue'];
        }
    }
    return $cache;
}

function setting(string $key, string $default = ''): string
{
    $all = settings_all();
    $v = $all[$key] ?? '';
    return $v !== '' ? $v : $default;
}

/** Like setting(), but an explicitly saved empty value stays empty (the default applies only when never saved). */
function setting_raw(string $key, string $default = ''): string
{
    $all = settings_all();
    return array_key_exists($key, $all) ? $all[$key] : $default;
}

function setting_save(string $key, string $value): void
{
    $pdo = db();
    $pdo->prepare('DELETE FROM settings WHERE skey = ?')->execute([$key]);
    $pdo->prepare('INSERT INTO settings (skey, svalue) VALUES (?, ?)')->execute([$key, $value]);
}

/* ---------- rate limiting ---------- */

function too_many_attempts(string $kind, int $max, int $minutes): bool
{
    $since = date('Y-m-d H:i:s', time() - $minutes * 60);
    db()->prepare('DELETE FROM attempts WHERE created_at < ?')->execute([date('Y-m-d H:i:s', time() - 86400)]);
    $st = db()->prepare('SELECT COUNT(*) FROM attempts WHERE kind = ? AND ip = ? AND created_at >= ?');
    $st->execute([$kind, client_ip(), $since]);
    return (int) $st->fetchColumn() >= $max;
}

function record_attempt(string $kind): void
{
    db()->prepare('INSERT INTO attempts (kind, ip, created_at) VALUES (?, ?, ?)')->execute([$kind, client_ip(), now()]);
}

function clear_attempts(string $kind): void
{
    db()->prepare('DELETE FROM attempts WHERE kind = ? AND ip = ?')->execute([$kind, client_ip()]);
}
