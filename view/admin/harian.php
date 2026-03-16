<?php $base = defined('BASE') ? BASE : ''; ?>
<style>
.total-box {
    background: var(--espresso); color: var(--cream);
    border-radius: 14px; padding: 22px; text-align: center;
    box-shadow: 0 4px 14px rgba(59,31,14,0.2);
}
.total-box .label { font-size:0.82rem; color:var(--latte); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px; }
.total-box .amount { font-size:1.9rem; font-weight:700; font-family:'Playfair Display',serif; }
.total-box .count  { font-size:0.82rem; color:var(--cream); opacity:0.7; margin-top:4px; }
</style>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="total-box">
            <div class="label">Total Pendapatan</div>
            <div class="amount" id="totalHarian">Rp 0</div>
            <div class="count" id="jmlTransaksi">0 transaksi</div>
        </div>
    </div>
    <div class="col-md-8 d-flex align-items-center">
        <div class="d-flex gap-2 w-100">
            <input type="date" id="tglFilter" class="form-control" value="<?php echo date('Y-m-d'); ?>">
            <button class="btn btn-cafe-primary px-4" id="btnFilter"><i class="fa fa-search me-1"></i>Cari</button>
            <button class="btn" style="border:1.5px solid var(--latte);color:var(--darkroast)" onclick="window.print()"><i class="fa fa-print me-1"></i>Print</button>
        </div>
    </div>
</div>
<div class="content-card">
    <h5 class="fw-bold mb-3" style="color:var(--espresso);font-family:'Playfair Display',serif">
        <i class="fa fa-chart-bar me-2" style="color:var(--caramel)"></i>Daftar Transaksi
    </h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="tbl-head"><tr><th>#</th><th>Kode</th><th>Pelanggan</th><th>Kasir</th><th>Total</th><th>Bayar</th><th>Kembalian</th><th>Waktu</th><th></th></tr></thead>
            <tbody id="tblLaporan"><tr><td colspan="9" class="text-center py-4">Pilih tanggal lalu klik Cari</td></tr></tbody>
        </table>
    </div>
</div>

<div class="modal fade modal-cafe" id="modalDetail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title">Detail Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailContent">Memuat...</div>
        </div>
    </div>
</div>

<script>
var BASE = '<?= $base ?>';
function rupiah(n){ return 'Rp ' + parseInt(n).toLocaleString('id-ID'); }
function loadLaporan(){
    var tgl = $('#tglFilter').val();
    $.get(BASE+'/php/main.php?action=getLaporanHarian&tanggal='+tgl, function(res){
        if(res.status!=='ok') return;
        $('#totalHarian').text(rupiah(res.total));
        $('#jmlTransaksi').text(res.data.length+' transaksi');
        var html = '';
        if(!res.data.length){ html='<tr><td colspan="9" class="text-center py-4 text-muted">Tidak ada transaksi</td></tr>'; }
        else res.data.forEach(function(t,i){
            var waktu = new Date(t.created_at).toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'});
            html += '<tr><td>'+(i+1)+'</td><td><code style="color:var(--darkroast)">'+t.kode_transaksi+'</code></td>'
                +'<td>'+t.nama_pelanggan+'</td><td>'+(t.nama_kasir||'-')+'</td>'
                +'<td class="fw-bold" style="color:var(--sienna)">'+rupiah(t.total)+'</td>'
                +'<td>'+rupiah(t.bayar)+'</td><td>'+rupiah(t.kembalian)+'</td><td>'+waktu+'</td>'
                +'<td><button class="btn btn-sm" style="border:1.5px solid var(--latte);color:var(--darkroast)" onclick="lihatDetail('+t.id+',\''+t.kode_transaksi+'\')"><i class="fa fa-eye"></i></button></td></tr>';
        });
        $('#tblLaporan').html(html);
    },'json');
}
function lihatDetail(id,kode){
    $('#detailContent').html('<div class="text-center py-3"><div class="spinner-border" style="color:var(--caramel)"></div></div>');
    new bootstrap.Modal(document.getElementById('modalDetail')).show();
    $.get(BASE+'/php/main.php?action=getDetailTransaksi&id='+id, function(res){
        if(res.status!=='ok') return;
        var html='<p style="color:var(--darkroast)" class="mb-2">Kode: <strong>'+kode+'</strong></p>'
            +'<table class="table table-sm"><thead style="background:var(--milkfoam)"><tr><th>Menu</th><th>Harga</th><th>Qty</th><th>Subtotal</th></tr></thead><tbody>';
        var total=0;
        res.data.forEach(function(d){ total+=parseFloat(d.subtotal); html+='<tr><td>'+d.nama_barang+'</td><td>'+rupiah(d.harga)+'</td><td>'+d.qty+'</td><td>'+rupiah(d.subtotal)+'</td></tr>'; });
        html+='</tbody></table><div class="text-end fw-bold fs-5" style="color:var(--sienna)">Total: '+rupiah(total)+'</div>';
        $('#detailContent').html(html);
    },'json');
}
$('#btnFilter').on('click',loadLaporan);
loadLaporan();
</script>
