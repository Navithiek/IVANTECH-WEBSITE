<?php
// Simple .env loader and config access
function load_env(string $path = __DIR__ . '/../.env') {
    if (!file_exists($path)) return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        [$key, $val] = array_map('trim', explode('=', $line, 2) + [1 => '']);
        if ($key !== '') putenv(sprintf('%s=%s', $key, $val));
    }
}

load_env();

// helper accessor
function env(string $k, $default = null) {
    $v = getenv($k);
    return $v === false ? $default : $v;
}
