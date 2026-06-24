# Nasi Timbel Bawean — QR Menu & POS

Aplikasi QR Menu terintegrasi dengan POS kasir untuk **Nasi Timbel Bawean**, resto nasi timbel khas Sunda di Jl. Bawean No. 3, Bandung (buka 07.30–17.00).

## Fitur MVP

- **QR Menu / Katalog online** — pelanggan pesan dari HP tanpa login
- **QR per meja** — scan QR meja, pesan ke open bill meja tersebut
- **Open bill / close bill** — pesanan meja digabung sampai kasir tutup & bayar
- **Waiter order** — kasir/pelayan input pesanan manual dari POS
- **Keranjang & checkout** — session cart, form pelanggan (QR outlet)
- **Antrian POS** — kasir melihat order pending & siap saji (update live)
- **Kitchen Display (KDS)** — layar dapur 3 kolom: antrian → masak → siap
- **Polling antrian** — antrian kasir & KDS update otomatis tiap 15 detik (cocok shared hosting)
- **Varian menu** — ukuran, level pedas, topping (+ harga) di QR menu & POS
- **Split bill** — bayar sebagian item dari satu bill meja
- **Status dapur** — pending → cooking → ready → disajikan (confirmed)
- **Pembayaran** — tunai / transfer manual + QRIS (Midtrans) + cetak struk thermal
- **Back office** — kelola kategori, menu, outlet, meja, QR code
- **Laporan** — penjualan periode, menu terlaris, export CSV, riwayat order
- **Shift kasir** — buka/tutup shift, modal awal, rekonsiliasi kas, riwayat shift
- **Edit bill** — ubah qty / hapus item yang belum dibayar di open bill meja
- **Multi-outlet** — kelola beberapa cabang, switch cabang aktif, assign kasir per cabang
- **Inventori stok** — lacak stok per menu, potong otomatis saat order, alert stok menipis, penyesuaian manual
- **Loyalty / membership** — poin pelanggan per cabang (earn saat bayar, redeem di kasir / QRIS)
- **Notifikasi order** — Telegram & WhatsApp (webhook / Fonnte) saat pesanan baru & siap saji

## Stack

- Laravel 10 + MySQL
- Blade + Tailwind CDN + Alpine (customer & admin)
- Auth: Laravel Breeze (Blade)

## Setup

```bash
cd c:\laragon\www\qr-pos
cp .env.example .env   # jika belum ada
# Set DB_DATABASE=qr_pos di .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link   # lihat catatan di bawah jika muncul error
```

**`storage:link` di Windows:** Jika muncul pesan `The [public\storage] link already exists`, symlink **sudah terpasang dengan benar** — lanjutkan setup, tidak perlu diperbaiki. Laravel menampilkan pesan ini karena junction Windows tidak dikenali PHP sebagai symlink (`is_link()` = false), meskipun path sudah mengarah ke `storage/app/public`.

Untuk membuat ulang link (jarang diperlukan):

```powershell
Remove-Item public\storage -Force
php artisan storage:link
```

Domain Laragon: `http://qr-pos.test`

**Virtual host belum jalan?** Jika browser menampilkan `DNS_PROBE_FINISHED_NXDOMAIN`:

1. Buka **Laragon** → klik kanan folder **www/qr-pos** → **Map to qr-pos.test** (atau **Create virtual host**)
2. Klik **Stop All**, lalu **Start All** di Laragon (reload Apache + update file `hosts`)
3. Jika masih gagal, tambahkan manual di `C:\Windows\System32\drivers\etc\hosts` (butuh Run as Administrator):

```
127.0.0.1      qr-pos.test       #laragon magic!
```

4. Buka ulang `http://qr-pos.test`

## Cetak Thermal (58/80mm)

Struk POS dioptimasi untuk printer thermal. Set lebar kertas di `.env`:

```
RECEIPT_PAPER_WIDTH=58
RECEIPT_AUTO_PRINT=true
```

Setelah pembayaran, browser akan membuka dialog cetak otomatis. Tombol **Cetak Thermal** juga tersedia di halaman struk.

## QRIS Midtrans (Sandbox)

1. Daftar di [Midtrans Sandbox](https://dashboard.sandbox.midtrans.com/)
2. Aktifkan metode **QRIS** di Settings → Payment Methods
3. Isi `.env`:

```
MIDTRANS_SERVER_KEY=SB-Mid-server-...
MIDTRANS_CLIENT_KEY=SB-Mid-client-...
MIDTRANS_IS_PRODUCTION=false
```

4. Webhook URL (production/ngrok): `https://domain-anda.com/webhooks/midtrans`

Di local, status pembayaran dicek via polling otomatis setiap 3 detik.

## Shift Kasir

Kasir wajib membuka shift sebelum menerima pembayaran (POS). Atur di `.env`:

```
POS_REQUIRE_OPEN_SHIFT=true
```

Set `false` untuk development tanpa shift.

| URL | Keterangan |
|-----|------------|
| `/pos/shift` | Buka / tutup shift kasir |
| `/admin/reports` | Laporan penjualan periode + export CSV |
| `/admin/shifts` | Riwayat shift kasir (admin) |

## Akun Demo

| Role  | Email              | Password  |
|-------|--------------------|-----------|
| Admin | admin@qrpos.test   | password  |
| Kasir | kasir@qrpos.test   | password  |

## URL Penting

| URL | Keterangan |
|-----|------------|
| `/login` | Login admin/kasir |
| `/o/nasi-timbel-bawean` | Katalog pelanggan (QR outlet) |
| `/o/nasi-timbel-bawean/t/{token}` | Katalog per meja (QR meja) |
| `/pos` | Antrian kasir |
| `/pos/tables` | Meja & open bill (waiter order) |
| `/kitchen` | Layar dapur (KDS) |
| `/admin/menu-items` | Kelola menu & varian |
| `/admin/inventory` | Stok menu, penyesuaian, riwayat pergerakan |
| `/admin/loyalty` | Program poin & member |
| `/admin/notifications` | Telegram / WhatsApp alert order |
| `/admin/tables` | Kelola meja & QR per meja |
| `/dashboard` | Dashboard admin |
| `/admin/outlet` | Pengaturan outlet & QR |
| `/pos/shift` | Shift kasir (buka/tutup) |
| `/admin/reports` | Laporan & export CSV |
| `/admin/shifts` | Riwayat shift kasir |
| `/admin/outlets` | Kelola cabang / outlet |
| `/admin/menu-copy` | Salin menu antar cabang |
| `/admin/users` | Assign kasir ke cabang |

## Multi-Outlet

Staff (admin/kasir) bekerja dalam konteks **cabang aktif** yang dipilih di sidebar. Admin melihat semua cabang; kasir hanya cabang yang di-assign.

| URL | Keterangan |
|-----|------------|
| `/o/nasi-timbel-bawean` | Katalog pelanggan |
| `/admin/outlets` | CRUD cabang + QR per cabang |
| `/admin/users` | Assign akses cabang untuk kasir |

Menu, meja, laporan, POS, dan KDS otomatis ter-scope ke cabang aktif.

### Copy menu antar cabang

Salin katalog lengkap (kategori, menu, foto, varian) dari cabang sumber ke cabang lain.

| URL | Keterangan |
|-----|------------|
| `/admin/menu-copy` | Pilih cabang sumber & tujuan, opsi timpa / stok / varian |

Contoh: salin menu **Nasi Timbel Bawean** ke cabang baru saat buka outlet kedua.

## Inventori Stok

Aktifkan **Lacak stok** saat menambah/edit menu. Stok dipotong saat item masuk ke order (bukan saat keranjang). Stok 0 otomatis menyembunyikan menu dari katalog. Keranjang tetap memvalidasi qty vs stok tersedia.

| URL | Keterangan |
|-----|------------|
| `/admin/inventory` | Daftar stok, penyesuaian manual, riwayat |
| Dashboard admin | Alert menu stok menipis |

Demo seeder: 27 menu (paket, nasi, lauk, pepes, minuman) — lihat `DatabaseSeeder.php`.

## Loyalty / Membership

Pelanggan dikenali dari **nomor telepon** order (per cabang). Poin didapat setiap pembayaran lunas; redeem di kasir POS atau checkout QRIS.

| URL | Keterangan |
|-----|------------|
| `/admin/loyalty` | Pengaturan earn/redeem + daftar member |
| POS pembayaran | Redeem poin jika order punya telepon valid |
| Checkout QR | Cek saldo poin + redeem (QRIS) |

Default: **1 poin / Rp 1.000** spend · **1 poin = Rp 100** diskon · min redeem 50 poin · maks 50% tagihan.

Demo member: `081234567890` — 250 poin (Nasi Timbel Bawean).

## Notifikasi (Telegram / WhatsApp)

Kirim alert ke staff saat **pesanan baru masuk antrian** atau **siap disajikan** (KDS). Konfigurasi per cabang di admin.

| URL | Keterangan |
|-----|------------|
| `/admin/notifications` | Pengaturan Telegram / WhatsApp + kirim test |

### Telegram
1. Buat bot via [@BotFather](https://t.me/BotFather)
2. Tambahkan bot ke grup staff, kirim pesan di grup
3. Buka `https://api.telegram.org/bot<TOKEN>/getUpdates` untuk melihat `chat_id`
4. Isi bot token & chat ID di admin → aktifkan → **Kirim test**

### WhatsApp
- **Webhook generik** — POST JSON `{ "message", "target" }` ke URL Anda (n8n, Make, Wablas custom, dll.)
- **Fonnte** — isi API token dari [fonnte.com](https://fonnte.com), target = nomor/grup staff

Opsi **Siap → WA pelanggan** mengirim ke nomor order via Fonnte (butuh provider Fonnte).

```bash
# Production: proses antrian notifikasi
php artisan queue:work

# Development tanpa queue worker:
QUEUE_CONNECTION=sync
```

Global off: `NOTIFY_ENABLED=false` di `.env`.

