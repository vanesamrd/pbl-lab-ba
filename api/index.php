<?php

// Router jembatan untuk Vercel Serverless
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Rute untuk folder admin
if (strpos($uri, '/admin') === 0) {
    $path = $uri;
    if ($path === '/admin' || $path === '/admin/') {
        $path = '/admin/dashboard.php';
    }
    
    $file = __DIR__ . '/..' . $path;
    if (!file_exists($file) && file_exists($file . '.php')) {
        $file = $file . '.php';
    }
    
    if (file_exists($file) && is_file($file)) {
        chdir(dirname($file));
        require $file;
        exit;
    }
}

// Rute untuk folder public
if ($uri === '/' || $uri === '') {
    $file = __DIR__ . '/../public/index.php';
} else {
    $file = __DIR__ . '/../public' . $uri;
    if (!file_exists($file) && file_exists($file . '.php')) {
        $file = $file . '.php';
    }
}

if (file_exists($file) && is_file($file)) {
    chdir(dirname($file));
    require $file;
    exit;
}

// Fallback ke halaman utama public/index.php
chdir(__DIR__ . '/../public');
require __DIR__ . '/../public/index.php';
