/* Halaman Owner: filter tanggal, pembaruan otomatis riwayat transaksi, saklar hak akses. */
(function () {
  /* Pilih Tanggal */
  const btn = document.getElementById('owPickBtn');
  const inp = document.getElementById('owPickDate');
  if (btn && inp) {
    btn.addEventListener('click', () => {
      if (typeof inp.showPicker === 'function') { try { inp.showPicker(); return; } catch (e) { /* jatuh ke fokus */ } }
      inp.focus(); inp.click();
    });
    inp.addEventListener('change', () => {
      if (!inp.value) return;
      const u = new URL(btn.dataset.page, location.href);
      u.searchParams.set('d', inp.value);
      location.href = u.toString();
    });
  }

  /* Riwayat transaksi: ambil ulang tiap 30 detik */
  const body = document.getElementById('trxBody');
  if (body) {
    setInterval(() => {
      if (document.hidden) return;
      fetch(location.href, { credentials: 'same-origin' })
        .then(r => (r.ok ? r.text() : Promise.reject()))
        .then(html => {
          const next = new DOMParser().parseFromString(html, 'text/html').getElementById('trxBody');
          if (next) body.innerHTML = next.innerHTML;
        })
        .catch(() => { /* abaikan; coba lagi di putaran berikutnya */ });
    }, 30000);
  }

  /* Hak akses & kendali sistem (contoh — belum disimpan ke server) */
  document.addEventListener('change', e => {
    const c = e.target;
    if (!c.matches || !c.matches('input[data-izin], input[data-kendali]')) return;
    const nama = c.getAttribute('aria-label') || 'Pengaturan';
    toast(nama + (c.checked ? ' diaktifkan' : ' dinonaktifkan') + ' (contoh, belum tersimpan)');
  });
})();
