<?php
// php/main.php - dipanggil langsung via AJAX, session_start() di sini sendiri
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/koneksi.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'login':
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $stmt = $koneksi->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if ($result && password_verify($password, $result['password'])) {
            $_SESSION['user_id'] = $result['id'];
            $_SESSION['nama']    = $result['nama'];
            $_SESSION['role']    = $result['role'];
            echo json_encode(['status' => 'ok', 'role' => $result['role']]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Username atau password salah']);
        }
        break;

    case 'logout':
        session_destroy();
        echo json_encode(['status' => 'ok']);
        break;

    case 'getBarang':
        $id_kategori = intval($_GET['id_kategori'] ?? 0);
        if ($id_kategori > 0) {
            $stmt = $koneksi->prepare("SELECT b.*, k.nama as nama_kategori FROM barang b LEFT JOIN kategori k ON b.id_kategori = k.id WHERE b.tersedia = 1 AND b.id_kategori = ? ORDER BY b.nama");
            $stmt->bind_param("i", $id_kategori);
        } else {
            $stmt = $koneksi->prepare("SELECT b.*, k.nama as nama_kategori FROM barang b LEFT JOIN kategori k ON b.id_kategori = k.id WHERE b.tersedia = 1 ORDER BY k.nama, b.nama");
        }
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        echo json_encode(['status' => 'ok', 'data' => $rows]);
        break;

    case 'getAllBarang':
        $stmt = $koneksi->prepare("SELECT b.*, k.nama as nama_kategori FROM barang b LEFT JOIN kategori k ON b.id_kategori = k.id ORDER BY k.nama, b.nama");
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        echo json_encode(['status' => 'ok', 'data' => $rows]);
        break;

    case 'tambahBarang':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { echo json_encode(['status'=>'error','message'=>'Akses ditolak']); break; }
        $nama = trim($_POST['nama'] ?? '');
        $harga = floatval($_POST['harga'] ?? 0);
        $stok = intval($_POST['stok'] ?? 0);
        $id_kategori = intval($_POST['id_kategori'] ?? 0);
        $gambar = '';
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
            $allowed = ['jpg','jpeg','png','gif','webp'];
            $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                echo json_encode(['status'=>'error','message'=>'Format gambar tidak didukung. Gunakan jpg/png/gif/webp']); break;
            }
            $dir = __DIR__ . '/../img/menu/';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $nama_file = uniqid('menu_') . '.' . $ext;
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $dir . $nama_file)) {
                $gambar = $nama_file;
            }
        }
        $stmt = $koneksi->prepare("INSERT INTO barang (id_kategori, nama, harga, stok, gambar) VALUES (?,?,?,?,?)");
        $stmt->bind_param("isdis", $id_kategori, $nama, $harga, $stok, $gambar);
        $stmt->execute();
        echo json_encode(['status' => 'ok', 'message' => 'Barang berhasil ditambahkan']);
        break;

    case 'editBarang':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { echo json_encode(['status'=>'error','message'=>'Akses ditolak']); break; }
        $id          = intval($_POST['id'] ?? 0);
        $nama        = trim($_POST['nama'] ?? '');
        $harga       = floatval($_POST['harga'] ?? 0);
        $stok        = intval($_POST['stok'] ?? 0);
        $id_kategori = intval($_POST['id_kategori'] ?? 0);
        $tersedia    = intval($_POST['tersedia'] ?? 1);

        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
            $allowed = ['jpg','jpeg','png','gif','webp'];
            $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                echo json_encode(['status'=>'error','message'=>'Format gambar tidak didukung. Gunakan jpg/png/gif/webp']); break;
            }
            $dir = __DIR__ . '/../img/menu/';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $old = $koneksi->query("SELECT gambar FROM barang WHERE id=$id")->fetch_assoc();
            if ($old && $old['gambar'] && file_exists($dir . $old['gambar'])) {
                unlink($dir . $old['gambar']);
            }
            $nama_file = uniqid('menu_') . '.' . $ext;
            if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $dir . $nama_file)) {
                echo json_encode(['status'=>'error','message'=>'Gagal menyimpan gambar. Cek permission folder img/menu/']); break;
            }
            $stmt = $koneksi->prepare("UPDATE barang SET id_kategori=?, nama=?, harga=?, stok=?, tersedia=?, gambar=? WHERE id=?");
            $stmt->bind_param("isdiisi", $id_kategori, $nama, $harga, $stok, $tersedia, $nama_file, $id);
        } else {
            $stmt = $koneksi->prepare("UPDATE barang SET id_kategori=?, nama=?, harga=?, stok=?, tersedia=? WHERE id=?");
            $stmt->bind_param("isdiii", $id_kategori, $nama, $harga, $stok, $tersedia, $id);
        }
        $stmt->execute();
        echo json_encode(['status' => 'ok', 'message' => 'Barang berhasil diupdate']);
        break;

    case 'hapusGambarBarang':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { echo json_encode(['status'=>'error','message'=>'Akses ditolak']); break; }
        $id = intval($_POST['id'] ?? 0);
        $row = $koneksi->query("SELECT gambar FROM barang WHERE id=$id")->fetch_assoc();
        if ($row && $row['gambar']) {
            $path = __DIR__ . '/../img/menu/' . $row['gambar'];
            if (file_exists($path)) unlink($path);
        }
        $koneksi->query("UPDATE barang SET gambar='' WHERE id=$id");
        echo json_encode(['status'=>'ok','message'=>'Gambar berhasil dihapus']);
        break;

    case 'hapusBarang':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { echo json_encode(['status'=>'error','message'=>'Akses ditolak']); break; }
        $id = intval($_POST['id'] ?? 0);
        $stmt = $koneksi->prepare("DELETE FROM barang WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo json_encode(['status' => 'ok', 'message' => 'Barang berhasil dihapus']);
        break;

    case 'getKategori':
        $stmt = $koneksi->prepare("SELECT * FROM kategori ORDER BY nama");
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        echo json_encode(['status' => 'ok', 'data' => $rows]);
        break;

    case 'tambahKategori':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { echo json_encode(['status'=>'error','message'=>'Akses ditolak']); break; }
        $nama = trim($_POST['nama'] ?? '');
        $stmt = $koneksi->prepare("INSERT INTO kategori (nama) VALUES (?)");
        $stmt->bind_param("s", $nama);
        $stmt->execute();
        echo json_encode(['status' => 'ok', 'message' => 'Kategori berhasil ditambahkan']);
        break;

    case 'editKategori':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { echo json_encode(['status'=>'error','message'=>'Akses ditolak']); break; }
        $id = intval($_POST['id'] ?? 0);
        $nama = trim($_POST['nama'] ?? '');
        $stmt = $koneksi->prepare("UPDATE kategori SET nama=? WHERE id=?");
        $stmt->bind_param("si", $nama, $id);
        $stmt->execute();
        echo json_encode(['status' => 'ok', 'message' => 'Kategori berhasil diupdate']);
        break;

    case 'hapusKategori':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { echo json_encode(['status'=>'error','message'=>'Akses ditolak']); break; }
        $id = intval($_POST['id'] ?? 0);
        $stmt = $koneksi->prepare("DELETE FROM kategori WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo json_encode(['status' => 'ok', 'message' => 'Kategori berhasil dihapus']);
        break;

    case 'simpanTransaksi':
        if (!isset($_SESSION['user_id'])) { echo json_encode(['status'=>'error','message'=>'Silakan login']); break; }
        $items = json_decode($_POST['items'] ?? '[]', true);
        $nama_pelanggan = trim($_POST['nama_pelanggan'] ?? 'Umum');
        $bayar = floatval($_POST['bayar'] ?? 0);
        $total = 0;
        foreach ($items as $item) { $total += $item['harga'] * $item['qty']; }
        $kembalian = $bayar - $total;
        if ($kembalian < 0) { echo json_encode(['status'=>'error','message'=>'Uang bayar kurang']); break; }
        $kode = 'TRX-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
        $id_kasir = $_SESSION['user_id'];
        $stmt = $koneksi->prepare("INSERT INTO transaksi (kode_transaksi, id_kasir, nama_pelanggan, total, bayar, kembalian) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("sissdd", $kode, $id_kasir, $nama_pelanggan, $total, $bayar, $kembalian);
        $stmt->execute();
        $id_transaksi = $koneksi->insert_id;
        foreach ($items as $item) {
            $subtotal = $item['harga'] * $item['qty'];
            $id_barang = intval($item['id']);
            $nama_b = $item['nama'];
            $harga_b = floatval($item['harga']);
            $qty = intval($item['qty']);
            $stmt2 = $koneksi->prepare("INSERT INTO detail_transaksi (id_transaksi, id_barang, nama_barang, harga, qty, subtotal) VALUES (?,?,?,?,?,?)");
            $stmt2->bind_param("iisdid", $id_transaksi, $id_barang, $nama_b, $harga_b, $qty, $subtotal);
            $stmt2->execute();
            $koneksi->query("UPDATE barang SET stok = stok - $qty WHERE id = $id_barang AND stok >= $qty");
        }
        echo json_encode(['status'=>'ok','kode'=>$kode,'total'=>$total,'kembalian'=>$kembalian]);
        break;

    case 'getLaporanHarian':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { echo json_encode(['status'=>'error','message'=>'Akses ditolak']); break; }
        $tanggal = $_GET['tanggal'] ?? date('Y-m-d');
        $stmt = $koneksi->prepare("SELECT t.*, u.nama as nama_kasir FROM transaksi t LEFT JOIN users u ON t.id_kasir = u.id WHERE DATE(t.created_at) = ? AND t.status = 'lunas' ORDER BY t.created_at DESC");
        $stmt->bind_param("s", $tanggal);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $total = array_sum(array_column($rows, 'total'));
        echo json_encode(['status'=>'ok','data'=>$rows,'total'=>$total]);
        break;

    case 'getDetailTransaksi':
        if (!isset($_SESSION['user_id'])) { echo json_encode(['status'=>'error','message'=>'Akses ditolak']); break; }
        $id = intval($_GET['id'] ?? 0);
        $stmt = $koneksi->prepare("SELECT * FROM detail_transaksi WHERE id_transaksi = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        echo json_encode(['status'=>'ok','data'=>$rows]);
        break;

    case 'getDashboard':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { echo json_encode(['status'=>'error','message'=>'Akses ditolak']); break; }
        $hari_ini = date('Y-m-d');
        $pendapatan    = $koneksi->query("SELECT COALESCE(SUM(total),0) as total FROM transaksi WHERE DATE(created_at)='$hari_ini' AND status='lunas'")->fetch_assoc()['total'];
        $trx_hari      = $koneksi->query("SELECT COUNT(*) as jml FROM transaksi WHERE DATE(created_at)='$hari_ini' AND status='lunas'")->fetch_assoc()['jml'];
        $total_barang  = $koneksi->query("SELECT COUNT(*) as jml FROM barang WHERE tersedia=1")->fetch_assoc()['jml'];
        $total_kategori= $koneksi->query("SELECT COUNT(*) as jml FROM kategori")->fetch_assoc()['jml'];
        $grafik = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = date('Y-m-d', strtotime("-$i days"));
            $val = $koneksi->query("SELECT COALESCE(SUM(total),0) as total FROM transaksi WHERE DATE(created_at)='$tgl' AND status='lunas'")->fetch_assoc()['total'];
            $grafik[] = ['tanggal' => date('d/m', strtotime($tgl)), 'total' => floatval($val)];
        }
        echo json_encode(['status'=>'ok','pendapatan'=>floatval($pendapatan),'transaksi'=>intval($trx_hari),'barang'=>intval($total_barang),'kategori'=>intval($total_kategori),'grafik'=>$grafik]);
        break;

    case 'getUsers':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { echo json_encode(['status'=>'error','message'=>'Akses ditolak']); break; }
        $rows = $koneksi->query("SELECT id, nama, username, role, created_at FROM users ORDER BY nama")->fetch_all(MYSQLI_ASSOC);
        echo json_encode(['status'=>'ok','data'=>$rows]);
        break;

    case 'tambahUser':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { echo json_encode(['status'=>'error','message'=>'Akses ditolak']); break; }
        $nama     = trim($_POST['nama'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = password_hash($_POST['password'] ?? 'password', PASSWORD_DEFAULT);
        $role     = $_POST['role'] ?? 'kasir';
        $stmt = $koneksi->prepare("INSERT INTO users (nama, username, password, role) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $nama, $username, $password, $role);
        if ($stmt->execute()) {
            echo json_encode(['status'=>'ok','message'=>'User berhasil ditambahkan']);
        } else {
            echo json_encode(['status'=>'error','message'=>'Username sudah digunakan']);
        }
        break;

    case 'hapusUser':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { echo json_encode(['status'=>'error','message'=>'Akses ditolak']); break; }
        $id = intval($_POST['id'] ?? 0);
        if ($id === intval($_SESSION['user_id'])) { echo json_encode(['status'=>'error','message'=>'Tidak dapat menghapus akun sendiri']); break; }
        $stmt = $koneksi->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo json_encode(['status'=>'ok','message'=>'User berhasil dihapus']);
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Action tidak dikenali: ' . $action]);
        break;
}
