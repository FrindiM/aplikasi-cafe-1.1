<?php $base = defined('BASE') ? BASE : ''; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Frindi Cafe</title>
    <link rel="stylesheet" href="<?= $base ?>/vendor/bootstrap/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Lato:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --espresso:#3B1F0E; --darkroast:#6B3A2A; --sienna:#A0522D;
            --caramel:#C8855A; --latte:#DBA882; --cream:#EDD5B3;
            --milkfoam:#F5E6D0; --offwhite:#FBF5ED;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #f2ebe3; font-family: 'Lato', sans-serif; }

        /* Sidebar */
        .sidebar {
            width: 252px; min-height: 100vh;
            background: var(--espresso);
            position: fixed; top: 0; left: 0; z-index: 100;
            display: flex; flex-direction: column;
        }
        .sidebar .brand {
            padding: 22px 20px 18px;
            border-bottom: 1px solid rgba(237,213,179,0.12);
            text-align: center;
        }
        .sidebar .brand img {
            width: 54px; height: 54px; border-radius: 50%; object-fit: cover;
            border: 2px solid var(--caramel); margin-bottom: 8px;
        }
        .sidebar .brand h5 {
            color: var(--cream); margin: 0;
            font-family: 'Playfair Display', serif; font-size: 1rem; font-weight: 600;
        }
        .sidebar .brand small { color: var(--latte); font-size: 0.75rem; }
        .sidebar nav { padding: 10px 0; flex: 1; }
        .sidebar nav a {
            display: flex; align-items: center; gap: 12px;
            padding: 13px 22px;
            color: rgba(245,230,208,0.7);
            text-decoration: none; font-size: 0.9rem;
            transition: 0.2s; border-left: 3px solid transparent;
        }
        .sidebar nav a:hover, .sidebar nav a.active {
            color: var(--cream);
            background: rgba(200,133,90,0.15);
            border-left-color: var(--caramel);
        }
        .sidebar nav a i { width: 18px; text-align: center; font-size: 0.95rem; }
        .sidebar .nav-divider {
            border-top: 1px solid rgba(237,213,179,0.1);
            margin: 8px 0;
        }

        /* Main content */
        .main-content { margin-left: 252px; padding: 22px; min-height: 100vh; }

        /* Topbar */
        .topbar {
            background: white; border-radius: 14px;
            padding: 14px 22px;
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 22px;
            box-shadow: 0 2px 10px rgba(59,31,14,0.07);
            border-bottom: 3px solid var(--cream);
        }
        .topbar h4 {
            margin: 0; color: var(--espresso);
            font-family: 'Playfair Display', serif; font-weight: 600; font-size: 1.2rem;
        }
        .topbar .user-info { color: var(--sienna); font-size: 0.85rem; }
        .btn-logout {
            background: var(--milkfoam); color: var(--darkroast);
            border: 1.5px solid var(--latte); border-radius: 8px;
            padding: 7px 16px; cursor: pointer; transition: 0.2s;
            font-size: 0.85rem; font-weight: 600;
        }
        .btn-logout:hover { background: var(--darkroast); color: var(--milkfoam); border-color: var(--darkroast); }

        /* Cards dalam admin */
        .content-card {
            background: white; border-radius: 14px; padding: 24px;
            box-shadow: 0 2px 10px rgba(59,31,14,0.07);
            border-top: 3px solid var(--cream);
        }

        /* Tabel */
        .table thead.tbl-head th {
            background: var(--milkfoam); color: var(--espresso);
            font-weight: 700; border-bottom: 2px solid var(--cream);
        }
        .table tbody tr:hover { background: var(--offwhite); }

        /* Buttons */
        .btn-cafe-primary { background: var(--sienna); color: var(--milkfoam); border: none; border-radius: 8px; }
        .btn-cafe-primary:hover { background: var(--darkroast); color: var(--milkfoam); }

        /* Badges */
        .badge-kat { background: var(--milkfoam); color: var(--darkroast); border-radius: 20px; padding: 4px 12px; font-size: 0.8rem; font-weight: 600; }

        /* Modal */
        .modal-cafe .modal-header { background: var(--espresso); color: var(--cream); }
        .modal-cafe .modal-header .btn-close { filter: invert(1); }
        .modal-cafe .btn-primary { background: var(--sienna); border-color: var(--sienna); }
        .modal-cafe .btn-primary:hover { background: var(--darkroast); border-color: var(--darkroast); }
        .modal-cafe .form-control:focus,.modal-cafe .form-select:focus {
            border-color: var(--caramel); box-shadow: 0 0 0 3px rgba(200,133,90,0.18);
        }

        @media(max-width:768px){
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
        }
    </style>
    <script src="<?= $base ?>/vendor/jquery/jquery-3.7.1.min.js"></script>
</head>
<body>
<div class="sidebar">
    <div class="brand">
        <img src="<?= $base ?>/img/logo.jpg" alt="Logo">
        <h5>Frindi Cafe</h5>
        <small>Panel Admin</small>
    </div>
    <nav>
        <a href="<?= $base ?>/admin"    id="nav-dashboard"><i class="fa fa-gauge"></i> Dashboard</a>
        <a href="<?= $base ?>/kategori" id="nav-kategori"><i class="fa fa-tags"></i> Kategori</a>
        <a href="<?= $base ?>/barang"   id="nav-barang"><i class="fa fa-mug-hot"></i> Menu / Barang</a>
        <a href="<?= $base ?>/lHarian"  id="nav-laporan"><i class="fa fa-chart-bar"></i> Laporan Harian</a>
        <a href="<?= $base ?>/pengguna" id="nav-pengguna"><i class="fa fa-users"></i> Pengguna</a>
        <div class="nav-divider"></div>
        <a href="<?= $base ?>/kasir"><i class="fa fa-cash-register"></i> Buka Kasir</a>
    </nav>
</div>
<div class="main-content">
    <div class="topbar">
        <h4 id="pageTitle">Dashboard</h4>
        <div class="d-flex align-items-center gap-3">
            <span class="user-info"><i class="fa fa-circle-user me-1"></i><?php echo isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama']) : 'Admin'; ?></span>
            <button class="btn-logout" id="btnLogout"><i class="fa fa-sign-out-alt me-1"></i>Logout</button>
        </div>
    </div>
    <div id="mainContent">
