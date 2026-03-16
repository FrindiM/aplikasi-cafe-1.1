<?php $base = defined('BASE') ? BASE : ''; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Frindi Cafe</title>
    <link rel="stylesheet" href="<?= $base ?>/vendor/bootstrap/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Lato:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --espresso:#3B1F0E; --darkroast:#6B3A2A; --sienna:#A0522D;
            --caramel:#C8855A; --latte:#DBA882; --cream:#EDD5B3;
            --milkfoam:#F5E6D0; --offwhite:#FBF5ED;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Lato', sans-serif;
            background-image: url('<?= $base ?>/img/bg.jpg');
            background-size: cover; background-position: center; background-attachment: fixed;
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 20px;
        }
        body::before {
            content: ''; position: fixed; inset: 0;
            background: rgba(27,10,3,0.72); z-index: 0;
        }
        .login-card {
            position: relative; z-index: 1;
            background: var(--offwhite);
            border-radius: 22px;
            padding: 0;
            width: 100%; max-width: 420px;
            box-shadow: 0 24px 70px rgba(27,10,3,0.55);
            overflow: hidden;
        }
        .card-top {
            background: var(--espresso);
            padding: 30px 36px 24px;
            text-align: center;
        }
        .card-top img {
            width: 72px; height: 72px; border-radius: 50%; object-fit: cover;
            border: 3px solid var(--caramel); margin-bottom: 12px;
        }
        .card-top h2 {
            font-family: 'Playfair Display', serif;
            color: var(--cream); font-size: 1.45rem; margin-bottom: 3px;
        }
        .card-top p { color: var(--latte); font-size: 0.85rem; }
        .card-body-inner { padding: 28px 36px 32px; }
        label { display: block; font-weight: 600; font-size: 0.85rem; color: var(--darkroast); margin-bottom: 6px; }
        .form-control {
            border: 1.5px solid var(--cream); border-radius: 10px;
            padding: 11px 14px; font-size: 0.95rem; background: white;
            width: 100%; outline: none; transition: 0.2s;
            color: var(--espresso);
        }
        .form-control:focus { border-color: var(--caramel); box-shadow: 0 0 0 3px rgba(200,133,90,0.18); }
        .mb-field { margin-bottom: 18px; }
        .btn-login {
            width: 100%; padding: 13px;
            background: var(--sienna); color: var(--milkfoam);
            border: none; border-radius: 12px;
            font-size: 1rem; font-weight: 700;
            font-family: 'Lato', sans-serif;
            cursor: pointer; transition: 0.25s; letter-spacing: 0.5px;
            margin-top: 6px;
        }
        .btn-login:hover { background: var(--darkroast); }
        .btn-login:disabled { background: var(--latte); cursor: not-allowed; }
        .alert-box {
            background: #fdf0ec; border: 1.5px solid var(--caramel);
            border-radius: 8px; padding: 10px 14px;
            color: var(--darkroast); font-size: 0.85rem;
            margin-bottom: 16px; display: none;
        }
        .hint-box {
            background: var(--milkfoam); border-radius: 10px;
            padding: 11px 14px; margin-top: 16px;
            font-size: 0.8rem; color: var(--darkroast); line-height: 1.7;
        }
        .hint-box strong { color: var(--espresso); }
        code { background: var(--cream); padding: 1px 6px; border-radius: 4px; font-size: 0.82rem; color: var(--espresso); }
    </style>
</head>
<body>
<div class="login-card">
    <div class="card-top">
        <img src="<?= $base ?>/img/logo.jpg" alt="Logo">
        <h2>Frindi Cafe</h2>
        <p>Sistem Kasir &amp; Manajemen</p>
    </div>
    <div class="card-body-inner">
        <div id="alertMsg" class="alert-box"></div>
        <div class="mb-field">
            <label for="username">Username</label>
            <input type="text" id="username" class="form-control" placeholder="Masukkan username" autocomplete="username">
        </div>
        <div class="mb-field">
            <label for="password">Password</label>
            <input type="password" id="password" class="form-control" placeholder="Masukkan password" autocomplete="current-password">
        </div>
        <button class="btn-login" id="btnLogin">Masuk</button>
        <div class="hint-box">
            <strong>Akun default:</strong><br>
            Admin &nbsp;→ <code>admin</code> / <code>password</code><br>
            Kasir &nbsp;→ <code>kasir1</code> / <code>password</code>
        </div>
    </div>
</div>
<script src="<?= $base ?>/vendor/jquery/jquery-3.7.1.min.js"></script>
<script>
var BASE = '<?= $base ?>';
$(document).ready(function(){
    function doLogin(){
        var username = $('#username').val().trim();
        var password = $('#password').val();
        $('#alertMsg').hide();
        if(!username||!password){ $('#alertMsg').text('Username dan password wajib diisi').show(); return; }
        $('#btnLogin').text('Memproses...').prop('disabled',true);
        $.ajax({
            url: BASE+'/php/main.php', type:'POST',
            data:{action:'login',username:username,password:password},
            dataType:'json',
            success:function(res){
                if(res.status==='ok'){
                    window.location.href = BASE+'/'+(res.role==='admin'?'admin':'kasir');
                } else {
                    $('#alertMsg').text(res.message||'Login gagal').show();
                    $('#btnLogin').text('Masuk').prop('disabled',false);
                }
            },
            error:function(xhr){
                var msg='Gagal terhubung ke server';
                try{ var j=JSON.parse(xhr.responseText); if(j.message) msg=j.message; }catch(e){}
                $('#alertMsg').text(msg).show();
                $('#btnLogin').text('Masuk').prop('disabled',false);
            }
        });
    }
    $('#btnLogin').on('click',doLogin);
    $('#username,#password').on('keypress',function(e){ if(e.which===13) doLogin(); });
});
</script>
</body>
</html>
