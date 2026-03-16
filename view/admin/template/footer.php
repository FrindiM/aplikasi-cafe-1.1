<?php $base = defined('BASE') ? BASE : ''; ?>
    </div><!-- end mainContent -->
</div><!-- end main-content -->
<script src="<?= $base ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
var BASE = '<?= $base ?>';
$(document).ready(function(){
    var path = window.location.pathname.replace(BASE+'/', '').replace(BASE, '').replace(/^\//, '');
    var map  = {admin:'dashboard', kategori:'kategori', barang:'barang', lHarian:'laporan', pengguna:'pengguna'};
    if (map[path]) $('#nav-'+map[path]).addClass('active');
    var titles = {admin:'Dashboard', kategori:'Manajemen Kategori', barang:'Manajemen Menu', lHarian:'Laporan Harian', pengguna:'Manajemen Pengguna'};
    if (titles[path]) $('#pageTitle').text(titles[path]);
    $('#btnLogout').on('click', function(){
        if(!confirm('Yakin ingin logout?')) return;
        $.post(BASE+'/php/main.php', {action:'logout'}, function(){
            window.location.href = BASE+'/login';
        });
    });
});
</script>
</body>
</html>
