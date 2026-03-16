<?php $base = defined('BASE') ? BASE : ''; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir — Frindi Cafe</title>
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

        /* Header */
        .kasir-header {
            background: var(--espresso); color: var(--cream);
            padding: 11px 20px; display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 2px 10px rgba(59,31,14,0.3);
        }
        .kasir-header h4 { margin: 0; font-family: 'Playfair Display', serif; font-size: 1.1rem; color: var(--cream); }

        /* Layout */
        .kasir-body { display: flex; height: calc(100vh - 52px); overflow: hidden; }

        /* Menu panel */
        .menu-panel { flex: 1; overflow-y: auto; padding: 16px; background: #f2ebe3; }
        .filter-bar { display: flex; gap: 7px; flex-wrap: wrap; margin-bottom: 14px; }
        .filter-btn {
            border: 1.5px solid var(--latte); color: var(--darkroast);
            background: white; border-radius: 20px; padding: 5px 16px;
            cursor: pointer; transition: 0.2s; font-size: 0.83rem; font-weight: 600;
        }
        .filter-btn.active, .filter-btn:hover { background: var(--sienna); border-color: var(--sienna); color: var(--milkfoam); }

        .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(155px, 1fr)); gap: 12px; }
        .menu-card {
            background: white; border-radius: 13px; overflow: hidden;
            cursor: pointer; box-shadow: 0 2px 8px rgba(59,31,14,0.09);
            transition: 0.2s; border: 2px solid transparent;
        }
        .menu-card:hover { transform: translateY(-2px); box-shadow: 0 5px 16px rgba(59,31,14,0.15); border-color: var(--caramel); }
        .menu-card img { width: 100%; height: 115px; object-fit: cover; }
        .menu-card-body { padding: 10px; }
        .menu-card-body h6 { margin: 0 0 3px; font-size: 0.88rem; font-weight: 700; color: var(--espresso); }
        .menu-card-body .price { color: var(--sienna); font-weight: 700; font-size: 0.92rem; }
        .menu-card-body .stok  { font-size: 0.73rem; color: var(--latte); }

        /* Cart panel */
        .cart-panel { width: 330px; background: white; display: flex; flex-direction: column; box-shadow: -3px 0 16px rgba(59,31,14,0.1); }
        .cart-header { padding: 14px 18px; border-bottom: 2px solid var(--milkfoam); }
        .cart-header h5 { margin: 0; color: var(--espresso); font-family: 'Playfair Display', serif; font-size: 1rem; }
        .cart-items { flex: 1; overflow-y: auto; padding: 10px; }
        .cart-item {
            background: var(--offwhite); border-radius: 10px; padding: 10px 12px;
            margin-bottom: 8px; display: flex; align-items: center; gap: 9px;
            border-left: 3px solid var(--caramel);
        }
        .cart-item-name  { font-weight: 700; font-size: 0.88rem; flex: 1; color: var(--espresso); }
        .cart-item-price { font-size: 0.78rem; color: var(--sienna); }
        .cart-item-sub   { color: var(--sienna); font-weight: 700; font-size: 0.88rem; white-space: nowrap; }
        .qty-ctrl { display: flex; align-items: center; gap: 5px; }
        .qty-btn  { width: 26px; height: 26px; border-radius: 50%; border: none; background: var(--sienna); color: white; cursor: pointer; font-size: 1rem; display: flex; align-items: center; justify-content: center; transition: 0.2s; }
        .qty-btn:hover { background: var(--darkroast); }
        .qty-btn.minus { background: var(--latte); color: var(--espresso); }
        .qty-btn.minus:hover { background: var(--caramel); }
        .qty-num { font-weight: 700; width: 22px; text-align: center; color: var(--espresso); }
        .cart-footer { padding: 14px 18px; border-top: 2px solid var(--milkfoam); background: var(--offwhite); }
        .total-row { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 12px; }
        .total-row .label { color: var(--darkroast); font-size: 0.88rem; font-weight: 600; }
        .total-row .value { font-size: 1.4rem; font-weight: 700; color: var(--espresso); font-family: 'Playfair Display', serif; }
        .btn-bayar {
            background: var(--sienna); color: var(--milkfoam);
            border: none; border-radius: 12px; padding: 13px;
            font-size: 1rem; font-weight: 700; width: 100%; cursor: pointer; transition: 0.2s;
        }
        .btn-bayar:hover { background: var(--darkroast); }
        .btn-bayar:disabled { background: var(--latte); color: var(--espresso); cursor: not-allowed; }
        .empty-cart { text-align: center; color: var(--latte); padding: 40px 20px; }
        .empty-cart i { font-size: 2.5rem; display: block; margin-bottom: 10px; color: var(--cream); }
        .pelanggan-input {
            border: none; border-bottom: 2px solid var(--cream); border-radius: 0;
            font-size: 0.88rem; width: 100%; outline: none; padding: 5px 0;
            margin-top: 5px; background: transparent; color: var(--espresso);
        }
        .pelanggan-input:focus { border-bottom-color: var(--caramel); }

        /* Modal bayar */
        .modal-bayar-header { background: var(--espresso); color: var(--cream); }
        .modal-bayar-header .btn-close { filter: invert(1); }

        /* Struk */
        @media print { .no-print { display: none !important; } #struk { display: block !important; } }
        #struk { display: none; padding: 20px; max-width: 300px; }
        .struk-line { border-top: 1px dashed #999; margin: 8px 0; }
    </style>
</head>
<body>
<div class="kasir-header no-print">
    <div class="d-flex align-items-center gap-3">
        <img src="<?= $base ?>/img/logo.jpg" style="width:34px;height:34px;border-radius:50%;object-fit:cover;border:2px solid var(--caramel)">
        <h4>Frindi Cafe — Kasir</h4>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= $base ?>/admin" class="btn btn-sm" style="border:1.5px solid var(--latte);color:var(--cream)"><i class="fa fa-home me-1"></i>Admin</a>
        <button class="btn btn-sm" style="background:var(--caramel);color:var(--espresso);font-weight:700" id="btnLogoutKasir"><i class="fa fa-sign-out-alt me-1"></i>Logout</button>
    </div>
</div>

<div class="kasir-body no-print">
    <!-- Menu panel -->
    <div class="menu-panel">
        <div class="filter-bar" id="filterBar">
            <button class="filter-btn active" data-id="0">Semua</button>
        </div>
        <div class="menu-grid" id="menuGrid">
            <div class="text-center py-5" style="grid-column:1/-1;color:var(--sienna)">Memuat menu...</div>
        </div>
    </div>

    <!-- Cart panel -->
    <div class="cart-panel">
        <div class="cart-header">
            <h5><i class="fa fa-shopping-bag me-2" style="color:var(--caramel)"></i>Pesanan</h5>
            <div class="mt-2">
                <small style="color:var(--latte)">Nama Pelanggan</small>
                <input type="text" id="namaPelanggan" class="pelanggan-input" placeholder="Umum / nama pelanggan">
            </div>
        </div>
        <div class="cart-items" id="cartItems">
            <div class="empty-cart"><i class="fa fa-mug-hot"></i>Keranjang kosong<br><small>Pilih menu di sebelah kiri</small></div>
        </div>
        <div class="cart-footer">
            <div class="total-row">
                <span class="label">Total</span>
                <span class="value" id="cartTotal">Rp 0</span>
            </div>
            <button class="btn-bayar" id="btnBayar" disabled>
                <i class="fa fa-cash-register me-2"></i>Bayar
            </button>
        </div>
    </div>
</div>

<!-- Modal Bayar -->
<div class="modal fade no-print" id="modalBayar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header modal-bayar-header">
                <h5 class="modal-title"><i class="fa fa-cash-register me-2"></i>Pembayaran</h5>
                <button type="button" class="btn-close modal-bayar-header" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="background:var(--offwhite)">
                <div class="text-center mb-3">
                    <div style="color:var(--sienna);font-size:0.85rem;font-weight:600">TOTAL YANG HARUS DIBAYAR</div>
                    <div style="font-size:2rem;font-weight:700;color:var(--espresso);font-family:'Playfair Display',serif" id="totalBayarDisplay">Rp 0</div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="color:var(--darkroast)">Uang Diterima (Rp)</label>
                    <input type="number" id="uangBayar" class="form-control form-control-lg" placeholder="Masukkan nominal"
                           style="border-color:var(--latte);font-family:'Playfair Display',serif;font-size:1.3rem;color:var(--espresso)">
                </div>
                <div id="kembalianBox" class="d-none p-3 rounded-3 text-center" style="background:var(--milkfoam);border:1.5px solid var(--cream)">
                    <div style="color:var(--sienna);font-size:0.8rem;font-weight:600">KEMBALIAN</div>
                    <div style="font-size:1.5rem;font-weight:700;color:var(--espresso);font-family:'Playfair Display',serif" id="kembalianVal"></div>
                </div>
                <div class="d-flex flex-wrap gap-2 mt-3" id="nominalCepat"></div>
            </div>
            <div class="modal-footer" style="background:var(--offwhite)">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn px-4" style="background:var(--sienna);color:var(--milkfoam);font-weight:700" id="btnKonfirmBayar" disabled>
                    <i class="fa fa-check me-1"></i>Konfirmasi
                </button>
            </div>
        </div>
    </div>
</div>

<div id="struk"></div>

<script src="<?= $base ?>/vendor/jquery/jquery-3.7.1.min.js"></script>
<script src="<?= $base ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
var BASE = '<?= $base ?>';
var cart = [], allMenu = [], lastBayar = 0;
function rupiah(n){ return 'Rp ' + parseInt(n).toLocaleString('id-ID'); }
function getTotal(){ return cart.reduce(function(s,i){ return s+i.harga*i.qty; },0); }

var _colors = ['#6B3A2A','#A0522D','#7B4F3A','#8B5E3C','#5C3317','#9B6B4A','#C8855A','#6F4E37'];
function _getColor(s){ var h=0; for(var i=0;i<s.length;i++) h=s.charCodeAt(i)+((h<<5)-h); return _colors[Math.abs(h)%_colors.length]; }
function makePlaceholder(nama,kat,w,h){ var c=_getColor(kat||nama); var ini=nama.split(' ').slice(0,2).map(function(x){return x[0];}).join('').toUpperCase(); var svg='<svg xmlns="http://www.w3.org/2000/svg" width="'+w+'" height="'+h+'"><rect width="100%" height="100%" fill="'+c+'"/><text x="50%" y="44%" dominant-baseline="middle" text-anchor="middle" font-family="Georgia,serif" font-size="'+Math.floor(h*0.3)+'" font-weight="bold" fill="rgba(245,230,208,0.9)">'+ini+'</text><text x="50%" y="72%" dominant-baseline="middle" text-anchor="middle" font-size="'+Math.floor(h*0.18)+'" fill="rgba(237,213,179,0.55)">\u2615</text></svg>'; return 'data:image/svg+xml;charset=utf-8,'+encodeURIComponent(svg); }

function loadKategori(){
    $.get(BASE+'/php/main.php?action=getKategori',function(res){
        res.data.forEach(function(k){ $('#filterBar').append('<button class="filter-btn" data-id="'+k.id+'">'+k.nama+'</button>'); });
        $('.filter-btn').on('click',function(){ $('.filter-btn').removeClass('active'); $(this).addClass('active'); renderMenu(parseInt($(this).data('id'))); });
    },'json');
}
function loadMenu(){
    $.get(BASE+'/php/main.php?action=getBarang',function(res){ allMenu=res.data; renderMenu(0); },'json');
}
function renderMenu(idKat){
    var list = idKat===0 ? allMenu : allMenu.filter(function(m){ return m.id_kategori==idKat; });
    var html='';
    if(!list.length){ html='<div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--latte)">Tidak ada menu</div>'; }
    else list.forEach(function(m){
        var imgSrc = m.gambar ? BASE+'/img/menu/'+m.gambar : makePlaceholder(m.nama,m.nama_kategori||'',155,115);
        var stokBadge = m.stok<=0 ? '<span style="font-size:0.7rem;background:var(--latte);color:var(--espresso);border-radius:10px;padding:1px 7px">Habis</span>' : (m.stok<=10 ? '<span class="stok" style="color:var(--caramel)">Stok: '+m.stok+'</span>' : '<span class="stok">Stok: '+m.stok+'</span>');
        var onClick = m.stok>0 ? 'addToCart('+m.id+')' : '';
        html+='<div class="menu-card'+(m.stok<=0?' opacity-50':'')+'" onclick="'+onClick+'">'
            +'<img src="'+imgSrc+'" alt="'+m.nama+'">'
            +'<div class="menu-card-body"><h6>'+m.nama+'</h6><div class="price">'+rupiah(m.harga)+'</div>'+stokBadge+'</div></div>';
    });
    $('#menuGrid').html(html);
}
function addToCart(id){
    var m=allMenu.find(function(x){ return x.id==id; }); if(!m) return;
    var ex=cart.find(function(c){ return c.id==id; });
    if(ex){ ex.qty++; } else { cart.push({id:m.id,nama:m.nama,harga:parseFloat(m.harga),qty:1}); }
    renderCart();
}
function changeQty(idx,delta){ cart[idx].qty+=delta; if(cart[idx].qty<=0) cart.splice(idx,1); renderCart(); }
function renderCart(){
    if(!cart.length){
        $('#cartItems').html('<div class="empty-cart"><i class="fa fa-mug-hot"></i>Keranjang kosong<br><small>Pilih menu di sebelah kiri</small></div>');
        $('#cartTotal').text('Rp 0'); $('#btnBayar').prop('disabled',true); return;
    }
    var html='';
    cart.forEach(function(item,idx){
        html+='<div class="cart-item"><div style="flex:1"><div class="cart-item-name">'+item.nama+'</div><div class="cart-item-price">'+rupiah(item.harga)+'</div></div>'
            +'<div class="qty-ctrl"><button class="qty-btn minus" onclick="changeQty('+idx+',-1)">−</button><span class="qty-num">'+item.qty+'</span><button class="qty-btn" onclick="changeQty('+idx+',1)">+</button></div>'
            +'<div class="cart-item-sub">'+rupiah(item.harga*item.qty)+'</div></div>';
    });
    $('#cartItems').html(html);
    $('#cartTotal').text(rupiah(getTotal()));
    $('#btnBayar').prop('disabled',false);
}
$('#btnBayar').on('click',function(){
    if(!cart.length) return;
    var total=getTotal();
    $('#totalBayarDisplay').text(rupiah(total));
    $('#uangBayar').val(''); $('#kembalianBox').addClass('d-none'); $('#btnKonfirmBayar').prop('disabled',true);
    var noms=[5000,10000,20000,50000,100000,200000].filter(function(n){ return n>=total; });
    var nomHtml='<button class="btn btn-sm" style="border:1.5px solid var(--latte);color:var(--darkroast)" onclick="setNominal('+total+')">'+rupiah(total)+' (Pas)</button>';
    noms.slice(0,3).forEach(function(n){ nomHtml+='<button class="btn btn-sm" style="border:1.5px solid var(--latte);color:var(--darkroast)" onclick="setNominal('+n+')">'+rupiah(n)+'</button>'; });
    $('#nominalCepat').html(nomHtml);
    new bootstrap.Modal(document.getElementById('modalBayar')).show();
    setTimeout(function(){ $('#uangBayar').focus(); },400);
});
function setNominal(n){ $('#uangBayar').val(n).trigger('input'); }
$('#uangBayar').on('input',function(){
    var bayar=parseFloat($(this).val())||0, total=getTotal();
    if(bayar>=total){ $('#kembalianBox').removeClass('d-none'); $('#kembalianVal').text(rupiah(bayar-total)); $('#btnKonfirmBayar').prop('disabled',false); }
    else { $('#kembalianBox').addClass('d-none'); $('#btnKonfirmBayar').prop('disabled',true); }
});
$('#btnKonfirmBayar').on('click',function(){
    var bayar=parseFloat($('#uangBayar').val()); lastBayar=bayar;
    var nama_pelanggan=$('#namaPelanggan').val().trim()||'Umum';
    var savedCart=JSON.parse(JSON.stringify(cart));
    $(this).prop('disabled',true).text('Memproses...');
    $.ajax({ url:BASE+'/php/main.php', type:'POST',
        data:{action:'simpanTransaksi',items:JSON.stringify(cart),nama_pelanggan:nama_pelanggan,bayar:bayar},
        dataType:'json',
        success:function(res){
            if(res.status==='ok'){
                bootstrap.Modal.getInstance(document.getElementById('modalBayar')).hide();
                cart=[]; $('#namaPelanggan').val(''); renderCart(); loadMenu(); cetakStruk(res,savedCart);
            } else { alert('Error: '+res.message); }
            $('#btnKonfirmBayar').prop('disabled',false).html('<i class="fa fa-check me-1"></i>Konfirmasi');
        },
        error:function(){ alert('Gagal terhubung ke server'); $('#btnKonfirmBayar').prop('disabled',false).html('<i class="fa fa-check me-1"></i>Konfirmasi'); }
    });
});
function cetakStruk(res,items){
    var now=new Date().toLocaleString('id-ID');
    var itemRows='';
    items.forEach(function(i){ itemRows+='<tr><td>'+i.nama+'</td><td align="right">'+i.qty+'x</td><td align="right">'+rupiah(i.harga*i.qty)+'</td></tr>'; });
    $('#struk').html('<div style="text-align:center;font-weight:bold;font-size:1.1rem">☕ FRINDI CAFE</div>'
        +'<div style="text-align:center;font-size:0.8rem;color:#666">Struk Pembayaran</div><div class="struk-line"></div>'
        +'<div style="font-size:0.85rem">Kode: '+res.kode+'</div><div style="font-size:0.85rem">'+now+'</div><div class="struk-line"></div>'
        +'<table style="width:100%;font-size:0.85rem">'+itemRows+'</table><div class="struk-line"></div>'
        +'<div style="font-size:0.85rem">Total: <strong>'+rupiah(res.total)+'</strong></div>'
        +'<div style="font-size:0.85rem">Bayar: '+rupiah(lastBayar)+'</div>'
        +'<div style="font-size:0.85rem">Kembalian: <strong>'+rupiah(res.kembalian)+'</strong></div><div class="struk-line"></div>'
        +'<div style="text-align:center;font-size:0.8rem">Terima kasih sudah berkunjung!<br>Selamat menikmati ☕</div>');
    var notif=$('<div class="position-fixed top-0 start-50 translate-middle-x mt-3 no-print" style="z-index:9999;min-width:320px;background:var(--milkfoam);border:2px solid var(--caramel);border-radius:12px;padding:14px 18px;box-shadow:0 8px 24px rgba(59,31,14,0.2);color:var(--espresso)">'
        +'<i class="fa fa-check-circle me-2" style="color:var(--sienna)"></i>Transaksi berhasil! Kembalian: <strong style="color:var(--sienna)">'+rupiah(res.kembalian)+'</strong>'
        +' <button class="btn btn-sm ms-2" style="border:1.5px solid var(--latte);color:var(--darkroast)" onclick="window.print()"><i class="fa fa-print me-1"></i>Print</button></div>');
    $('body').append(notif);
    setTimeout(function(){ notif.fadeOut(500,function(){ $(this).remove(); }); },6000);
}
$('#btnLogoutKasir').on('click',function(){
    if(!confirm('Yakin ingin logout?')) return;
    $.post(BASE+'/php/main.php',{action:'logout'},function(){ window.location.href=BASE+'/login'; });
});
loadKategori(); loadMenu();
</script>
</body>
</html>
