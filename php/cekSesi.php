<?php
// php/cekSesi.php
// session_start() sudah dipanggil di index.php
if (!isset($_SESSION['user_id'])) {
    // BASE didefinisikan di index.php
    $base = defined('BASE') ? BASE : '';
    header('Location: ' . $base . '/login');
    exit();
}
