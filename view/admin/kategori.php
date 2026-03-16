<?php $base = defined('BASE') ? BASE : ''; ?>
<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 fw-bold" style="color:var(--espresso);font-family:'Playfair Display',serif">
            <i class="fa fa-tags me-2" style="color:var(--caramel)"></i>Data Kategori
        </h5>
        <button class="btn btn-cafe-primary" data-bs-toggle="modal" data-bs-target="#modalKategori" onclick="resetForm()">
            <i class="fa fa-plus me-1"></i> Tambah Kategori
        </button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="tbl-head"><tr><th>#</th><th>Nama Kategori</th><th>Dibuat</th><th>Aksi</th></tr></thead>
            <tbody id="tblKategori"><tr><td colspan="4" class="text-center py-4">Memuat data...</td></tr></tbody>
        </table>
    </div>
</div>

<div class="modal fade modal-cafe" id="modalKategori" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editId">
                <div id="alertKat" class="alert d-none"></div>
                <label class="form-label fw-semibold" style="color:var(--darkroast)">Nama Kategori</label>
                <input type="text" id="namaKategori" class="form-control" placeholder="contoh: Minuman">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSimpanKat">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
var BASE = '<?= $base ?>';
function loadKategori(){
    $.get(BASE+'/php/main.php?action=getKategori', function(res){
        if(res.status!=='ok') return;
        var html = '';
        if(!res.data.length){ html='<tr><td colspan="4" class="text-center py-4 text-muted">Belum ada kategori</td></tr>'; }
        else res.data.forEach(function(k,i){
            html += '<tr><td>'+(i+1)+'</td>'
                +'<td><span class="badge-kat">'+k.nama+'</span></td>'
                +'<td>'+new Date(k.created_at).toLocaleDateString('id-ID')+'</td>'
                +'<td>'
                +'<button class="btn btn-sm btn-warning me-1" onclick="editKat('+k.id+',\''+k.nama.replace(/'/g,"\\'")+'\')"><i class="fa fa-edit"></i></button>'
                +'<button class="btn btn-sm btn-danger" onclick="hapusKat('+k.id+',\''+k.nama.replace(/'/g,"\\'")+'\')"><i class="fa fa-trash"></i></button>'
                +'</td></tr>';
        });
        $('#tblKategori').html(html);
    },'json');
}
function resetForm(){ $('#editId').val(''); $('#namaKategori').val(''); $('#modalTitle').text('Tambah Kategori'); $('#alertKat').addClass('d-none'); }
function editKat(id,nama){ $('#editId').val(id); $('#namaKategori').val(nama); $('#modalTitle').text('Edit Kategori'); $('#alertKat').addClass('d-none'); new bootstrap.Modal(document.getElementById('modalKategori')).show(); }
function hapusKat(id,nama){
    if(!confirm('Hapus kategori "'+nama+'"?')) return;
    $.post(BASE+'/php/main.php',{action:'hapusKategori',id:id},function(res){ alert(res.message); loadKategori(); },'json');
}
$('#btnSimpanKat').on('click',function(){
    var id=$('#editId').val(), nama=$('#namaKategori').val().trim();
    if(!nama){ $('#alertKat').removeClass('d-none alert-success').addClass('alert-danger').text('Nama tidak boleh kosong'); return; }
    $.post(BASE+'/php/main.php',{action:id?'editKategori':'tambahKategori',id:id,nama:nama},function(res){
        if(res.status==='ok'){ bootstrap.Modal.getInstance(document.getElementById('modalKategori')).hide(); loadKategori(); }
        else { $('#alertKat').removeClass('d-none').addClass('alert-danger').text(res.message); }
    },'json');
});
loadKategori();
</script>
