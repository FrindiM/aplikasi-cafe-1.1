<?php $base = defined('BASE') ? BASE : ''; ?>
<style>
.stat-card {
    background: white; border-radius: 14px; padding: 20px;
    box-shadow: 0 2px 10px rgba(59,31,14,0.07);
    display: flex; align-items: center; gap: 16px;
    border-left: 4px solid var(--caramel);
}
.stat-icon {
    width: 52px; height: 52px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; color: white; flex-shrink: 0;
}
.stat-card h3 { margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--espresso); }
.stat-card p  { margin: 0; color: var(--sienna); font-size: 0.82rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
.chart-card {
    background: white; border-radius: 14px; padding: 24px;
    box-shadow: 0 2px 10px rgba(59,31,14,0.07);
    margin-top: 20px; border-top: 3px solid var(--cream);
}
.chart-card h5 { color: var(--espresso); font-family: 'Playfair Display',serif; font-weight: 600; margin-bottom: 18px; }
</style>

<div class="row g-3 mb-2">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:var(--sienna)"><i class="fa fa-money-bill-wave"></i></div>
            <div><p>Pendapatan Hari Ini</p><h3 id="statPendapatan">—</h3></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:var(--darkroast)"><i class="fa fa-receipt"></i></div>
            <div><p>Transaksi Hari Ini</p><h3 id="statTransaksi">—</h3></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:var(--caramel)"><i class="fa fa-mug-hot"></i></div>
            <div><p>Total Menu</p><h3 id="statBarang">—</h3></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:var(--latte)"><i class="fa fa-tags" style="color:var(--espresso)"></i></div>
            <div><p>Kategori</p><h3 id="statKategori">—</h3></div>
        </div>
    </div>
</div>

<div class="chart-card">
    <h5><i class="fa fa-chart-line me-2" style="color:var(--caramel)"></i>Pendapatan 7 Hari Terakhir</h5>
    <canvas id="grafikPendapatan" height="90"></canvas>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
var BASE = '<?= $base ?>';
function rupiah(n){ return 'Rp ' + parseInt(n).toLocaleString('id-ID'); }

$.get(BASE+'/php/main.php?action=getDashboard', function(res){
    if(res.status!=='ok') return;
    $('#statPendapatan').text(rupiah(res.pendapatan));
    $('#statTransaksi').text(res.transaksi);
    $('#statBarang').text(res.barang);
    $('#statKategori').text(res.kategori);

    new Chart(document.getElementById('grafikPendapatan').getContext('2d'), {
        type: 'bar',
        data: {
            labels: res.grafik.map(function(g){ return g.tanggal; }),
            datasets: [{
                label: 'Pendapatan',
                data: res.grafik.map(function(g){ return g.total; }),
                backgroundColor: 'rgba(160,82,45,0.75)',
                borderColor: '#6B3A2A',
                borderWidth: 1, borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend:{display:false},
                tooltip:{ callbacks:{ label:function(c){ return rupiah(c.parsed.y); } } }
            },
            scales:{
                y:{ ticks:{ callback:function(v){ return 'Rp '+(v/1000)+'k'; }, color:'#A0522D' } },
                x:{ ticks:{ color:'#6B3A2A' } }
            }
        }
    });
}, 'json');
</script>
