# ☕ Aplikasi Cafe - Frindi Cafe

Sistem kasir dan manajemen cafe berbasis PHP + MySQL.

## Fitur
- 🏠 Halaman publik dengan tampilan menu
- 🔐 Login multi-role (Admin & Kasir)
- 🛒 Sistem kasir / POS (Point of Sale)
- 📦 Manajemen menu & kategori
- 👥 Manajemen pengguna
- 📊 Laporan transaksi harian dengan grafik
- 🖨️ Cetak struk transaksi

## Persyaratan
- PHP >= 7.4
- MySQL / MariaDB
- Apache dengan mod_rewrite aktif (XAMPP/WAMP/Laragon)

## Instalasi

### 1. Letakkan folder di htdocs (XAMPP)
```
C:/xampp/htdocs/aplikasi-cafe/
```

### 2. Import database
- Buka phpMyAdmin → http://localhost/phpmyadmin
- Buat database baru: `db_cafe`
- Import file `database.sql`

### 3. Konfigurasi koneksi
Edit `php/koneksi.php` sesuai pengaturan database Anda:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');        // isi password MySQL jika ada
define('DB_NAME', 'db_cafe');
```

### 4. Aktifkan mod_rewrite
Pastikan `mod_rewrite` aktif di Apache (XAMPP: xampp-control → Apache → Config → httpd.conf, cari `AllowOverride None` → ganti `AllowOverride All`)

### 5. Akses aplikasi
- Halaman utama : http://localhost/aplikasi-cafe/
- Login         : http://localhost/aplikasi-cafe/login

## Akun Default
| Role  | Username | Password |
|-------|----------|----------|
| Admin | admin    | password |
| Kasir | kasir1   | password |

## Struktur Folder
```
aplikasi-cafe/
├── css/style.css
├── img/
│   ├── bg.jpg
│   ├── logo.jpg
│   └── menu/          ← gambar menu upload
├── js/script.js
├── php/
│   ├── koneksi.php    ← konfigurasi database
│   ├── main.php       ← handler AJAX
│   └── cekSesi.php
├── vendor/            ← Bootstrap & jQuery
├── view/
│   ├── home.php       ← halaman publik
│   ├── login.php
│   ├── kasir.php      ← halaman kasir/POS
│   ├── 404.php
│   └── admin/
│       ├── index.php       ← dashboard
│       ├── kategori.php
│       ├── barang.php      ← manajemen menu
│       ├── harian.php      ← laporan harian
│       ├── pengguna.php
│       └── template/
│           ├── header.php
│           └── footer.php
├── database.sql
├── index.php          ← router utama
└── .htaccess
```
