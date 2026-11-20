<?php
declare(strict_types=1);

/**
 * Keranjang berbasis "porsi": setiap porsi adalah satu baris dengan racikannya sendiri
 * (level, add-on, catatan). Dua porsi menu yang sama bisa punya racikan berbeda.
 *
 * $_SESSION['cart'] = ['L1' => ['mid' => 2, 'r' => 1, 'a' => [0], 'note' => '', 'label' => '...', 'extra' => 3000], ...]
 */
class Cart
{
    public static function seedDemo(): void
    {
        // Buang format keranjang lama (menu_id => qty) dari sesi yang masih tersisa.
        foreach ($_SESSION['cart'] ?? [] as $l) {
            if (!is_array($l)) {
                unset($_SESSION['cart'], $_SESSION['opt']);
                break;
            }
        }
        if (DEMO_CART && !isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
            foreach ([1, 2, 3] as $mid) {
                self::add($mid, 0, [], '');
            }
        }
    }

    private static function validId(string $lid): bool
    {
        return (bool) preg_match('/^L\d+$/', $lid);
    }

    /** Jumlah porsi sebuah menu di keranjang. */
    public static function qty(int $mid): int
    {
        $n = 0;
        foreach ($_SESSION['cart'] ?? [] as $l) {
            if ((int) $l['mid'] === $mid) {
                $n++;
            }
        }
        return $n;
    }

    /** Validasi pilihan & hitung label + tambahan harga. */
    private static function build(int $mid, int $r, array $a, string $note): ?array
    {
        if (Menu::find($mid) === null) {
            return null;
        }
        $o = Menu::options($mid);
        if (!isset($o['req']['items'][$r])) {
            return null;
        }
        $label  = [$o['req']['items'][$r][0]];
        $extra  = (int) $o['req']['items'][$r][2];
        $picked = [];
        foreach (array_unique($a) as $i) {
            if (isset($o['add'][$i])) {
                $label[] = $o['add'][$i][0] . ' (+' . intdiv((int) $o['add'][$i][2], 1000) . 'k)';
                $extra  += (int) $o['add'][$i][2];
                $picked[] = (int) $i;
            }
        }
        return [
            'mid' => $mid, 'r' => $r, 'a' => $picked, 'extra' => $extra,
            'label' => implode(' • ', $label),
            'note'  => mb_substr(trim(strip_tags($note)), 0, 100),
        ];
    }

    /** Tambah satu porsi baru. Mengembalikan id baris, atau null bila pilihan tidak valid. */
    public static function add(int $mid, int $r, array $a, string $note): ?string
    {
        $row = self::build($mid, $r, $a, $note);
        if ($row === null || self::items() >= 99) {
            return null;
        }
        $_SESSION['seq'] = (int) ($_SESSION['seq'] ?? 0) + 1;
        $lid = 'L' . $_SESSION['seq'];
        $_SESSION['cart'][$lid] = $row;
        return $lid;
    }

    /** Ubah racikan satu porsi yang sudah ada. */
    public static function update(string $lid, int $r, array $a, string $note): bool
    {
        if (!self::validId($lid) || !isset($_SESSION['cart'][$lid])) {
            return false;
        }
        $row = self::build((int) $_SESSION['cart'][$lid]['mid'], $r, $a, $note);
        if ($row === null) {
            return false;
        }
        $_SESSION['cart'][$lid] = $row;
        return true;
    }

    public static function remove(string $lid): bool
    {
        if (!self::validId($lid) || !isset($_SESSION['cart'][$lid])) {
            return false;
        }
        unset($_SESSION['cart'][$lid]);
        return true;
    }

    /** Hapus porsi terakhir dari sebuah menu (tombol − di halaman menu). */
    public static function removeLast(int $mid): void
    {
        $cart = $_SESSION['cart'] ?? [];
        foreach (array_reverse(array_keys($cart)) as $lid) {
            if ((int) $cart[$lid]['mid'] === $mid) {
                unset($_SESSION['cart'][$lid]);
                return;
            }
        }
    }

    public static function items(): int
    {
        return count($_SESSION['cart'] ?? []);
    }

    /** Satu baris keranjang lengkap (untuk halaman detail mode ubah). */
    public static function line(string $lid): ?array
    {
        foreach (self::lines() as $l) {
            if ($l['lid'] === $lid) {
                return $l;
            }
        }
        return null;
    }

    public static function lines(): array
    {
        $rows = [];
        $nth  = [];
        foreach ($_SESSION['cart'] ?? [] as $lid => $c) {
            $m = Menu::find((int) $c['mid']);
            if ($m === null) {
                continue;
            }
            $mid = (int) $m['id'];
            $nth[$mid] = ($nth[$mid] ?? 0) + 1;
            $unit = (int) $m['harga'] + (int) $c['extra'];
            $rows[] = [
                'lid' => (string) $lid, 'm' => $m, 'mid' => $mid, 'no' => $nth[$mid],
                'qty' => 1, 'unit' => $unit, 'sum' => $unit,
                'label' => $c['label'] ?: (string) $m['opsi'], 'note' => $c['note'],
                'r' => (int) $c['r'], 'a' => $c['a'],
            ];
        }
        return $rows;
    }

    public static function summary(?array $lines = null): array
    {
        $items = 0;
        $subtotal = 0;
        foreach ($lines ?? self::lines() as $l) {
            $items += $l['qty'];
            $subtotal += $l['sum'];
        }
        $pajak = (int) round($subtotal * PAJAK_SERVIS);
        return ['items' => $items, 'subtotal' => $subtotal, 'pajak' => $pajak, 'total' => $subtotal + $pajak];
    }

    public static function ref(): string
    {
        if (empty($_SESSION['ref'])) {
            $_SESSION['ref'] = 'A' . random_int(1000, 9999);
        }
        return '#' . $_SESSION['ref'];
    }

    public static function checkout(string $metode): void
    {
        $lines = self::lines();
        if (!$lines) {
            return;
        }
        $_SESSION['order'] = ['lines' => $lines, 'sum' => self::summary($lines), 'ref' => self::ref(),
                              'metode' => $metode, 'jam' => date('H:i')];
        $_SESSION['cart'] = [];
        unset($_SESSION['ref']);
    }

    public static function order(): ?array
    {
        return $_SESSION['order'] ?? null;
    }
}
