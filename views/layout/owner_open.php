<?php
/**
 * Rangka halaman Owner (sidebar + topbar). Variabel: $active (hub|laporan|akses|keuangan), $status (bool, kotak status di sidebar).
 * Ditutup oleh owner_close.php. Dipanggil dari awal tiap view owner/* (header.php tidak diubah).
 */
$u = Owner::user();
?>
<div class="ow">
  <aside class="ow-side">
    <div class="ow-brand">
      <span class="ow-logo">S</span>
      <div><b>BON SURYA</b><small>SUPERVISOR ACCESS</small></div>
    </div>
    <nav class="ow-nav" aria-label="Menu owner">
      <?php foreach (Owner::nav() as [$k, $label, $route]): ?>
        <a href="<?= e(url($route)) ?>" class="<?= $active === $k ? 'is-active' : '' ?>"<?= $active === $k ? ' aria-current="page"' : '' ?>><?= e($label) ?></a>
      <?php endforeach; ?>
    </nav>
    <?php if (!empty($status)): ?>
      <div class="ow-side-foot">
        <div class="ow-status">
          <p><small>STATUS OPERASIONAL</small><b><?= (int) $status ?> Cabang Tersinkron</b></p>
          <i class="ow-dot" aria-hidden="true"></i>
        </div>
        <p class="ow-ver"><span>Savor &amp; Dine</span><em>v2.4 Console</em></p>
      </div>
    <?php endif; ?>
  </aside>

  <div class="ow-wrap">
    <header class="ow-top">
      <span class="ow-me"><b><?= e($u['nama']) ?></b><small><?= e($u['peran']) ?></small></span>
      <i class="ow-avatar" aria-hidden="true"><?= e($u['inisial']) ?></i>
    </header>
    <main class="ow-main">
