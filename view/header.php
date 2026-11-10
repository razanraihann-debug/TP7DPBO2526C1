<?php
// Komponen modular: layout atas (sidebar + judul halaman).
// Butuh variabel: $pageTitle, $pageSub (opsional). Pastikan init.php sudah dimuat.
$current = basename($_SERVER['SCRIPT_NAME']);
$menu = [
    'peminjaman.php' => ['clipboard', 'Peminjaman'],
    'alat.php'       => ['package', 'Alat'],
    'kategori.php'   => ['tag', 'Kategori'],
    'peminjam.php'   => ['users', 'Peminjam'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle ?? 'Peminjaman Alat') ?> · Sistem Peminjaman Alat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?php $cssFile = __DIR__ . '/../style/style.css'; ?>
    <link rel="stylesheet" href="../style/style.css?v=<?= file_exists($cssFile) ? filemtime($cssFile) : time() ?>">
</head>
<body>
<aside class="sidebar">
    <div class="brand">
        <div class="brand-logo"><?= icon('package', 20) ?></div>
        <div>
            <div class="brand-name">PinjamAlat</div>
            <div class="brand-sub">Manajemen peminjaman</div>
        </div>
    </div>
    <div class="menu-label">Menu</div>
    <nav>
        <?php foreach ($menu as $file => [$ic, $label]): ?>
            <a href="<?= $file ?>" class="<?= $current === $file ? 'active' : '' ?>">
                <?= icon($ic) ?><span><?= $label ?></span>
            </a>
        <?php endforeach; ?>
    </nav>
</aside>

<main class="main">
    <header class="page-head">
        <h1><?= e($pageTitle ?? '') ?></h1>
        <p><?= e($pageSub ?? '') ?></p>
    </header>

    <?php if (isset($_GET['msg'])): $isErr = ($_GET['type'] ?? 'ok') === 'err'; ?>
        <div class="alert <?= $isErr ? 'alert-err' : 'alert-ok' ?>" id="flash">
            <?= icon($isErr ? 'alert' : 'check') ?><span><?= e($_GET['msg']) ?></span>
        </div>
    <?php endif; ?>
