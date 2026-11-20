# Bon Surya — Struktur Proyek

```
PBL_Frontend/
├── index.php              Front controller: index.php?page=<nama>
├── config/
│   ├── config.php         Konstanta aplikasi & DB
│   ├── database.php      Koneksi PDO db()
│   ├── helpers.php        rp(), e(), url(), asset(), render(), partial(), meja(), csrf_token()
│   ├── bootstrap.php      Sesi + memuat config, helper, models
│   └── routes.php         Daftar halaman: view, layout, css, js, closure data (memanggil models)
├── models/                Menu, Cart, Pricing, Admin, Kasir, Dapur  ← sambungkan ke backend/DB di sini
├── views/
│   ├── layout/            header.php, footer.php + partial (admin_open/close, customer_top, page_top, bottom_nav)
│   ├── customer/          menu, detail, cart, checkout, payment, split, status
│   ├── admin/             data_menu, sales_analysis, catering, promo_price, (table_status, kds, cashier_report)
│   ├── kasir/cashier.php
│   ├── dapur/kitchen.php
│   └── errors/404.php
├── css/
│   ├── bootstrap.min.css  Bootstrap 5.3 lokal
│   ├── style.css          Tema + override Bootstrap
│   └── pages/             CSS per halaman
├── js/
│   ├── bootstrap.bundle.min.js  Bootstrap JS lokal
│   ├── icons.js
│   └── pages/             JS per halaman
├── api/cart.php           Endpoint JSON keranjang
├── data/  database/  assets/
```

Halaman: `?page=menu|detail|cart|checkout|payment|split|status|kasir|dapur|admin-menu|admin-sales|admin-catering|admin-promo`
(+ `admin-tables`, `admin-kds`, `admin-cashier` — halaman admin lama, tanpa menu).

Menambah halaman: buat `views/<grup>/<nama>.php`, tambahkan satu entri di `config/routes.php`.
Header/footer tidak perlu di-include manual: `render()` memanggilnya otomatis.

## Role Owner (tambahan)

Halaman: `?page=owner` (Pusat Kontrol & Multi-Role; tab lewat `&role=dapur|kasir|waiters|customer|admin`),
`owner-laporan`, `owner-akses`, `owner-keuangan`. Filter periode: `&p=hari|7hari|bulan|tahun` atau `&p=tanggal&d=YYYY-MM-DD`.

File baru: `models/Owner.php`, `views/owner/*`, `views/layout/owner_open.php` + `owner_close.php`,
`css/pages/owner.css`, `js/pages/owner.js`, `js/pages/owner-dapur.js`.
Satu-satunya file lama yang disentuh: `config/routes.php` (require model Owner + 4 entri rute di akhir). Tab Dapur memakai `Dapur::antrean()`;
tab Kasir/Customer/Admin menampilkan halaman yang sudah ada lewat iframe.
