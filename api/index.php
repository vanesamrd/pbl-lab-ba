<?php

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$uri = urldecode($uri);

// Normalisasi jika request mengandung awalan /lab-ba atau /lab-ba/public
if (str_starts_with($uri, '/lab-ba/public')) {
    $uri = substr($uri, strlen('/lab-ba/public'));
} elseif (str_starts_with($uri, '/lab-ba')) {
    $uri = substr($uri, strlen('/lab-ba'));
}

// Jika akses ke root (/)
if ($uri === '' || $uri === '/' || $uri === '/index.php') {
    chdir(__DIR__ . '/../public');
    require __DIR__ . '/../public/index.php';
    exit;
}

// Jika akses ke area admin
if (str_starts_with($uri, '/admin')) {
    $adminFile = realpath(__DIR__ . '/..' . $uri);
    
    // Jika berupa direktori, cari index.php atau dashboard.php
    if (is_dir($adminFile)) {
        if (file_exists($adminFile . '/dashboard.php')) {
            $adminFile .= '/dashboard.php';
        } elseif (file_exists($adminFile . '/index.php')) {
            $adminFile .= '/index.php';
        }
    } elseif (!file_exists($adminFile) && file_exists($adminFile . '.php')) {
        $adminFile .= '.php';
    }

    if ($adminFile && file_exists($adminFile)) {
        chdir(dirname($adminFile));
        require $adminFile;
        exit;
    }
}

// Cek file di folder public
$publicFile = realpath(__DIR__ . '/../public' . $uri);
if (!$publicFile && file_exists(__DIR__ . '/../public' . $uri . '.php')) {
    $publicFile = realpath(__DIR__ . '/../public' . $uri . '.php');
}

if ($publicFile && file_exists($publicFile)) {
    chdir(dirname($publicFile));
    require $publicFile;
    exit;
}

// Fallback jika tidak ditemukan
chdir(__DIR__ . '/../public');
require __DIR__ . '/../public/index.php';
