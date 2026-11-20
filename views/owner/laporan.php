<?php
/** Ringkasan Laporan & Riwayat Penjualan. Variabel: $periode, $ring, $tren, $trx. */
partial('layout/owner_open', ['active' => 'laporan', 'status' => false]);
?>
<header class="ow-head">
  <h1>Ringkasan Laporan &amp; Riwayat Penjualan</h1>
  <?php partial('owner/_periode', ['page' => 'owner-laporan', 'periode' => $periode]); ?>
</header>

<div class="ow-kpis">
  <article class="ow-card ow-kpi">
    <header><h2>Total Pendapatan</h2><span class="ow-kpi-ic"><i data-ico="cash"></i></span></header>
    <p class="ow-kpi-v"><?= e(rp($ring['pendapatan'])) ?></p>
    <p class="ow-kpi-s"><span class="ow-up"><i data-ico="trend"></i>+<?= e($ring['naik']) ?>%</span> vs periode lalu</p>
  </article>
  <article class="ow-card ow-kpi">
    <header><h2>Total Transaksi</h2><span class="ow-kpi-ic"><i data-ico="receipt"></i></span></header>
    <p class="ow-kpi-v"><?= e(number_format($ring['struk'], 0, ',', '.')) ?> Struk</p>
    <p class="ow-kpi-s"><span class="ow-dot-s"></span>Rerata <?= (int) $ring['rerata'] ?> pesanan / jam</p>
  </article>
</div>

<article class="ow-card ow-chart">
  <header>
    <div><h2>Tren Penjualan Ringkas</h2><p>Aktivitas volume omset 7 hari terakhir</p></div>
    <span class="ow-legend"><i></i>Total Omset Harian</span>
  </header>
  <div class="ow-bars" role="img" aria-label="Grafik omset harian 7 hari terakhir">
    <?php foreach ($tren as $b): ?>
      <div class="ow-bar<?= $b['top'] ? ' is-top' : '' ?>">
        <div class="ow-bar-fill">
          <?php if ($b['top']): ?><em><?= e(number_format($b['jt'], 1, '.', '')) ?>jt</em><?php endif; ?>
          <i style="height: <?= (int) $b['tinggi'] ?>%" title="<?= e($b['hari']) ?>: <?= e(number_format($b['jt'], 1, ',', '')) ?> jt"></i>
        </div>
        <span><?= e($b['hari']) ?></span>
      </div>
    <?php endforeach; ?>
  </div>
</article>

<article class="ow-card ow-trx">
  <header>
    <div><h2>Riwayat Transaksi Terbaru</h2><p>5 transaksi terverifikasi terakhir dari seluruh kasir cabang</p></div>
    <small id="owStamp">Pembaruan otomatis tiap 30 detik</small>
  </header>
  <div class="ow-scroll">
    <table class="ow-table">
      <thead><tr><th>ID Struk</th><th>Waktu</th><th>Meja</th><th>Metode Bayar</th><th class="num">Total Nominal</th><th>Status</th></tr></thead>
      <tbody id="trxBody">
        <?php foreach ($trx as $t): ?>
          <tr>
            <td class="ow-id"><?= e($t['id']) ?></td>
            <td><?= e($t['waktu']) ?></td>
            <td><b><?= e($t['meja']) ?></b></td>
            <td><?= e($t['metode']) ?></td>
            <td class="num"><b><?= e(rp($t['total'])) ?></b></td>
            <td><span class="ow-badge"><?= e($t['status']) ?></span></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <footer class="ow-pager"><span class="is-off">Sebelumnya</span><b>1</b><span class="is-off">Berikutnya</span></footer>
</article>
<?php partial('layout/owner_close'); ?>
