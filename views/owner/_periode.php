<?php
/** Filter periode. Variabel: $page (route), $periode (hasil Owner::periode()). */
$on = $periode['kunci'];
?>
<div class="ow-periode" role="group" aria-label="Pilih periode">
  <?php foreach (Owner::PERIODE as $k => $label): ?>
    <a href="<?= e(url($page, ['p' => $k])) ?>" class="<?= $on === $k ? 'is-on' : '' ?>"<?= $on === $k ? ' aria-current="true"' : '' ?>><?= e($label) ?></a>
  <?php endforeach; ?>
  <button type="button" class="<?= $on === 'tanggal' ? 'is-on' : '' ?>" id="owPickBtn" data-page="<?= e(url($page, ['p' => 'tanggal'])) ?>">
    <i data-ico="calendar"></i><span><?= $on === 'tanggal' ? e($periode['label']) : 'Pilih Tanggal' ?></span>
  </button>
  <input type="date" class="ow-date" id="owPickDate" aria-label="Pilih tanggal" value="<?= e($periode['tanggal']) ?>" max="<?= e(date('Y-m-d')) ?>">
</div>
