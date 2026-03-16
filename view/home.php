<?php $base = defined('BASE') ? BASE : ''; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frindi Cafe</title>
    <link rel="stylesheet" href="<?= $base ?>/vendor/bootstrap/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Gloria+Hallelujah&family=Permanent+Marker&family=Playfair+Display:wght@400;600&family=Lato:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= $base ?>/css/style.css">
    <style>
        :root {
            --espresso:  #3B1F0E;
            --darkroast: #6B3A2A;
            --sienna:    #A0522D;
            --caramel:   #C8855A;
            --latte:     #DBA882;
            --cream:     #EDD5B3;
            --milkfoam:  #F5E6D0;
            --offwhite:  #FBF5ED;
        }
        body { font-family: 'Lato', sans-serif; }

        /* Navbar */
        .cafe-navbar {
            background: var(--espresso) !important;
            position: sticky; top: 0; z-index: 100;
            box-shadow: 0 2px 12px rgba(59,31,14,0.3);
        }
        .cafe-navbar .navbar-brand { color: var(--cream) !important; font-family: 'Playfair Display', serif; font-size: 1.25rem; }
        .cafe-navbar .nav-link { color: rgba(245,230,208,0.8) !important; transition: 0.2s; }
        .cafe-navbar .nav-link:hover { color: var(--cream) !important; }
        .cafe-navbar .btn-login-nav {
            background: var(--caramel); color: var(--espresso) !important;
            border-radius: 20px; padding: 6px 18px; font-weight: 600;
        }
        .cafe-navbar .btn-login-nav:hover { background: var(--latte); }

        /* Section title */
        .section-title {
            font-family: 'Playfair Display', serif;
            color: var(--espresso);
            text-align: center;
            margin: 36px 0 8px;
            font-size: 2rem;
        }
        .section-subtitle {
            text-align: center; color: var(--sienna);
            margin-bottom: 28px; font-size: 0.95rem; letter-spacing: 0.5px;
        }

        /* Filter pills */
        .filter-wrap { display:flex; justify-content:center; flex-wrap:wrap; gap:8px; margin-bottom:28px; }
        .filter-pill {
            border: 2px solid var(--latte);
            color: var(--sienna);
            background: white;
            border-radius: 25px;
            padding: 6px 20px;
            cursor: pointer;
            transition: 0.2s;
            font-weight: 600;
            font-size: 0.88rem;
        }
        .filter-pill.active, .filter-pill:hover {
            background: var(--sienna);
            border-color: var(--sienna);
            color: var(--milkfoam);
        }

        /* Menu card */
        .menu-card {
            border: none !important;
            border-radius: 16px !important;
            overflow: hidden;
            box-shadow: 0 3px 14px rgba(59,31,14,0.1);
            transition: 0.25s;
            background: white;
        }
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 28px rgba(59,31,14,0.18);
        }
        .menu-card .card-img-top { height: 190px; object-fit: cover; }
        .menu-card .card-body { background: white; }
        .menu-card .card-title { font-family: 'Playfair Display', serif; color: var(--espresso); font-size: 1rem; margin-bottom: 6px; }
        .menu-card .harga { color: var(--sienna); font-weight: 700; font-size: 1rem; }
        .kat-badge {
            background: var(--milkfoam);
            color: var(--darkroast);
            border-radius: 20px; padding: 2px 12px;
            font-size: 0.75rem; font-weight: 600;
            width: fit-content; margin-bottom: 8px;
        }

        /* Tentang section */
        .tentang-section { background: var(--milkfoam); border-radius: 20px; padding: 50px 30px; margin: 40px 0; }

        /* Footer */
        footer {
            background: var(--espresso);
            color: var(--cream);
            text-align: center;
            padding: 22px;
            font-size: 0.9rem;
        }

        /* Background tinted untuk section */
        .section { background: var(--offwhite) !important; }
    </style>
</head>
<body>
    <!-- Hero -->
    <div class="row hero align-items-center text-center g-0">
        <div class="sapaan row justify-content-center">
            <div class="col-10">
                <h1>Selamat Datang</h1>
                <h4>Di Frindi Cafe</h4>
                <p>Nikmati sajian terbaik kami untuk harimu ☕</p>
            </div>
        </div>
        <div class="row g-0">
            <div class="col">
                <button class="btnBuka">Lihat Menu</button>
            </div>
        </div>
    </div>

    <!-- Section Menu -->
    <div class="section" id="sectionMenu">
        <nav class="navbar navbar-expand-lg cafe-navbar">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                    <img src="<?= $base ?>/img/logo.jpg" alt="Logo" width="36" height="36"
                         class="rounded-circle" style="object-fit:cover;border:2px solid var(--caramel)">
                    Frindi Cafe
                </a>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                    <span style="color:var(--cream);font-size:1.3rem">&#9776;</span>
                </button>
                <div class="collapse navbar-collapse" id="navMenu">
                    <ul class="navbar-nav ms-auto align-items-center gap-2">
                        <li class="nav-item"><a class="nav-link" href="#sectionMenu">Menu</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
                        <li class="nav-item">
                            <a class="nav-link btn-login-nav" href="<?= $base ?>/login">
                                Login Kasir
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container py-4">
            <h2 class="section-title">Menu Kami</h2>
            <p class="section-subtitle">Pilihan minuman &amp; makanan lezat pilihan barista kami</p>
            <div class="filter-wrap" id="filterKategori">
                <button class="filter-pill active" data-id="0">Semua</button>
            </div>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4" id="menuGrid">
                <div class="col-12 text-center py-5">
                    <div class="spinner-border" style="color:var(--caramel)"></div>
                </div>
            </div>
        </div>

        <div class="container" id="tentang">
            <div class="tentang-section">
                <div class="row align-items-center g-4">
                    <div class="col-md-5 text-center">
                        <img src="<?= $base ?>/img/logo.jpg" alt="Logo" class="rounded-circle"
                             style="width:200px;height:200px;object-fit:cover;border:6px solid var(--caramel);box-shadow:0 10px 30px rgba(59,31,14,0.2)">
                    </div>
                    <div class="col-md-7">
                        <h3 style="font-family:'Playfair Display',serif;color:var(--espresso);margin-bottom:12px">Tentang Kami</h3>
                        <p style="color:var(--darkroast);line-height:1.7">Frindi Cafe adalah tempat ngopi dan bersantai yang menyajikan berbagai minuman dan makanan lezat dengan suasana yang nyaman. Kami berkomitmen memberikan pengalaman terbaik bagi setiap pelanggan.</p>
                        <div class="d-flex gap-4 mt-3">
                            <div class="text-center">
                                <div style="font-size:1.6rem;font-weight:700;color:var(--sienna)" id="jmlMenu">-</div>
                                <small style="color:var(--darkroast)">Menu Tersedia</small>
                            </div>
                            <div class="text-center">
                                <div style="font-size:1.6rem;font-weight:700;color:var(--sienna)" id="jmlKat">-</div>
                                <small style="color:var(--darkroast)">Kategori</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer>
            <p class="mb-0">© <?php echo date('Y'); ?> Frindi Cafe — Dibuat dengan ☕ dan ❤️</p>
        </footer>
    </div>

    <script src="<?= $base ?>/vendor/jquery/jquery-3.7.1.min.js"></script>
    <script src="<?= $base ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
    var BASE = '<?= $base ?>';
    function rupiah(n){ return 'Rp ' + parseInt(n).toLocaleString('id-ID'); }
    var allMenu = [];
    var _colors = ['#6B3A2A','#A0522D','#7B4F3A','#8B5E3C','#5C3317','#9B6B4A','#C8855A','#6F4E37'];
    function _getColor(s){ var h=0; for(var i=0;i<s.length;i++) h=s.charCodeAt(i)+((h<<5)-h); return _colors[Math.abs(h)%_colors.length]; }
    function makePlaceholder(nama,kat,w,h){
        var c=_getColor(kat||nama);
        var ini=nama.split(' ').slice(0,2).map(function(x){return x[0];}).join('').toUpperCase();
        var svg='<svg xmlns="http://www.w3.org/2000/svg" width="'+w+'" height="'+h+'"><rect width="100%" height="100%" fill="'+c+'"/><text x="50%" y="44%" dominant-baseline="middle" text-anchor="middle" font-family="Georgia,serif" font-size="'+Math.floor(h*0.3)+'" font-weight="bold" fill="rgba(245,230,208,0.9)">'+ini+'</text><text x="50%" y="72%" dominant-baseline="middle" text-anchor="middle" font-family="Arial,sans-serif" font-size="'+Math.floor(h*0.18)+'" fill="rgba(237,213,179,0.6)">\u2615</text></svg>';
        return 'data:image/svg+xml;charset=utf-8,'+encodeURIComponent(svg);
    }

    function loadKategori(){
        $.get(BASE+'/php/main.php?action=getKategori', function(res){
            res.data.forEach(function(k){
                $('#filterKategori').append('<button class="filter-pill" data-id="'+k.id+'">'+k.nama+'</button>');
            });
            $('#jmlKat').text(res.data.length);
            bindFilter();
        },'json');
    }
    function loadMenu(){
        $.get(BASE+'/php/main.php?action=getBarang', function(res){
            allMenu = res.data;
            $('#jmlMenu').text(res.data.length);
            renderMenu(0);
        },'json');
    }
    function renderMenu(idKat){
        var list = idKat==0 ? allMenu : allMenu.filter(function(m){ return m.id_kategori==idKat; });
        var html = '';
        if(!list.length){ html='<div class="col-12 text-center py-5" style="color:var(--sienna)">Tidak ada menu</div>'; }
        else list.forEach(function(m){
            var imgSrc = m.gambar ? BASE+'/img/menu/'+m.gambar : makePlaceholder(m.nama,m.nama_kategori||'',400,190);
            var stok = m.stok<=0 ? '<span class="badge mt-1" style="background:var(--latte);color:var(--espresso)">Habis</span>' : '';
            html += '<div class="col"><div class="card menu-card h-100">'
                +'<img src="'+imgSrc+'" class="card-img-top" alt="'+m.nama+'">'
                +'<div class="card-body d-flex flex-column">'
                +'<div class="kat-badge">'+(m.nama_kategori||'Umum')+'</div>'
                +'<h5 class="card-title">'+m.nama+'</h5>'
                +'<div class="harga mt-auto">'+rupiah(m.harga)+'</div>'+stok
                +'</div></div></div>';
        });
        $('#menuGrid').html(html);
    }
    function bindFilter(){
        $('.filter-pill').off('click').on('click',function(){
            $('.filter-pill').removeClass('active');
            $(this).addClass('active');
            renderMenu($(this).data('id'));
        });
    }
    $(document).ready(function(){
        $('.btnBuka').on('click',function(e){
            e.preventDefault();
            $('.section').css('display','block');
            document.getElementById('sectionMenu').scrollIntoView({behavior:'smooth'});
        });
        loadKategori(); loadMenu();
    });
    </script>
</body>
</html>
