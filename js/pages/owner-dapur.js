/* Owner → tab Dapur: papan antrean dapur (data dari models/Dapur.php lewat #owData). */
(function () {
  const dataEl = document.getElementById('owData');
  if (!dataEl) return;
  const $ = (s, r) => (r || document).querySelector(s);
  const esc = s => String(s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
  const data = JSON.parse(dataEl.textContent);
  const BATAS = data.batas * 1000;
  const now = Date.now();
  const orders = data.orders.map(o => Object.assign(o, { since: now - o.detik * 1000 }));
  const LIST = { baru: '#listBaru', masak: '#listMasak', siap: '#listSiap' };
  const mmss = ms => {
    const s = Math.max(0, Math.floor(ms / 1000));
    return String(Math.floor(s / 60)).padStart(2, '0') + ':' + String(s % 60).padStart(2, '0');
  };
  const late = o => o.status === 'masak' && Date.now() - o.since >= BATAS;
  const sub = o => (o.status === 'siap' ? 'Menunggu diantar' : late(o) ? 'Prioritas Utama' : o.tipe);
  const BTN = {
    baru: ['ow-btn--go', 'pot', 'Mulai Masak'],
    masak: ['ow-btn--ready', 'tick', 'Tandai Siap'],
    siap: ['ow-btn--done', 'cloche', 'Sudah Diantar']
  };

  function card(o) {
    const [cls, ic, label] = BTN[o.status];
    return '<article class="ow-oc t-' + o.status + (late(o) ? ' is-late' : '') + '" data-id="' + esc(o.id) + '">' +
      '<header class="ow-oc-h"><div><h3>Meja ' + esc(o.meja) + '<span class="ow-late">Terlambat</span></h3>' +
      '<p>#' + esc(o.id) + ' <i>•</i> <span class="sub">' + esc(sub(o)) + '</span></p></div>' +
      '<time>' + mmss(Date.now() - o.since) + '</time></header>' +
      '<div class="ow-oc-b"><ul class="ow-items">' + o.items.map(i => '<li><em>' + i[0] + '×</em><span>' + esc(i[1]) + '</span></li>').join('') + '</ul>' +
      (o.catatan ? '<p class="ow-note"><i data-ico="info"></i>Catatan: ' + esc(o.catatan) + '</p>' : '') +
      '<button type="button" class="ow-btn ' + cls + '"><i data-ico="' + ic + '"></i>' + label + '</button></div></article>';
  }

  function render() {
    const n = {};
    for (const k in LIST) {
      const rows = orders.filter(o => o.status === k);
      n[k] = rows.length;
      $(LIST[k]).innerHTML = rows.map(card).join('');
      const em = document.querySelector('[data-empty="' + k + '"]');
      if (em) em.hidden = rows.length > 0;
    }
    $('#cBaru').textContent = $('#hBaru').textContent = n.baru;
    $('#cMasak').textContent = $('#hMasak').textContent = n.masak;
    $('#cSiap').textContent = $('#hSiap').textContent = n.siap;
    const tab = $('#owTabN');
    if (tab) tab.textContent = orders.length;
    document.querySelectorAll('.ow-dapur [data-ico]').forEach(el => { el.innerHTML = window.ico(el.dataset.ico); });
  }

  function tick() {
    document.querySelectorAll('.ow-oc').forEach(el => {
      const o = orders.find(x => x.id === el.dataset.id);
      if (!o) return;
      $('time', el).textContent = mmss(Date.now() - o.since);
      const l = late(o);
      if (l !== el.classList.contains('is-late')) { el.classList.toggle('is-late', l); $('.sub', el).textContent = sub(o); }
    });
  }

  document.addEventListener('click', e => {
    const b = e.target.closest('.ow-btn');
    if (!b) return;
    const o = orders.find(x => x.id === b.closest('.ow-oc').dataset.id);
    if (!o) return;
    if (o.status === 'baru') { o.status = 'masak'; o.since = Date.now(); toast('Meja ' + o.meja + ' mulai dimasak'); }
    else if (o.status === 'masak') { o.status = 'siap'; o.since = Date.now(); toast('Meja ' + o.meja + ' siap disajikan · waiter & pelanggan diberi tahu'); }
    else { orders.splice(orders.indexOf(o), 1); toast('Meja ' + o.meja + ' sudah diantar'); }
    render();
  });

  render();
  setInterval(tick, 1000);
})();
