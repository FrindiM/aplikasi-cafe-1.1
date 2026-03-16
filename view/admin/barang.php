<?php $base = defined('BASE') ? BASE : ''; ?>
<style>
.menu-img { width:54px; height:54px; object-fit:cover; border-radius:10px; border:2px solid var(--cream); }
.upload-area {
    border:2px dashed var(--latte); border-radius:12px; padding:20px;
    text-align:center; cursor:pointer; transition:0.2s; background:var(--offwhite); position:relative;
}
.upload-area:hover, .upload-area.dragover { border-color:var(--sienna); background:var(--milkfoam); }
.upload-area input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
.upload-area .upload-icon { font-size:1.8rem; color:var(--caramel); display:block; margin-bottom:6px; }
.upload-area .upload-text { color:var(--darkroast); font-size:0.88rem; }
.upload-area .upload-hint { color:var(--latte); font-size:0.78rem; margin-top:3px; }
.img-preview-wrap { position:relative; display:inline-block; margin-top:10px; }
.img-preview-wrap img { width:100%; max-height:160px; object-fit:cover; border-radius:10px; border:2px solid var(--latte); display:block; }
.btn-hapus-preview { position:absolute; top:-8px; right:-8px; background:#c0392b; color:white; border:none; border-radius:50%; width:26px; height:26px; font-size:0.85rem; cursor:pointer; display:flex; align-items:center; justify-content:center; }
.existing-img-wrap img { width:80px; height:80px; object-fit:cover; border-radius:8px; border:2px solid var(--latte); }
</style>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 fw-bold" style="color:var(--espresso);font-family:'Playfair Display',serif">
            <i class="fa fa-mug-hot me-2" style="color:var(--caramel)"></i>Data Menu / Barang
        </h5>
        <button class="btn btn-cafe-primary" data-bs-toggle="modal" data-bs-target="#modalBarang" onclick="resetFormBarang()">
            <i class="fa fa-plus me-1"></i> Tambah Menu
        </button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="tbl-head"><tr><th>#</th><th>Gambar</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody id="tblBarang"><tr><td colspan="8" class="text-center py-4">Memuat data...</td></tr></tbody>
        </table>
    </div>
</div>

<div class="modal fade modal-cafe" id="modalBarang" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBarangTitle">Tambah Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editBarangId">
                <input type="hidden" id="gambarLama">
                <div id="alertBarang" class="alert d-none"></div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="color:var(--darkroast)">Nama Menu <span class="text-danger">*</span></label>
                        <input type="text" id="namaBarang" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="color:var(--darkroast)">Kategori <span class="text-danger">*</span></label>
                        <select id="kategoriBarang" class="form-select"><option value="">Pilih Kategori</option></select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="color:var(--darkroast)">Harga (Rp) <span class="text-danger">*</span></label>
                        <input type="number" id="hargaBarang" class="form-control" min="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="color:var(--darkroast)">Stok</label>
                        <input type="number" id="stokBarang" class="form-control" min="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="color:var(--darkroast)">Status</label>
                        <select id="tersediaBarang" class="form-select">
                            <option value="1">✅ Tersedia</option>
                            <option value="0">❌ Habis</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="color:var(--darkroast)">Gambar Menu</label>
                        <div id="existingImgWrap" class="d-none mb-2">
                            <div style="font-size:0.78rem;color:var(--sienna);margin-bottom:4px">Gambar saat ini:</div>
                            <div class="d-flex align-items-center gap-2">
                                <img id="existingImg" src="" alt="Gambar" class="existing-img-wrap" style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:2px solid var(--latte)">
                                <button type="button" class="btn btn-sm btn-outline-danger" id="btnHapusGambar"><i class="fa fa-trash me-1"></i>Hapus</button>
                            </div>
                            <small style="color:var(--latte)">Upload gambar baru di bawah untuk mengganti.</small>
                        </div>
                        <div class="upload-area mt-1" id="uploadArea">
                            <input type="file" id="gambarBarang" accept="image/jpeg,image/png,image/gif,image/webp">
                            <span class="upload-icon">📷</span>
                            <div class="upload-text">Klik atau <strong>drag &amp; drop</strong> gambar di sini</div>
                            <div class="upload-hint">JPG, PNG, GIF, WEBP · Maks. 2MB</div>
                        </div>
                        <div id="previewWrap" class="d-none">
                            <div class="img-preview-wrap w-100">
                                <img id="imgPreview" src="" alt="Preview">
                                <button type="button" class="btn-hapus-preview" id="btnBatalPreview">✕</button>
                            </div>
                            <small style="color:var(--sienna)" class="mt-1 d-block" id="namaFileLabel"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary px-4" id="btnSimpanBarang"><i class="fa fa-save me-1"></i>Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
var BASE = '<?= $base ?>';
function rupiah(n){ return 'Rp ' + parseInt(n).toLocaleString('id-ID'); }
var _colors = ['#6B3A2A','#A0522D','#7B4F3A','#8B5E3C','#5C3317','#9B6B4A','#C8855A','#6F4E37'];
function _getColor(s){ var h=0; for(var i=0;i<s.length;i++) h=s.charCodeAt(i)+((h<<5)-h); return _colors[Math.abs(h)%_colors.length]; }
function makePlaceholder(nama,kat,w,h){ var c=_getColor(kat||nama); var ini=nama.split(' ').slice(0,2).map(function(x){return x[0];}).join('').toUpperCase(); var svg='<svg xmlns="http://www.w3.org/2000/svg" width="'+w+'" height="'+h+'"><rect width="100%" height="100%" fill="'+c+'"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-family="Georgia,serif" font-size="'+Math.floor(h*0.38)+'" font-weight="bold" fill="rgba(245,230,208,0.9)">'+ini+'</text></svg>'; return 'data:image/svg+xml;charset=utf-8,'+encodeURIComponent(svg); }

function setPreview(file){
    if(!file) return;
    if(file.size > 2*1024*1024){ showAlert('Ukuran file terlalu besar (maks 2MB)','danger'); clearPreview(); return; }
    var reader = new FileReader();
    reader.onload = function(e){ $('#imgPreview').attr('src',e.target.result); $('#namaFileLabel').text(file.name+' ('+(file.size/1024).toFixed(0)+' KB)'); $('#uploadArea').addClass('d-none'); $('#previewWrap').removeClass('d-none'); };
    reader.readAsDataURL(file);
}
function clearPreview(){ $('#gambarBarang').val(''); $('#imgPreview').attr('src',''); $('#previewWrap').addClass('d-none'); $('#uploadArea').removeClass('d-none'); }
$('#gambarBarang').on('change',function(){ if(this.files[0]) setPreview(this.files[0]); });
$('#btnBatalPreview').on('click',clearPreview);
var ua=document.getElementById('uploadArea');
ua.addEventListener('dragover',function(e){e.preventDefault();this.classList.add('dragover');});
ua.addEventListener('dragleave',function(){this.classList.remove('dragover');});
ua.addEventListener('drop',function(e){ e.preventDefault(); this.classList.remove('dragover'); var file=e.dataTransfer.files[0]; if(file&&file.type.startsWith('image/')){ var dt=new DataTransfer(); dt.items.add(file); document.getElementById('gambarBarang').files=dt.files; setPreview(file); } });

function showAlert(msg,type){ $('#alertBarang').removeClass('d-none alert-danger alert-success alert-warning').addClass('alert-'+type).text(msg).show(); }

$('#btnHapusGambar').on('click',function(){
    var id=$('#editBarangId').val();
    if(!id||!confirm('Hapus gambar ini?')) return;
    $.post(BASE+'/php/main.php',{action:'hapusGambarBarang',id:id},function(res){
        if(res.status==='ok'){ $('#existingImgWrap').addClass('d-none'); $('#gambarLama').val(''); showAlert('Gambar berhasil dihapus','success'); }
        else { showAlert(res.message,'danger'); }
    },'json');
});

function loadBarang(){
    $.get(BASE+'/php/main.php?action=getAllBarang',function(res){
        if(res.status!=='ok') return;
        var html='';
        if(!res.data.length){ html='<tr><td colspan="8" class="text-center py-4 text-muted">Belum ada menu</td></tr>'; }
        else res.data.forEach(function(b,i){
            var imgSrc = b.gambar ? BASE+'/img/menu/'+b.gambar : makePlaceholder(b.nama,b.nama_kategori||'',54,54);
            var badge = b.tersedia ? '<span class="badge" style="background:var(--milkfoam);color:var(--darkroast)">Tersedia</span>' : '<span class="badge" style="background:var(--latte);color:var(--espresso)">Habis</span>';
            var stokCls = b.stok<=10 ? 'fw-bold' : '';
            var stokColor = b.stok<=10 ? 'color:var(--sienna)' : 'color:var(--darkroast)';
            var bJson = JSON.stringify(b).replace(/"/g,'&quot;');
            html+='<tr><td>'+(i+1)+'</td><td><img src="'+imgSrc+'" class="menu-img"></td>'
                +'<td class="fw-semibold" style="color:var(--espresso)">'+b.nama+'</td>'
                +'<td><span class="badge-kat">'+(b.nama_kategori||'-')+'</span></td>'
                +'<td style="color:var(--sienna)">'+rupiah(b.harga)+'</td>'
                +'<td class="'+stokCls+'" style="'+stokColor+'">'+b.stok+'</td>'
                +'<td>'+badge+'</td>'
                +'<td><button class="btn btn-sm btn-warning me-1" onclick=\'editBarang('+bJson+')\' title="Edit"><i class="fa fa-edit"></i></button>'
                +'<button class="btn btn-sm btn-danger" onclick="hapusBarang('+b.id+',\''+b.nama.replace(/'/g,"\\'")+'\')"><i class="fa fa-trash"></i></button></td></tr>';
        });
        $('#tblBarang').html(html);
    },'json');
}

function loadKategoriOptions(selectedId){
    $.get(BASE+'/php/main.php?action=getKategori',function(res){
        var opts='<option value="">Pilih Kategori</option>';
        res.data.forEach(function(k){ opts+='<option value="'+k.id+'"'+(k.id==selectedId?' selected':'')+'>'+k.nama+'</option>'; });
        $('#kategoriBarang').html(opts);
    },'json');
}

function resetFormBarang(){
    $('#editBarangId,#gambarLama,#namaBarang,#hargaBarang,#stokBarang').val('');
    $('#tersediaBarang').val('1'); $('#alertBarang').addClass('d-none');
    $('#existingImgWrap').addClass('d-none'); clearPreview();
    $('#modalBarangTitle').text('Tambah Menu'); loadKategoriOptions(0);
}

function editBarang(b){
    $('#editBarangId').val(b.id); $('#gambarLama').val(b.gambar||'');
    $('#namaBarang').val(b.nama); $('#hargaBarang').val(b.harga);
    $('#stokBarang').val(b.stok); $('#tersediaBarang').val(b.tersedia);
    $('#alertBarang').addClass('d-none'); clearPreview();
    if(b.gambar){ $('#existingImg').attr('src',BASE+'/img/menu/'+b.gambar); $('#existingImgWrap').removeClass('d-none'); }
    else { $('#existingImgWrap').addClass('d-none'); }
    $('#modalBarangTitle').text('Edit Menu — '+b.nama);
    loadKategoriOptions(b.id_kategori);
    new bootstrap.Modal(document.getElementById('modalBarang')).show();
}

function hapusBarang(id,nama){
    if(!confirm('Hapus menu "'+nama+'"?')) return;
    $.post(BASE+'/php/main.php',{action:'hapusBarang',id:id},function(res){ alert(res.message); loadBarang(); },'json');
}

$('#btnSimpanBarang').on('click',function(){
    var id=$('#editBarangId').val(), nama=$('#namaBarang').val().trim(), harga=$('#hargaBarang').val(), stok=$('#stokBarang').val(), id_kategori=$('#kategoriBarang').val(), tersedia=$('#tersediaBarang').val();
    if(!nama||!harga||!id_kategori){ showAlert('Nama, harga, dan kategori wajib diisi','danger'); return; }
    var formData=new FormData();
    formData.append('action',id?'editBarang':'tambahBarang');
    if(id) formData.append('id',id);
    formData.append('nama',nama); formData.append('harga',harga);
    formData.append('stok',stok||0); formData.append('id_kategori',id_kategori);
    formData.append('tersedia',tersedia);
    var gambarFile=document.getElementById('gambarBarang').files[0];
    if(gambarFile) formData.append('gambar',gambarFile);
    var btn=$(this);
    btn.prop('disabled',true).html('<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...');
    $.ajax({ url:BASE+'/php/main.php', type:'POST', data:formData, processData:false, contentType:false, dataType:'json',
        success:function(res){ if(res.status==='ok'){ bootstrap.Modal.getInstance(document.getElementById('modalBarang')).hide(); loadBarang(); } else { showAlert(res.message,'danger'); } },
        error:function(){ showAlert('Gagal terhubung ke server','danger'); },
        complete:function(){ btn.prop('disabled',false).html('<i class="fa fa-save me-1"></i>Simpan'); }
    });
});

loadKategoriOptions(0);
loadBarang();
</script>
