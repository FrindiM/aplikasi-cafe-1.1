<?php $base = defined('BASE') ? BASE : ''; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link rel="stylesheet" href="<?= $base ?>/vendor/bootstrap/css/bootstrap.min.css">
    <style>
        body { display:flex; align-items:center; justify-content:center; min-height:100vh; background:#f0f4f8; }
        h1 { font-size:5rem; color:#2d6a4f; font-weight:900; }
    </style>
</head>
<body>
<div class="text-center">
    <h1>404</h1>
    <p class="text-muted fs-5">Halaman tidak ditemukan</p>
    <a href="<?= $base ?>/home" class="btn btn-success mt-2">Kembali ke Beranda</a>
</div>
</body>
</html>
