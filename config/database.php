<?php
declare(strict_types=1);

function envConfig(string $key, string $default = ''): string
{
    static $config = null;
    if ($config === null) {
        $config = [];
        $path = dirname(__DIR__) . '/.env';
        if (is_file($path) && is_readable($path)) {
            foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
                $line = trim($line);
                if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) continue;
                [$name, $value] = explode('=', $line, 2);
                $config[trim($name)] = trim(trim($value), "\"'");
            }
        }
    }
    return (string) (getenv($key) !== false ? getenv($key) : ($config[$key] ?? $default));
}

function db(): PDO
{
    static $pdo;
    if (isset($pdo)) return $pdo;
    $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
        envConfig('DB_HOST', '127.0.0.1'), (int) envConfig('DB_PORT', '3306'),
        envConfig('DB_DATABASE', 'gymprogress_teste'));
    $pdo = new PDO($dsn, envConfig('DB_USERNAME', 'root'), envConfig('DB_PASSWORD'), [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    $pdo->exec("SET time_zone = '+00:00'");
    return $pdo;
}
