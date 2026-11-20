<?php
/** Layout penutup — memuat ikon, Bootstrap JS lokal, lalu JS khusus halaman (js/pages/<nama>.js). */
if ($layout === 'admin') { partial('layout/admin_close'); }
?>
  <script src="<?= e(asset('js/icons.js')) ?>"></script>
  <script src="<?= e(asset('js/bootstrap.bundle.min.js')) ?>"></script>
<?php foreach ($js as $j): ?>
  <script src="<?= e(asset('js/pages/' . $j . '.js')) ?>"></script>
<?php endforeach; ?>
</body>
</html>
