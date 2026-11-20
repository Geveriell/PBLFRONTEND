<?php
/** Pusat Kontrol & Multi-Role. Variabel: $role, $roles, $antrean, $n, $owData, $frameUrl. */
partial('layout/owner_open', ['active' => 'hub', 'status' => false]);
?>
<nav class="ow-tabs" aria-label="Pilih role">
  <?php foreach ($roles as $k => [$label, $ic]): ?>
    <a href="<?= e(url('owner', ['role' => $k])) ?>" class="<?= $role === $k ? 'is-on' : '' ?>"<?= $role === $k ? ' aria-current="page"' : '' ?>>
      <i data-ico="<?= e($ic) ?>"></i><span><?= e($label) ?></span>
      <?php if ($k === 'dapur'): ?><em class="ow-tab-n" id="owTabN"><?= count($antrean) ?></em><?php endif; ?>
    </a>
  <?php endforeach; ?>
</nav>

<?php if ($role === 'dapur'): ?>
  <section class="ow-dapur">
    <header class="ow-banner">
      <h1>Dapur</h1>
      <div class="ow-pills">
        <span class="ow-pill">Baru <b id="cBaru"><?= $n['baru'] ?></b></span>
        <span class="ow-pill">Diproses <b id="cMasak"><?= $n['masak'] ?></b></span>
        <span class="ow-pill">Siap <b id="cSiap"><?= $n['siap'] ?></b></span>
        <span class="ow-pill">Rata-rata: <b class="is-plain"><?= Dapur::RATA_MENIT ?> mnt</b></span>
      </div>
    </header>

    <div class="ow-cols">
      <section class="ow-col" data-col="baru">
        <div class="ow-col-h ow-col-h--baru"><h2>Pesanan Baru</h2><span class="ow-count" id="hBaru"><?= $n['baru'] ?></span></div>
        <div class="ow-list" id="listBaru"></div>
        <p class="ow-empty" data-empty="baru" hidden>Tidak ada pesanan baru.</p>
      </section>
      <section class="ow-col" data-col="masak">
        <div class="ow-col-h ow-col-h--masak"><h2>Sedang Dimasak</h2><span class="ow-count" id="hMasak"><?= $n['masak'] ?></span></div>
        <div class="ow-list" id="listMasak"></div>
        <p class="ow-empty" data-empty="masak" hidden>Belum ada yang dimasak.</p>
      </section>
      <section class="ow-col" data-col="siap">
        <div class="ow-col-h ow-col-h--siap"><h2>Siap Disajikan</h2><span class="ow-count ow-count--siap" id="hSiap"><?= $n['siap'] ?></span></div>
        <div class="ow-list" id="listSiap"></div>
        <p class="ow-hint"><i data-ico="bell"></i><span>Pesanan yang ditandai siap akan otomatis memunculkan notifikasi di ponsel waiter &amp; pelanggan pada halaman pelacakan pesanan meja.</span></p>
      </section>
    </div>
  </section>
  <script type="application/json" id="owData"><?= json_encode($owData, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?></script>

<?php elseif ($frameUrl !== null): ?>
  <section class="ow-frame">
    <iframe src="<?= e($frameUrl) ?>" title="<?= e($roles[$role][0]) ?>" loading="lazy"></iframe>
    <a class="ow-frame-open" href="<?= e($frameUrl) ?>" target="_blank" rel="noopener">Buka layar penuh</a>
  </section>

<?php else: ?>
  <section class="ow-card ow-soon">
    <i data-ico="<?= e($roles[$role][1]) ?>"></i>
    <h2><?= e($roles[$role][0]) ?></h2>
    <p>Layar Waiters belum tersedia di frontend ini. Setelah halamannya dibuat, daftarkan di <code>config/routes.php</code> lalu isi <code>Owner::frame()</code> agar tampil di tab ini.</p>
  </section>
<?php endif; ?>
<?php partial('layout/owner_close'); ?>
