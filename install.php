<?php
/**
 * install.php — Installer Database Frindi Cafe
 * Akses: http://localhost/aplikasi-cafe/install.php
 *
 * ⚠️  HAPUS file ini setelah instalasi selesai!
 */

// ─── Konfigurasi — sesuaikan jika berbeda ──────────────────────────────────
$CFG = [
    'host'    => 'localhost',
    'user'    => 'root',
    'pass'    => '',
    'dbname'  => 'db_cafe',
    'charset' => 'utf8mb4',
];
// ───────────────────────────────────────────────────────────────────────────

$logs   = [];   // log tiap langkah
$errors = [];   // error yang terjadi

// ─── Jalankan instalasi saat form di-submit ─────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['install'])) {

    $host  = trim($_POST['db_host']  ?? $CFG['host']);
    $user  = trim($_POST['db_user']  ?? $CFG['user']);
    $pass  = $_POST['db_pass']       ?? $CFG['pass'];
    $dbname= trim($_POST['db_name']  ?? $CFG['dbname']);
    $seed  = isset($_POST['seed_data']);   // isi data contoh?
    $reset = isset($_POST['reset_data']);  // hapus tabel lama?

    // 1. Koneksi tanpa memilih database dulu
    $db = new mysqli($host, $user, $pass);
    if ($db->connect_error) {
        $errors[] = 'Koneksi gagal: ' . $db->connect_error;
    } else {
        $db->set_charset('utf8mb4');
        $logs[] = ['ok', "Koneksi ke MySQL berhasil ($host)"];

        // 2. Buat database jika belum ada
        if ($db->query("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
            $logs[] = ['ok', "Database `$dbname` siap"];
        } else {
            $errors[] = 'Gagal membuat database: ' . $db->error;
        }

        $db->select_db($dbname);

        // 3. Nonaktifkan foreign key check sementara
        $db->query("SET FOREIGN_KEY_CHECKS = 0");

        // 4. Hapus tabel lama jika reset dipilih
        if ($reset) {
            $tables = ['detail_transaksi', 'transaksi', 'barang', 'kategori', 'users'];
            foreach ($tables as $tbl) {
                if ($db->query("DROP TABLE IF EXISTS `$tbl`")) {
                    $logs[] = ['warn', "Tabel `$tbl` dihapus"];
                }
            }
        }

        // 5. Buat tabel
        $queries = [

            // users
            "CREATE TABLE IF NOT EXISTS `users` (
                `id`         INT          NOT NULL AUTO_INCREMENT,
                `nama`       VARCHAR(100) NOT NULL,
                `username`   VARCHAR(50)  NOT NULL,
                `password`   VARCHAR(255) NOT NULL,
                `role`       ENUM('admin','kasir') NOT NULL DEFAULT 'kasir',
                `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `uq_username` (`username`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            // kategori
            "CREATE TABLE IF NOT EXISTS `kategori` (
                `id`         INT          NOT NULL AUTO_INCREMENT,
                `nama`       VARCHAR(100) NOT NULL,
                `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            // barang
            "CREATE TABLE IF NOT EXISTS `barang` (
                `id`          INT           NOT NULL AUTO_INCREMENT,
                `id_kategori` INT           DEFAULT NULL,
                `nama`        VARCHAR(100)  NOT NULL,
                `harga`       DECIMAL(10,2) NOT NULL DEFAULT 0,
                `stok`        INT           NOT NULL DEFAULT 0,
                `gambar`      VARCHAR(255)  NOT NULL DEFAULT '',
                `tersedia`    TINYINT(1)    NOT NULL DEFAULT 1,
                `created_at`  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `fk_barang_kategori` (`id_kategori`),
                CONSTRAINT `fk_barang_kategori`
                    FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id`)
                    ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            // transaksi
            "CREATE TABLE IF NOT EXISTS `transaksi` (
                `id`              INT           NOT NULL AUTO_INCREMENT,
                `kode_transaksi`  VARCHAR(50)   NOT NULL,
                `id_kasir`        INT           DEFAULT NULL,
                `nama_pelanggan`  VARCHAR(100)  NOT NULL DEFAULT 'Umum',
                `total`           DECIMAL(10,2) NOT NULL DEFAULT 0,
                `bayar`           DECIMAL(10,2) NOT NULL DEFAULT 0,
                `kembalian`       DECIMAL(10,2) NOT NULL DEFAULT 0,
                `status`          ENUM('lunas','pending','batal') NOT NULL DEFAULT 'lunas',
                `created_at`      TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `uq_kode` (`kode_transaksi`),
                KEY `fk_trx_kasir` (`id_kasir`),
                CONSTRAINT `fk_trx_kasir`
                    FOREIGN KEY (`id_kasir`) REFERENCES `users` (`id`)
                    ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            // detail_transaksi
            "CREATE TABLE IF NOT EXISTS `detail_transaksi` (
                `id`           INT           NOT NULL AUTO_INCREMENT,
                `id_transaksi` INT           NOT NULL,
                `id_barang`    INT           DEFAULT NULL,
                `nama_barang`  VARCHAR(100)  NOT NULL,
                `harga`        DECIMAL(10,2) NOT NULL DEFAULT 0,
                `qty`          INT           NOT NULL DEFAULT 1,
                `subtotal`     DECIMAL(10,2) NOT NULL DEFAULT 0,
                PRIMARY KEY (`id`),
                KEY `fk_detail_trx` (`id_transaksi`),
                KEY `fk_detail_barang` (`id_barang`),
                CONSTRAINT `fk_detail_trx`
                    FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id`)
                    ON DELETE CASCADE,
                CONSTRAINT `fk_detail_barang`
                    FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id`)
                    ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        ];

        $tableNames = ['users', 'kategori', 'barang', 'transaksi', 'detail_transaksi'];
        foreach ($queries as $i => $sql) {
            if ($db->query($sql)) {
                $logs[] = ['ok', "Tabel `{$tableNames[$i]}` berhasil dibuat / sudah ada"];
            } else {
                $errors[] = "Gagal buat tabel `{$tableNames[$i]}`: " . $db->error;
            }
        }

        // 6. Aktifkan kembali foreign key
        $db->query("SET FOREIGN_KEY_CHECKS = 1");

        // 7. Isi data awal jika diminta
        if ($seed && empty($errors)) {

            // Cek apakah users sudah ada isinya
            $cek = $db->query("SELECT COUNT(*) as n FROM users")->fetch_assoc();
            if ($cek['n'] == 0) {
                $hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // "password"
                $db->query("INSERT INTO users (nama, username, password, role) VALUES
                    ('Administrator', 'admin',  '$hash', 'admin'),
                    ('Kasir Satu',   'kasir1', '$hash', 'kasir')");
                $logs[] = ['ok', 'Akun default dibuat (admin & kasir1, password: <strong>password</strong>)'];
            } else {
                $logs[] = ['info', 'Tabel users sudah berisi data, akun default dilewati'];
            }

            $cek2 = $db->query("SELECT COUNT(*) as n FROM kategori")->fetch_assoc();
            if ($cek2['n'] == 0) {
                $db->query("INSERT INTO kategori (nama) VALUES ('Minuman'),('Makanan'),('Snack'),('Dessert')");
                $logs[] = ['ok', '4 kategori contoh ditambahkan'];

                $db->query("INSERT INTO barang (id_kategori, nama, harga, stok, gambar) VALUES
                    (1,'Kopi Hitam',     15000,100,''),
                    (1,'Kopi Susu',      20000,100,''),
                    (1,'Es Teh Manis',   10000,100,''),
                    (1,'Jus Alpukat',    25000, 50,''),
                    (1,'Matcha Latte',   28000, 50,''),
                    (2,'Nasi Goreng',    25000, 50,''),
                    (2,'Mie Goreng',     22000, 50,''),
                    (2,'Roti Bakar',     15000, 80,''),
                    (3,'Kentang Goreng', 18000, 60,''),
                    (3,'Pisang Goreng',  12000, 60,''),
                    (4,'Es Krim Cokelat',20000, 40,''),
                    (4,'Puding Mangga',  15000, 40,'')");
                $logs[] = ['ok', '12 menu contoh ditambahkan'];
            } else {
                $logs[] = ['info', 'Tabel kategori sudah berisi data, data contoh dilewati'];
            }
        }

        // 8. Update koneksi.php otomatis
        $koneksiPath = __DIR__ . '/php/koneksi.php';
        $koneksiContent = "<?php\n"
            . "// php/koneksi.php — di-generate otomatis oleh install.php\n"
            . "define('DB_HOST', " . var_export($host,   true) . ");\n"
            . "define('DB_USER', " . var_export($user,   true) . ");\n"
            . "define('DB_PASS', " . var_export($pass,   true) . ");\n"
            . "define('DB_NAME', " . var_export($dbname, true) . ");\n\n"
            . "\$koneksi = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);\n\n"
            . "if (\$koneksi->connect_error) {\n"
            . "    die(json_encode(['status' => 'error', 'message' => 'Koneksi database gagal: ' . \$koneksi->connect_error]));\n"
            . "}\n\n"
            . "\$koneksi->set_charset('utf8mb4');\n";

        if (file_put_contents($koneksiPath, $koneksiContent)) {
            $logs[] = ['ok', 'File <code>php/koneksi.php</code> diperbarui otomatis'];
        } else {
            $logs[] = ['warn', 'Tidak bisa memperbarui <code>php/koneksi.php</code> secara otomatis — perbarui manual'];
        }

        // 9. Buat folder img/menu jika belum ada
        $imgDir = __DIR__ . '/img/menu';
        if (!is_dir($imgDir)) {
            mkdir($imgDir, 0755, true);
            $logs[] = ['ok', 'Folder <code>img/menu/</code> dibuat'];
        } else {
            $logs[] = ['info', 'Folder <code>img/menu/</code> sudah ada'];
        }

        $db->close();
    }

    $success = empty($errors);
}

$showForm = !isset($success);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Installer — Frindi Cafe</title>
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: linear-gradient(135deg, #3B1F0E 0%, #6B3A2A 55%, #A0522D 100%);
    min-height: 100vh;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding: 30px 16px 60px;
}
.box {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    width: 100%;
    max-width: 620px;
    overflow: hidden;
}
.box-header {
    background: #3B1F0E;
    color: white;
    padding: 28px 32px 22px;
    text-align: center;
}
.box-header .logo { font-size: 2.5rem; display: block; margin-bottom: 6px; }
.box-header h1 { font-size: 1.4rem; font-weight: 700; margin-bottom: 4px; }
.box-header p  { font-size: 0.85rem; opacity: 0.75; }
.box-body { padding: 28px 32px; }

/* Form */
.form-group { margin-bottom: 18px; }
label { display: block; font-weight: 600; font-size: 0.88rem; color: #333; margin-bottom: 6px; }
input[type=text], input[type=password] {
    width: 100%; padding: 10px 14px;
    border: 1.5px solid #d0d5dd; border-radius: 8px;
    font-size: 0.95rem; transition: border-color 0.2s;
    outline: none;
}
input[type=text]:focus, input[type=password]:focus { border-color: #A0522D; box-shadow: 0 0 0 3px rgba(45,106,79,0.12); }
.row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

/* Checkbox group */
.check-group {
    background: #FBF5ED;
    border: 1.5px solid #DBA882;
    border-radius: 10px;
    padding: 14px 18px;
    margin-bottom: 18px;
}
.check-group h3 { font-size: 0.88rem; color: #3B1F0E; font-weight: 700; margin-bottom: 10px; }
.check-item { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 10px; cursor: pointer; }
.check-item:last-child { margin-bottom: 0; }
.check-item input[type=checkbox] { margin-top: 2px; width: 16px; height: 16px; accent-color: #A0522D; cursor: pointer; flex-shrink: 0; }
.check-item .check-label { font-size: 0.88rem; color: #333; }
.check-item .check-label strong { display: block; margin-bottom: 2px; }
.check-item .check-label small { color: #777; font-size: 0.8rem; }

/* Warning box */
.warn-box {
    background: #fff8e1;
    border: 1.5px solid #f59e0b;
    border-radius: 10px;
    padding: 12px 16px;
    margin-bottom: 20px;
    font-size: 0.85rem;
    color: #78350f;
    display: flex;
    gap: 10px;
    align-items: flex-start;
}
.warn-box .icon { font-size: 1.1rem; flex-shrink: 0; margin-top: 1px; }

/* Button */
.btn-install {
    width: 100%;
    background: #A0522D;
    color: white;
    border: none;
    border-radius: 10px;
    padding: 14px;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.2s;
    letter-spacing: 0.3px;
}
.btn-install:hover { background: #3B1F0E; }
.btn-install:disabled { background: #aaa; cursor: not-allowed; }

/* Log hasil */
.log-list { list-style: none; margin-bottom: 20px; }
.log-list li {
    padding: 9px 14px;
    border-radius: 8px;
    font-size: 0.875rem;
    margin-bottom: 6px;
    display: flex;
    align-items: flex-start;
    gap: 8px;
    line-height: 1.4;
}
.log-ok   { background: #f0fdf4; color: #3B1F0E; }
.log-warn { background: #fefce8; color: #854d0e; }
.log-info { background: #eff6ff; color: #1e40af; }
.log-err  { background: #fef2f2; color: #991b1b; }
.log-icon { flex-shrink: 0; font-size: 0.95rem; margin-top: 1px; }

/* Result banner */
.result-banner {
    border-radius: 12px;
    padding: 18px 20px;
    margin-bottom: 22px;
    text-align: center;
}
.result-banner.success { background: #F5E6D0; border: 2px solid #DBA882; color: #3B1F0E; }
.result-banner.failed  { background: #fee2e2; border: 2px solid #fca5a5; color: #7f1d1d; }
.result-banner h2 { font-size: 1.2rem; margin-bottom: 6px; }
.result-banner p  { font-size: 0.85rem; opacity: 0.85; }

/* Links */
.link-btn {
    display: inline-block;
    padding: 10px 22px;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    transition: 0.2s;
    margin: 4px;
}
.link-btn.green { background: #A0522D; color: white; }
.link-btn.green:hover { background: #3B1F0E; }
.link-btn.outline { border: 2px solid #A0522D; color: #A0522D; }
.link-btn.outline:hover { background: #f0fdf4; }

.divider { border: none; border-top: 1px solid #e5e7eb; margin: 20px 0; }
.footer-note {
    text-align: center;
    font-size: 0.78rem;
    color: #999;
    margin-top: 10px;
}
.footer-note strong { color: #e63946; }
</style>
</head>
<body>
<div class="box">
    <div class="box-header">
        <span class="logo">☕</span>
        <h1>Frindi Cafe — Installer</h1>
        <p>Setup database pertama kali atau reset ke kondisi awal</p>
    </div>
    <div class="box-body">

    <?php if ($showForm): ?>
    <!-- ── FORM ─────────────────────────────────────────────── -->
    <div class="warn-box">
        <span class="icon">⚠️</span>
        <div>Halaman ini hanya untuk keperluan instalasi.
        <strong>Hapus file <code>install.php</code> setelah selesai</strong> agar tidak dapat diakses oleh orang lain.</div>
    </div>

    <form method="POST" id="installForm">
        <h3 style="font-size:0.95rem;color:#1b4332;margin-bottom:14px;font-weight:700">⚙️ Konfigurasi Database</h3>
        <div class="row2">
            <div class="form-group">
                <label for="db_host">Host MySQL</label>
                <input type="text" id="db_host" name="db_host" value="<?= htmlspecialchars($CFG['host']) ?>" required>
            </div>
            <div class="form-group">
                <label for="db_name">Nama Database</label>
                <input type="text" id="db_name" name="db_name" value="<?= htmlspecialchars($CFG['dbname']) ?>" required>
            </div>
            <div class="form-group">
                <label for="db_user">Username MySQL</label>
                <input type="text" id="db_user" name="db_user" value="<?= htmlspecialchars($CFG['user']) ?>" required>
            </div>
            <div class="form-group">
                <label for="db_pass">Password MySQL</label>
                <input type="password" id="db_pass" name="db_pass" value="<?= htmlspecialchars($CFG['pass']) ?>" placeholder="(kosong jika tidak ada)">
            </div>
        </div>

        <hr class="divider">

        <div class="check-group">
            <h3>🔧 Opsi Instalasi</h3>
            <label class="check-item">
                <input type="checkbox" name="seed_data" value="1" checked>
                <span class="check-label">
                    <strong>Isi data contoh</strong>
                    <small>Tambahkan akun admin/kasir, 4 kategori, dan 12 menu awal (hanya jika tabel masih kosong)</small>
                </span>
            </label>
            <label class="check-item" id="resetWrap">
                <input type="checkbox" name="reset_data" value="1" id="resetCheck">
                <span class="check-label">
                    <strong>⚠️ Hapus & buat ulang semua tabel</strong>
                    <small>Semua data yang ada (transaksi, menu, dll) akan <strong style="color:#e63946">dihapus permanen</strong>. Gunakan hanya untuk reset total.</small>
                </span>
            </label>
        </div>

        <button type="submit" name="install" class="btn-install" id="btnInstall">
            🚀 Mulai Instalasi
        </button>
    </form>

    <?php else: ?>
    <!-- ── HASIL ─────────────────────────────────────────────── -->

    <?php if ($success): ?>
    <div class="result-banner success">
        <h2>✅ Instalasi Berhasil!</h2>
        <p>Database sudah siap digunakan. Silakan hapus file <code>install.php</code> sekarang.</p>
    </div>
    <?php else: ?>
    <div class="result-banner failed">
        <h2>❌ Instalasi Gagal</h2>
        <p>Terjadi error. Periksa log di bawah dan coba lagi.</p>
    </div>
    <?php endif; ?>

    <!-- Log -->
    <ul class="log-list">
        <?php foreach ($logs as [$type, $msg]): ?>
        <li class="log-<?= $type ?>">
            <span class="log-icon"><?= $type==='ok' ? '✓' : ($type==='warn' ? '⚠' : ($type==='info' ? 'ℹ' : '✗')) ?></span>
            <span><?= $msg ?></span>
        </li>
        <?php endforeach; ?>
        <?php foreach ($errors as $err): ?>
        <li class="log-err"><span class="log-icon">✗</span><span><?= htmlspecialchars($err) ?></span></li>
        <?php endforeach; ?>
    </ul>

    <?php if ($success): ?>
    <div style="text-align:center;margin-bottom:16px">
        <a href="index.php" class="link-btn green">🏠 Buka Aplikasi</a>
        <a href="login" class="link-btn outline">🔑 Login Sekarang</a>
    </div>
    <p class="footer-note">
        <strong>🗑 Ingat: hapus file install.php setelah ini!</strong>
    </p>
    <?php else: ?>
    <div style="text-align:center">
        <a href="install.php" class="link-btn outline">↩ Coba Lagi</a>
    </div>
    <?php endif; ?>

    <?php endif; // end showForm ?>

    </div><!-- box-body -->
</div><!-- box -->

<script>
// Konfirmasi jika pilih reset
document.getElementById('installForm') && document.getElementById('installForm').addEventListener('submit', function(e) {
    var reset = document.getElementById('resetCheck');
    if (reset && reset.checked) {
        if (!confirm('⚠️ PERINGATAN!\n\nSemua tabel dan data lama akan DIHAPUS PERMANEN.\n\nLanjutkan?')) {
            e.preventDefault();
            return;
        }
    }
    var btn = document.getElementById('btnInstall');
    btn.disabled = true;
    btn.textContent = '⏳ Memproses...';
});
</script>
</body>
</html>
