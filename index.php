<?php
// index.php - Router utama

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Deteksi BASE PATH otomatis (mendukung subfolder maupun root)
// Contoh: jika diakses di http://localhost/aplikasi-cafe/ maka BASE = /aplikasi-cafe
$scriptDir = dirname($_SERVER['SCRIPT_NAME']); // e.g. /aplikasi-cafe atau /
define('BASE', rtrim($scriptDir, '/'));         // e.g. /aplikasi-cafe atau ''

// Ambil bagian path setelah BASE
$uri      = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$relative = ltrim(substr($uri, strlen(BASE)), '/'); // e.g. "login", "kasir", ""

// Kalau kosong atau hanya nama folder, tampilkan home
if ($relative === '' || $relative === basename(BASE)) {
    $relative = 'home';
}

// Fungsi redirect yang sadar subfolder
function redirect($path) {
    header('Location: ' . BASE . '/' . ltrim($path, '/'));
    exit();
}

switch ($relative) {
    case 'home':
        include 'view/home.php';
        exit();

    case 'login':
        include 'view/login.php';
        exit();

    case 'kasir':
        if (!isset($_SESSION['user_id'])) { redirect('login'); }
        include 'view/kasir.php';
        exit();

    case 'admin':
        if (!isset($_SESSION['user_id'])) { redirect('login'); }
        if ($_SESSION['role'] !== 'admin') { redirect('kasir'); }
        include 'view/admin/template/header.php';
        include 'view/admin/index.php';
        include 'view/admin/template/footer.php';
        exit();

    case 'kategori':
        if (!isset($_SESSION['user_id'])) { redirect('login'); }
        if ($_SESSION['role'] !== 'admin') { redirect('kasir'); }
        include 'view/admin/template/header.php';
        include 'view/admin/kategori.php';
        include 'view/admin/template/footer.php';
        exit();

    case 'barang':
        if (!isset($_SESSION['user_id'])) { redirect('login'); }
        if ($_SESSION['role'] !== 'admin') { redirect('kasir'); }
        include 'view/admin/template/header.php';
        include 'view/admin/barang.php';
        include 'view/admin/template/footer.php';
        exit();

    case 'lHarian':
        if (!isset($_SESSION['user_id'])) { redirect('login'); }
        if ($_SESSION['role'] !== 'admin') { redirect('kasir'); }
        include 'view/admin/template/header.php';
        include 'view/admin/harian.php';
        include 'view/admin/template/footer.php';
        exit();

    case 'pengguna':
        if (!isset($_SESSION['user_id'])) { redirect('login'); }
        if ($_SESSION['role'] !== 'admin') { redirect('kasir'); }
        include 'view/admin/template/header.php';
        include 'view/admin/pengguna.php';
        include 'view/admin/template/footer.php';
        exit();

    default:
        include 'view/404.php';
        exit();
}
