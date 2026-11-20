<?php
/** Hak Akses & Kendali Sistem. Variabel: $peran, $izin, $kendali. Perubahan masih contoh (belum tersimpan ke backend). */
partial('layout/owner_open', ['active' => 'akses', 'status' => false]);
?>
<header class="ow-head ow-head--card">
  <div>
    <h1>Hak Akses &amp; Kendali Sistem</h1>
    <p>Atur izin tiap role dan kendali operasional seluruh cabang.</p>
  </div>
</header>

<article class="ow-card ow-matrix">
  <header><h2>Matriks Hak Akses</h2><p>Role Owner selalu memiliki akses penuh dan tidak dapat diubah.</p></header>
  <div class="ow-scroll">
    <table class="ow-table ow-table--m">
      <thead>
        <tr><th>Izin</th><?php foreach ($peran as $p): ?><th class="mid"><?= e($p) ?></th><?php endforeach; ?></tr>
      </thead>
      <tbody>
        <?php foreach ($izin as [$kode, $label, $ket, $nilai]): ?>
          <tr>
            <td><b><?= e($label) ?></b><small><?= e($ket) ?></small></td>
            <?php foreach ($peran as $i => $p): ?>
              <td class="mid">
                <label class="ow-sw">
                  <input type="checkbox" data-izin="<?= e($kode) ?>" data-peran="<?= e($p) ?>" aria-label="<?= e($label . ' — ' . $p) ?>"<?= !empty($nilai[$i]) ? ' checked' : '' ?><?= $i === 0 ? ' disabled' : '' ?>>
                  <span></span>
                </label>
              </td>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</article>

<article class="ow-card ow-ctrl">
  <header><h2>Kendali Sistem</h2><p>Berlaku untuk seluruh cabang yang tersinkron.</p></header>
  <ul>
    <?php foreach ($kendali as [$kode, $label, $ket, $aktif, $kunci]): ?>
      <li>
        <div><b><?= e($label) ?></b><small><?= e($ket) ?></small></div>
        <label class="ow-sw">
          <input type="checkbox" data-kendali="<?= e($kode) ?>" aria-label="<?= e($label) ?>"<?= $aktif ? ' checked' : '' ?><?= $kunci ? ' disabled' : '' ?>>
          <span></span>
        </label>
      </li>
    <?php endforeach; ?>
  </ul>
  <p class="ow-fine">Perubahan di halaman ini masih contoh dan belum tersimpan ke server.</p>
</article>
<?php partial('layout/owner_close'); ?>
