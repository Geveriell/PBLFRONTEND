<?php
declare(strict_types=1);

/**
 * Data halaman role Owner (Executive Owner & Board).
 * Masih data contoh; ganti isinya dengan query DB saat DB_ENABLED aktif.
 * Antrean dapur & kasir tidak diduplikasi di sini — memakai model Dapur / Kasir yang sudah ada.
 */
class Owner
{
    public const PERIODE = ['hari' => 'Hari Ini', '7hari' => '7 Hari', 'bulan' => '1 Bulan', 'tahun' => '1 Tahun'];

    /** Pengali angka contoh per periode. */
    private const FAKTOR = ['hari' => 1, '7hari' => 7, 'bulan' => 30, 'tahun' => 365, 'tanggal' => 1];

    public static function user(): array
    {
        return ['nama' => 'Darmawan Santoso / Hendra Wijaya', 'peran' => 'Executive Owner & Board', 'inisial' => 'DH'];
    }

    /** [kunci aktif, label, route] */
    public static function nav(): array
    {
        return [
            ['hub', 'Pusat Kontrol & Multi-Role', 'owner'],
            ['laporan', 'Laporan & Riwayat Periode', 'owner-laporan'],
            ['akses', 'Hak Akses & Kendali Sistem', 'owner-akses'],
            ['keuangan', 'Laporan Keuangan', 'owner-keuangan'],
        ];
    }

    /** Tab di Pusat Kontrol: kunci => [label, ikon]. */
    public static function roles(): array
    {
        return [
            'dapur'    => ['Dapur / Kitchen', 'pot'],
            'kasir'    => ['Kasir', 'register'],
            'waiters'  => ['Waiters', 'user'],
            'customer' => ['Customer', 'qr'],
            'admin'    => ['Admin Resto', 'verified'],
        ];
    }

    /** Halaman yang sudah ada untuk dipantau lewat iframe di Pusat Kontrol. */
    public static function frame(string $role): ?string
    {
        return ['kasir' => 'kasir', 'customer' => 'menu', 'admin' => 'admin-menu'][$role] ?? null;
    }

    public static function role(): string
    {
        $r = (string) ($_GET['role'] ?? 'dapur');
        return isset(self::roles()[$r]) ? $r : 'dapur';
    }

    /* ---------- Periode ---------- */

    /** Periode aktif dari ?p= (hari|7hari|bulan|tahun|tanggal) dan ?d=YYYY-MM-DD. */
    public static function periode(): array
    {
        $p = (string) ($_GET['p'] ?? 'hari');
        $d = (string) ($_GET['d'] ?? '');
        if ($p === 'tanggal') {
            $ok = preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $d, $m) === 1 && checkdate((int) $m[2], (int) $m[3], (int) $m[1]);
            return $ok ? ['kunci' => 'tanggal', 'tanggal' => $d, 'label' => date('d/m/Y', (int) strtotime($d))] : ['kunci' => 'hari', 'tanggal' => '', 'label' => self::PERIODE['hari']];
        }
        $p = isset(self::PERIODE[$p]) ? $p : 'hari';
        return ['kunci' => $p, 'tanggal' => '', 'label' => self::PERIODE[$p]];
    }

    /* ---------- Laporan & Riwayat Penjualan ---------- */

    public static function ringkasan(string $k): array
    {
        $data = [
            'hari'    => ['pendapatan' => 142850000,   'naik' => '14.2', 'struk' => 1120],
            '7hari'   => ['pendapatan' => 912350000,   'naik' => '9.8',  'struk' => 7840],
            'bulan'   => ['pendapatan' => 3920500000,  'naik' => '11.6', 'struk' => 33600],
            'tahun'   => ['pendapatan' => 47215000000, 'naik' => '18.4', 'struk' => 408800],
            'tanggal' => ['pendapatan' => 142850000,   'naik' => '14.2', 'struk' => 1120],
        ];
        return ($data[$k] ?? $data['hari']) + ['rerata' => 93];
    }

    /** Omset harian 7 hari terakhir (juta rupiah). */
    public static function tren(): array
    {
        $rows = [['Sen', 24.6], ['Sel', 29.1], ['Rab', 26.8], ['Kam', 33.6], ['Jum', 36.5], ['Sab', 36.5], ['Min', 36.5]];
        $max  = max(array_column($rows, 1));
        $out  = [];
        foreach ($rows as $i => [$hari, $jt]) {
            $out[] = ['hari' => $hari, 'jt' => $jt, 'tinggi' => (int) round($jt / $max * 92), 'top' => $i === count($rows) - 1];
        }
        return $out;
    }

    public static function transaksi(): array
    {
        return [
            ['id' => '#SD-240508-01', 'waktu' => '14:22 WIB', 'meja' => 'Meja 12 (VIP)', 'metode' => 'QRIS Dinamis', 'total' => 348000, 'status' => 'Berhasil'],
            ['id' => '#SD-240508-02', 'waktu' => '14:18 WIB', 'meja' => 'Meja 04', 'metode' => 'BCA Debit', 'total' => 185000, 'status' => 'Berhasil'],
            ['id' => '#SD-240508-03', 'waktu' => '14:15 WIB', 'meja' => 'Meja 09', 'metode' => 'Tunai Kasir', 'total' => 92500, 'status' => 'Berhasil'],
            ['id' => '#SD-240508-04', 'waktu' => '14:02 WIB', 'meja' => 'Meja 02', 'metode' => 'QRIS Dinamis', 'total' => 210000, 'status' => 'Berhasil'],
            ['id' => '#SD-240508-05', 'waktu' => '13:50 WIB', 'meja' => 'Meja 17', 'metode' => 'Mandiri Kartu', 'total' => 144000, 'status' => 'Berhasil'],
        ];
    }

    /* ---------- Laporan Keuangan ---------- */

    public static function keuangan(string $k): array
    {
        $f = self::FAKTOR[$k] ?? 1;
        $kertas = 850000 * $f;
        $koin   = 30000 * $f;
        $qrMeja = 74200000 * $f;
        $qrKasir = 21500000 * $f;
        $bca    = 21400000 * $f;
        $mandiri = 11450000 * $f;
        $total  = ($kertas + $koin) + ($qrMeja + $qrKasir) + ($bca + $mandiri);
        return [
            'transaksi' => 343 * $f,
            'total'     => $total,
            'cash'      => ['total' => $kertas + $koin, 'kertas' => $kertas, 'koin' => $koin, 'kasir' => 'Sarah Pratiwi & Budi Santoso'],
            'qris'      => ['total' => $qrMeja + $qrKasir, 'meja' => $qrMeja, 'kasir' => $qrKasir],
            'debit'     => ['total' => $bca + $mandiri, 'bca' => $bca, 'bcaSlip' => 52 * $f, 'mandiri' => $mandiri, 'mandiriSlip' => 27 * $f, 'slip' => 79 * $f],
            'cabang'    => 12,
        ];
    }

    /** Transaksi luar perencanaan (void & diskon manual). tipe: void | diskon. */
    public static function insiden(string $k): array
    {
        if ($k === 'hari' || $k === 'tanggal') {
            return [
                ['tipe' => 'void', 'judul' => 'Void Struk — Meja 04', 'tag' => 'VOID RESMI', 'nominal' => 85000, 'waktu' => 'Pukul 13:42 WIB', 'ket' => 'Dasar: Pelanggan salah input menu (Nasi Putih).', 'aksi' => 'Disetujui SPV Budi S.'],
                ['tipe' => 'diskon', 'judul' => 'Diskon Manual Owner (VIP Promo)', 'tag' => '2 TRANSAKSI', 'nominal' => 150000, 'waktu' => 'Pukul 20:23 WIB', 'ket' => 'Kunjungan Tamu Khusus Rekanan Hendra W.', 'aksi' => ''],
            ];
        }
        $n = [
            '7hari' => [6, 512000, 11, 820000],
            'bulan' => [24, 2040000, 47, 3480000],
            'tahun' => [290, 24650000, 560, 42100000],
        ][$k] ?? [6, 512000, 11, 820000];
        return [
            ['tipe' => 'void', 'judul' => 'Void Struk — ' . $n[0] . ' struk', 'tag' => 'VOID RESMI', 'nominal' => $n[1], 'waktu' => 'Rekap periode', 'ket' => 'Seluruh void sudah melalui otorisasi supervisor atau manajer.', 'aksi' => ''],
            ['tipe' => 'diskon', 'judul' => 'Diskon Manual Owner (VIP Promo)', 'tag' => $n[2] . ' TRANSAKSI', 'nominal' => $n[3], 'waktu' => 'Rekap periode', 'ket' => 'Rekapitulasi diskon manual sepanjang periode.', 'aksi' => ''],
        ];
    }

    /* ---------- Hak Akses & Kendali Sistem ---------- */

    public static function peran(): array
    {
        return ['Owner', 'Admin', 'Kasir', 'Dapur', 'Waiters'];
    }

    /** [kode, label, keterangan, [owner, admin, kasir, dapur, waiters]] — 1 = diizinkan. */
    public static function izin(): array
    {
        return [
            ['dapur_lihat', 'Lihat antrean dapur', 'Memantau pesanan baru, diproses, dan siap', [1, 1, 0, 1, 1]],
            ['dapur_ubah', 'Ubah status pesanan', 'Mulai masak, tandai siap, sudah diantar', [1, 1, 0, 1, 0]],
            ['bayar', 'Terima pembayaran', 'Tunai, QRIS, kartu, dan split bill', [1, 0, 1, 0, 0]],
            ['void', 'Void struk', 'Wajib otorisasi fisik atau barcode manajer', [1, 1, 0, 0, 0]],
            ['diskon', 'Diskon manual', 'Potongan di luar promo terjadwal', [1, 1, 0, 0, 0]],
            ['harga', 'Ubah harga & promo', 'Harga menu dan diskon persentase', [1, 1, 0, 0, 0]],
            ['menu', 'Kelola data menu', 'Tambah, ubah, dan nonaktifkan menu', [1, 1, 0, 0, 0]],
            ['laporan', 'Lihat laporan penjualan', 'Ringkasan dan riwayat transaksi', [1, 1, 0, 0, 0]],
            ['keuangan', 'Lihat laporan keuangan', 'Rekap kas, settlement, dan audit', [1, 0, 0, 0, 0]],
            ['akses', 'Kelola hak akses', 'Mengubah matriks izin di halaman ini', [1, 0, 0, 0, 0]],
        ];
    }

    /** [kode, label, keterangan, aktif, terkunci] */
    public static function kendali(): array
    {
        return [
            ['pesan_qr', 'Terima pesanan pelanggan (QR meja)', 'Jika dimatikan, pelanggan tidak bisa membuat pesanan baru dari meja.', true, false],
            ['void_otorisasi', 'Wajib otorisasi manajer untuk void', 'Void struk hanya sah dengan persetujuan fisik atau barcode manajer.', true, false],
            ['audit', 'Log audit terenkripsi', 'Mencatat void, diskon manual, dan perubahan hak akses. Tidak dapat dimatikan.', true, true],
        ];
    }
}
