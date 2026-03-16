-- ============================================================
--  Frindi Cafe — Database Setup
--  Import via phpMyAdmin atau: mysql -u root -p < database.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS db_cafe
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE db_cafe;

-- ------------------------------------------------------------
-- Tabel users (admin & kasir)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id         INT          AUTO_INCREMENT PRIMARY KEY,
    nama       VARCHAR(100) NOT NULL,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    role       ENUM('admin','kasir') NOT NULL DEFAULT 'kasir',
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabel kategori
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS kategori (
    id         INT          AUTO_INCREMENT PRIMARY KEY,
    nama       VARCHAR(100) NOT NULL,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabel barang (menu)
--   gambar: nama file saja (misal: menu_abc123.jpg)
--           disimpan di folder /img/menu/
--           nilai '' (string kosong) = belum ada gambar
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS barang (
    id           INT            AUTO_INCREMENT PRIMARY KEY,
    id_kategori  INT            DEFAULT NULL,
    nama         VARCHAR(100)   NOT NULL,
    harga        DECIMAL(10,2)  NOT NULL DEFAULT 0,
    stok         INT            NOT NULL DEFAULT 0,
    gambar       VARCHAR(255)   NOT NULL DEFAULT '',
    tersedia     TINYINT(1)     NOT NULL DEFAULT 1,
    created_at   TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_kategori) REFERENCES kategori(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabel transaksi
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS transaksi (
    id               INT            AUTO_INCREMENT PRIMARY KEY,
    kode_transaksi   VARCHAR(50)    NOT NULL UNIQUE,
    id_kasir         INT            DEFAULT NULL,
    nama_pelanggan   VARCHAR(100)   NOT NULL DEFAULT 'Umum',
    total            DECIMAL(10,2)  NOT NULL DEFAULT 0,
    bayar            DECIMAL(10,2)  NOT NULL DEFAULT 0,
    kembalian        DECIMAL(10,2)  NOT NULL DEFAULT 0,
    status           ENUM('lunas','pending','batal') NOT NULL DEFAULT 'lunas',
    created_at       TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_kasir) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabel detail transaksi
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS detail_transaksi (
    id            INT            AUTO_INCREMENT PRIMARY KEY,
    id_transaksi  INT            NOT NULL,
    id_barang     INT            DEFAULT NULL,
    nama_barang   VARCHAR(100)   NOT NULL,
    harga         DECIMAL(10,2)  NOT NULL DEFAULT 0,
    qty           INT            NOT NULL DEFAULT 1,
    subtotal      DECIMAL(10,2)  NOT NULL DEFAULT 0,
    FOREIGN KEY (id_transaksi) REFERENCES transaksi(id) ON DELETE CASCADE,
    FOREIGN KEY (id_barang)    REFERENCES barang(id)    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  Data Awal
-- ============================================================

-- Akun default (password: "password")
INSERT INTO users (nama, username, password, role) VALUES
('Administrator', 'admin',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Kasir Satu',   'kasir1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'kasir');

-- Kategori
INSERT INTO kategori (nama) VALUES
('Minuman'),
('Makanan'),
('Snack'),
('Dessert');

-- Menu (gambar kosong = tampil placeholder warna otomatis)
INSERT INTO barang (id_kategori, nama, harga, stok, gambar) VALUES
(1, 'Kopi Hitam',      15000, 100, ''),
(1, 'Kopi Susu',       20000, 100, ''),
(1, 'Es Teh Manis',    10000, 100, ''),
(1, 'Jus Alpukat',     25000,  50, ''),
(1, 'Matcha Latte',    28000,  50, ''),
(2, 'Nasi Goreng',     25000,  50, ''),
(2, 'Mie Goreng',      22000,  50, ''),
(2, 'Roti Bakar',      15000,  80, ''),
(3, 'Kentang Goreng',  18000,  60, ''),
(3, 'Pisang Goreng',   12000,  60, ''),
(4, 'Es Krim Cokelat', 20000,  40, ''),
(4, 'Puding Mangga',   15000,  40, '');
