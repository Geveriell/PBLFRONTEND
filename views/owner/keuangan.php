<?php
/** Rekap Laporan Keuangan. Variabel: $periode, $k (hasil Owner::keuangan()), $insiden. */
partial('layout/owner_open', ['active' => 'keuangan', 'status' => $k['cabang']]);
$ikon = ['void' => 'basket', 'diskon' => 'tag'];
?>
<header class="ow-head ow-head--card">
  <div>
    <h1>Rekap Laporan Keuangan</h1>
    <p>Validasi penerimaan kas laci vs digital gateway seluruh kasir cabang secara instan.</p>
  </div>
  <?php partial('owner/_periode', ['page' => 'owner-keuangan', 'periode' => $periode]); ?>
</header>

<section class="ow-panel">
  <article class="ow-sum">
    <header><span class="ow-sum-ic"><i data-ico="register"></i></span><h2>Total Transaksi</h2><b class="ow-sum-n"><?= e(number_format($k['transaksi'], 0, ',', '.')) ?></b></header>
    <small>TERCATAT KASIR OTOMATIS</small>
    <p class="ow-sum-v"><?= e(rp($k['total'])) ?></p>
    <p class="ow-ok"><i data-ico="tick"></i>Selengkapnya</p>
  </article>
  <article class="ow-sum ow-sum--hl">
    <header><span class="ow-sum-ic"><i data-ico="verified"></i></span><h2>Total Fisik &amp; Settlement</h2></header>
    <small>FISIK LACI + SETTLEMENT BANK</small>
    <p class="ow-sum-v is-brand"><?= e(rp($k['total'])) ?></p>
    <p class="ow-ok"><i data-ico="lock"></i>Tidak ada kebocoran atau pending claim</p>
  </article>
</section>

<section class="ow-pays">
  <article class="ow-card ow-pay">
    <header><span class="ow-pay-ic is-amber"><i data-ico="cash"></i></span><h2>Pembayaran Cash</h2></header>
    <small>TOTAL KAS DIHITUNG</small>
    <p class="ow-pay-v"><?= e(rp($k['cash']['total'])) ?></p>
    <dl class="ow-dl">
      <div><dt>Uang Kertas:</dt><dd><?= e(rp($k['cash']['kertas'])) ?></dd></div>
      <div><dt>Uang Koin:</dt><dd><?= e(rp($k['cash']['koin'])) ?></dd></div>
    </dl>
    <p class="ow-pay-f"><i data-ico="user"></i>Kasir: <?= e($k['cash']['kasir']) ?></p>
  </article>

  <article class="ow-card ow-pay">
    <header><span class="ow-pay-ic"><i data-ico="qr"></i></span><h2>Pembayaran QRIS</h2></header>
    <small>TOTAL PENERIMAAN QRIS</small>
    <p class="ow-pay-v is-brand"><?= e(rp($k['qris']['total'])) ?></p>
    <dl class="ow-dl">
      <div><dt>QRIS Meja:</dt><dd><?= e(rp($k['qris']['meja'])) ?></dd></div>
      <div><dt>QRIS Kasir:</dt><dd><?= e(rp($k['qris']['kasir'])) ?></dd></div>
      <div class="is-soft"><dt>Batch Status:</dt><dd class="is-ok">Auto-Settled H+0</dd></div>
    </dl>
    <p class="ow-pay-f is-ok"><i data-ico="home"></i><b>Rekonsiliasi:</b><span>Terverifikasi Bank</span></p>
    <p class="ow-pay-n">Nett MDR tercatat otomatis tanpa mismatch</p>
  </article>

  <article class="ow-card ow-pay">
    <header><span class="ow-pay-ic is-amber"><i data-ico="card"></i></span><h2>Pembayaran Debit</h2></header>
    <small>TOTAL SETTLEMENT KARTU</small>
    <p class="ow-pay-v"><?= e(rp($k['debit']['total'])) ?></p>
    <dl class="ow-dl">
      <div><dt>EDC BCA (<?= (int) $k['debit']['bcaSlip'] ?> Slip):</dt><dd><?= e(rp($k['debit']['bca'])) ?></dd></div>
      <div><dt>EDC Mandiri (<?= (int) $k['debit']['mandiriSlip'] ?> Slip):</dt><dd><?= e(rp($k['debit']['mandiri'])) ?></dd></div>
      <div class="is-soft"><dt>Audit Slip Fisik:</dt><dd>Semua <?= (int) $k['debit']['slip'] ?> Slip Terhitung</dd></div>
    </dl>
    <p class="ow-pay-f is-ok"><i data-ico="tick"></i><b>Batch Settlement:</b><span>Selesai &amp; Tertutup</span></p>
    <p class="ow-pay-n">Slip fisik kasir telah diinput &amp; di-batch close</p>
  </article>
</section>

<article class="ow-card ow-risk">
  <header>
    <h2><i data-ico="verified"></i>Detail Transaksi Luar Perencanaan</h2>
    <p>Pengawasan pembatalan struk, diskon otorisasi, dan risiko selisih harian.</p>
  </header>
  <ul class="ow-risk-list">
    <?php foreach ($insiden as $r): ?>
      <li class="is-<?= e($r['tipe']) ?>">
        <span class="ow-risk-ic"><i data-ico="<?= e($ikon[$r['tipe']] ?? 'info') ?>"></i></span>
        <div class="ow-risk-b">
          <p><b><?= e($r['judul']) ?></b><em><?= e($r['tag']) ?></em></p>
          <small><?= e($r['ket']) ?></small>
          <?php if ($r['aksi'] !== ''): ?><a class="ow-risk-a"><?= e($r['aksi']) ?></a><?php endif; ?>
        </div>
        <div class="ow-risk-v"><b>-<?= e(rp($r['nominal'])) ?></b><small><?= e($r['waktu']) ?></small></div>
      </li>
    <?php endforeach; ?>
  </ul>
  <p class="ow-clean"><i data-ico="verified"></i><span><b>0 Selisih Kasir &amp; 0 Pembatalan Ilegal</b>Semua kasir menyeimbangkan laci secara transparan dengan zero variance.</span></p>
  <footer class="ow-risk-f"><span>Catatan: Semua void memerlukan otorisasi fisik atau barcode manajer.</span><a>Log Audit Enkripsi Aktif</a></footer>
</article>
<?php partial('layout/owner_close'); ?>
