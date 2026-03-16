<?php $base = defined('BASE') ? BASE : ''; ?>
<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 fw-bold" style="color:var(--espresso);font-family:'Playfair Display',serif">
            <i class="fa fa-users me-2" style="color:var(--caramel)"></i>Manajemen Pengguna
        </h5>
        <button class="btn btn-cafe-primary" data-bs-toggle="modal" data-bs-target="#modalUser" onclick="resetFormUser()">
            <i class="fa fa-plus me-1"></i> Tambah Pengguna
        </button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="tbl-head"><tr><th>#</th><th>Nama</th><th>Username</th><th>Role</th><th>Dibuat</th><th>Aksi</th></tr></thead>
            <tbody id="tblUsers"><tr><td colspan="6" class="text-center py-4">Memuat data...</td></tr></tbody>
        </table>
    </div>
</div>

<div class="modal fade modal-cafe" id="modalUser" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="alertUser" class="alert d-none"></div>
                <div class="mb-3"><label class="form-label fw-semibold" style="color:var(--darkroast)">Nama Lengkap</label><input type="text" id="namaUser" class="form-control"></div>
                <div class="mb-3"><label class="form-label fw-semibold" style="color:var(--darkroast)">Username</label><input type="text" id="usernameUser" class="form-control"></div>
                <div class="mb-3"><label class="form-label fw-semibold" style="color:var(--darkroast)">Password</label><input type="password" id="passwordUser" class="form-control"></div>
                <div class="mb-3"><label class="form-label fw-semibold" style="color:var(--darkroast)">Role</label>
                    <select id="roleUser" class="form-select"><option value="kasir">Kasir</option><option value="admin">Admin</option></select></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSimpanUser">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
var BASE = '<?= $base ?>';
function loadUsers(){
    $.get(BASE+'/php/main.php?action=getUsers', function(res){
        if(res.status!=='ok') return;
        var html='';
        if(!res.data.length){ html='<tr><td colspan="6" class="text-center py-4 text-muted">Belum ada pengguna</td></tr>'; }
        else res.data.forEach(function(u,i){
            var badge = u.role==='admin'
                ? '<span class="badge" style="background:var(--espresso);color:var(--cream)">Admin</span>'
                : '<span class="badge" style="background:var(--latte);color:var(--espresso)">Kasir</span>';
            html+='<tr><td>'+(i+1)+'</td><td class="fw-semibold" style="color:var(--espresso)">'+u.nama+'</td><td><code style="color:var(--darkroast)">'+u.username+'</code></td><td>'+badge+'</td><td>'+new Date(u.created_at).toLocaleDateString('id-ID')+'</td>'
                +'<td><button class="btn btn-sm btn-danger" onclick="hapusUser('+u.id+',\''+u.nama.replace(/'/g,"\\'")+'\')"><i class="fa fa-trash"></i></button></td></tr>';
        });
        $('#tblUsers').html(html);
    },'json');
}
function resetFormUser(){ $('#namaUser,#usernameUser,#passwordUser').val(''); $('#roleUser').val('kasir'); $('#alertUser').addClass('d-none'); }
function hapusUser(id,nama){
    if(!confirm('Hapus pengguna "'+nama+'"?')) return;
    $.post(BASE+'/php/main.php',{action:'hapusUser',id:id},function(res){ alert(res.message); loadUsers(); },'json');
}
$('#btnSimpanUser').on('click',function(){
    var nama=$('#namaUser').val().trim(), username=$('#usernameUser').val().trim(), password=$('#passwordUser').val(), role=$('#roleUser').val();
    if(!nama||!username||!password){ $('#alertUser').removeClass('d-none').addClass('alert-danger').text('Semua field wajib diisi'); return; }
    $.post(BASE+'/php/main.php',{action:'tambahUser',nama:nama,username:username,password:password,role:role},function(res){
        if(res.status==='ok'){ bootstrap.Modal.getInstance(document.getElementById('modalUser')).hide(); loadUsers(); }
        else { $('#alertUser').removeClass('d-none').addClass('alert-danger').text(res.message); }
    },'json');
});
loadUsers();
</script>
