# Deploy ke Hosting (Laravel)

Panduan deploy **Nasi Timbel Bawean** ke shared hosting (cPanel) atau VPS.

## Prasyarat hosting

- PHP **8.1+** (disarankan 8.2)
- Ekstensi: `openssl`, `pdo`, `pdo_mysql`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`
- MySQL / MariaDB
- Composer (CLI atau via cPanel Terminal)
- Document root mengarah ke folder **`public/`**

---

## 1. Push dari komputer lokal

```bash
git push origin main
```

Repo: https://github.com/teenyclaw/timbel-bawean

---

## 2. Clone / pull di server

### cPanel (Git Version Control)

1. cPanel → **Git Version Control** → Clone
2. URL: `https://github.com/teenyclaw/timbel-bawean.git`
3. Path deploy: misalnya `/home/username/timbel-bawean`

### SSH (VPS)

```bash
cd /var/www
git clone https://github.com/teenyclaw/timbel-bawean.git
cd timbel-bawean
```

Update berikutnya:

```bash
git pull origin main
```

---

## 3. Environment production

```bash
cp .env.example .env
nano .env   # atau edit via cPanel File Manager
```

Isi minimal:

```env
APP_NAME="Nasi Timbel Bawean"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=user_db
DB_PASSWORD=password_db

SESSION_DRIVER=file
QUEUE_CONNECTION=sync
CACHE_STORE=file

POS_REQUIRE_OPEN_SHIFT=true
```

Generate key:

```bash
php artisan key:generate
```

---

## 4. Install dependency & migrate

### Composer tidak ditemukan (`bash: composer: command not found`)

Di shared hosting cPanel, perintah `composer` sering **tidak ada di PATH**. Ini normal (bukan error project).

**Cek lingkungan dulu (tanpa Composer):**

```bash
cd ~/bawean
php scripts/host-env-check.php
```

**Opsi A — Install Composer di home (paling umum):**

```bash
cd ~
curl -sS https://getcomposer.org/installer | php
php ~/composer.phar install --no-dev --optimize-autoloader -d ~/bawean
```

**Opsi B — cPanel UI:**

cPanel → **Software** → **Setup PHP Composer** → pilih folder `bawean` → Install.

**Opsi C — Path alternatif (jika ada):**

```bash
/usr/local/bin/composer install --no-dev --optimize-autoloader -d ~/bawean
```

**Opsi D — Lewati Composer jika `vendor/` sudah ada:**

Jika `vendor/autoload.php` sudah ada dan `composer.lock` tidak berubah setelah `git pull`, cukup jalankan perintah `php artisan` di bawah.

---

```bash
composer install --no-dev --optimize-autoloader
# atau: php ~/composer.phar install --no-dev --optimize-autoloader -d ~/bawean

php artisan migrate --force
php artisan db:seed --force   # hanya pertama kali / data demo

php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 5. Permission folder

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache   # VPS (user web server)
```

Di cPanel, pastikan `storage/` dan `bootstrap/cache/` writable (755 atau 775).

---

## 6. Document root

Arahkan domain/subdomain ke:

```
/home/username/timbel-bawean/public
```

**Jangan** arahkan ke root project (keamanan).

### cPanel

- **Domains** → domain → Document Root → ubah ke `.../timbel-bawean/public`

---

## 7. Cron (opsional)

Jika nanti pakai queue database:

```cron
* * * * * cd /path/to/timbel-bawean && php artisan schedule:run >> /dev/null 2>&1
```

Untuk MVP dengan `QUEUE_CONNECTION=sync`, cron tidak wajib.

---

## 8. Midtrans / notifikasi (production)

```env
MIDTRANS_SERVER_KEY=...
MIDTRANS_CLIENT_KEY=...
MIDTRANS_IS_PRODUCTION=true
```

Webhook Midtrans: `https://domain-anda.com/webhooks/midtrans`

---

## 9. Cek setelah deploy

| URL | Harus |
|-----|--------|
| `/login` | Halaman login |
| `/o/nasi-timbel-bawean` | Katalog QR pelanggan |
| `/pos/kasir` | Kasir (login kasir/admin) |
| `/admin/menu-items` | Kelola menu (admin) |

Akun demo (ganti password di production):

- Admin: `admin@qrpos.test` / `password`
- Kasir: `kasir@qrpos.test` / `password`

---

## Troubleshooting

| Masalah | Solusi |
|---------|--------|
| `composer: command not found` | `php ~/composer.phar install ...` atau cPanel Setup PHP Composer |
| 500 error | Cek `storage/logs/laravel.log`, permission `storage/` |
| CSS/JS tidak load | Pastikan `APP_URL` benar & document root = `public/` |
| Foto menu tidak muncul | `php artisan storage:link` |
| Mixed content HTTPS | Set `APP_URL=https://...` |

---

## Update versi baru

```bash
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
