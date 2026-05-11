# Rujukan Pasien

Aplikasi web manajemen rujukan pasien antar rumah sakit, berbasis Laravel.
Mendukung pengelolaan kunjungan, catatan SOAP, berkas medis, konsultasi dokter,
notifikasi email rujukan, dan kontrol akses berbasis role.

Repo GitHub:

```
https://github.com/kumbang-kobum/rujukan-pasien.git
```

## Stack

| Komponen | Versi / Detail |
|---|---|
| Laravel | 12.x |
| PHP | 8.2 atau lebih baru |
| Database | MySQL / MariaDB |
| Autentikasi | Laravel Breeze |
| Cetak PDF | barryvdh/laravel-dompdf |
| Email Transaksional | **Brevo API** (bukan SMTP bawaan Laravel) |
| Asset bundler | Vite + NPM |

## Role Pengguna

| Role | Akses |
|---|---|
| `super_admin` | Kelola master rumah sakit dan seluruh akun di semua RS |
| `admin_rs` | Kelola akun pengguna dalam satu RS, akses semua fitur klinis RS tersebut |
| `dokter` | Buat/lihat rujukan, buat SOAP, konsultasi |
| `petugas` | Buat SOAP, upload berkas medis |

## Alur Penggunaan Rujukan

1. **RS Asal (RS S)** — buat kunjungan pasien → buat catatan SOAP → buat rujukan ke RS B.
2. **RS Tujuan (RS B)** — login, buka menu Rujukan, klik **Lihat** pada rujukan masuk.
3. Di halaman detail rujukan, klik **Terima Rujukan** (atau Tolak).
4. Setelah status *Diterima*, RS B dapat membuka menu **SOAP → Tambah SOAP** dan memilih pasien yang dirujuk dari dropdown.
5. SOAP dari RS S terlihat di bagian bawah halaman detail rujukan untuk referensi klinis.

> Pasien dari rujukan hanya muncul di dropdown SOAP setelah rujukan berstatus **Diterima**.

## Struktur Direktori Penting

```
app/
  Http/Controllers/   — logika fitur (CRUD + bisnis)
  Models/             — Eloquent model dengan scope visibilitas per RS
  Notifications/      — RujukanMasukNotification (email via Brevo)
  Services/           — BrevoMailer (HTTP client ke Brevo API)
config/
  services.php        — konfigurasi Brevo (key, sender)
  soap_templates.php  — preset template SOAP & USG
database/migrations/  — skema tabel
resources/views/      — Blade template
routes/web.php        — route web dengan middleware role
storage/app/private/berkas/  — berkas medis (private, wajib login)
```

## Akun Awal Seeder

Jalankan `php artisan migrate --seed` untuk membuat akun contoh:

| Email | Role |
|---|---|
| `superadmin@example.com` | Super Admin |
| `admin.rsa@example.com` | Admin RS A |
| `admin.rsb@example.com` | Admin RS B |
| `dokter.rsa@example.com` | Dokter RS A |
| `dokter.rsb@example.com` | Dokter RS B |
| `perawat.rsa@example.com` | Petugas RS A |
| `perawat.rsb@example.com` | Petugas RS B |

Password semua akun contoh:

```
password
```

Ganti password segera setelah login, terutama di environment yang bisa diakses publik.

---

## Setup Development (macOS + XAMPP)

### 1. Prasyarat

Pastikan sudah tersedia:

- XAMPP for macOS (untuk MySQL/phpMyAdmin)
- PHP 8.2+
- Composer
- Node.js LTS + NPM
- Git

```bash
php -v && composer -V && node -v && npm -v
```

Laravel 12 membutuhkan PHP minimal 8.2. Jika PHP bawaan macOS tidak sesuai,
pakai PHP dari Homebrew atau XAMPP, lalu pastikan `php` di terminal mengarah ke
versi yang benar.

### 2. Jalankan MySQL XAMPP

Buka XAMPP Manager → start MySQL. Buat database via phpMyAdmin
(`http://localhost/phpmyadmin`) atau terminal:

```bash
/Applications/XAMPP/xamppfiles/bin/mysql -u root
```

```sql
CREATE DATABASE rujukan_pasien CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Opsional — buat user khusus:

```sql
CREATE USER 'rujukan_user'@'localhost' IDENTIFIED BY 'passwordku';
GRANT ALL PRIVILEGES ON rujukan_pasien.* TO 'rujukan_user'@'localhost';
FLUSH PRIVILEGES;
```

### 3. Clone & masuk folder

```bash
cd /Users/chandrair/Documents/GitHub
git clone https://github.com/kumbang-kobum/rujukan-pasien.git
cd rujukan-pasien
```

### 4. Install dependency

```bash
composer install
npm install
```

Jangan menyalin `vendor/` atau `node_modules/` dari Windows — install ulang
agar sesuai dengan environment macOS.

### 5. Konfigurasi `.env`

```bash
cp .env.example .env
```

Sesuaikan bagian berikut:

```env
APP_NAME="Rujukan Pasien"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rujukan_pasien
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```

> Gunakan `DB_HOST=127.0.0.1` (bukan `localhost`) agar Laravel memakai koneksi
> TCP, bukan socket MySQL bawaan macOS.

**Email (opsional di lokal)** — lihat bagian [Konfigurasi Email Brevo](#konfigurasi-email-brevo).

### 6. Generate key, migrasi, storage

```bash
php artisan key:generate
php artisan migrate --seed
php artisan storage:link --force
php artisan optimize:clear
```

### 7. Jalankan

```bash
# Terminal 1
php artisan serve --host=127.0.0.1 --port=8000

# Terminal 2
npm run dev
```

Atau jalankan semuanya sekaligus:

```bash
composer run dev
```

Buka `http://127.0.0.1:8000`.

---

## Konfigurasi Email Brevo

Aplikasi ini mengirim notifikasi email rujukan melalui **Brevo Transactional API**,
bukan SMTP Laravel standar. Tambahkan tiga variabel berikut ke `.env`:

```env
BREVO_API_KEY=xkeysib-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
BREVO_SENDER_EMAIL=noreply@domain-anda.com
BREVO_SENDER_NAME="Rujukan Pasien"
```

Cara mendapatkan API key:

1. Daftar / login di [brevo.com](https://www.brevo.com).
2. Buka **Settings → SMTP & API → API Keys**.
3. Buat API key baru dengan permission *Transactional emails*.
4. `BREVO_SENDER_EMAIL` harus berupa alamat yang sudah diverifikasi di Brevo
   (*Senders & IPs → Senders*).

Setelah mengisi `.env`, jalankan:

```bash
php artisan optimize:clear
```

Jika `QUEUE_CONNECTION=database`, email masuk antrian dan baru terkirim saat
queue worker berjalan:

```bash
php artisan queue:work --once   # proses satu job
php artisan queue:failed        # cek job gagal
```

Untuk testing lokal tanpa queue worker, ubah sementara ke:

```env
QUEUE_CONNECTION=sync
```

---

## Install di Server aaPanel

### 1. Software aaPanel

Dari App Store aaPanel, install:

- Nginx atau Apache
- PHP 8.2 / 8.3
- MySQL atau MariaDB
- Supervisor Manager (untuk queue worker)

Aktifkan extension PHP: `fileinfo`, `pdo_mysql`, `mysqli`, `mbstring`,
`openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `curl`, `zip`,
`gd`, `intl`.

### 2. Buat database

Menu Database aaPanel:

```
Database : rujukan_pasien
User     : rujukan_user
Password : isi_password_kuat
```

### 3. Clone & install

```bash
cd /www/wwwroot
git clone https://github.com/kumbang-kobum/rujukan-pasien.git
cd rujukan-pasien

composer install --no-dev --optimize-autoloader
npm ci
npm run build
```

Jika server tidak ada Node.js, jalankan `npm run build` di lokal dan
pastikan folder `public/build/` ikut tersedia di server.

### 4. `.env` production

```bash
cp .env.example .env
php artisan key:generate
```

```env
APP_NAME="Rujukan Pasien"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rujukan_pasien
DB_USERNAME=rujukan_user
DB_PASSWORD=isi_password_kuat

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database

BREVO_API_KEY=xkeysib-xxxxxxxxxxxxxxxx
BREVO_SENDER_EMAIL=noreply@domain-anda.com
BREVO_SENDER_NAME="Rujukan Pasien"
```

Jangan commit `.env` ke Git.

### 5. Migrasi, storage, permission

```bash
php artisan migrate --seed --force
php artisan storage:link --force
php artisan optimize:clear
php artisan config:cache
php artisan view:cache
```

> Jangan jalankan `php artisan route:cache` selama `routes/web.php` masih
> menggunakan closure route.

```bash
chown -R www:www storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

Jika `php` tidak mengarah ke versi aaPanel yang benar:

```bash
/www/server/php/82/bin/php artisan migrate --seed --force
```

### 6. Konfigurasi website aaPanel

1. Tambahkan domain di menu Website.
2. Document root:

```
/www/wwwroot/rujukan-pasien/public
```

3. Pilih PHP 8.2 / 8.3.
4. Aktifkan SSL.

Untuk Nginx, tambahkan rewrite:

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

Untuk Apache, file `public/.htaccess` Laravel sudah cukup — pastikan
`mod_rewrite` aktif.

### 7. Queue worker (wajib untuk email)

Email rujukan dikirim via queue. Tanpa queue worker berjalan, email tidak
akan terkirim meski konfigurasi Brevo sudah benar.

Contoh konfigurasi Supervisor aaPanel:

```bash
cd /www/wwwroot/rujukan-pasien && php artisan queue:work --tries=3 --timeout=90
```

---

## Update Server dari GitHub

```bash
cd /www/wwwroot/rujukan-pasien

# Backup database
mysqldump -u rujukan_user -p rujukan_pasien > backup-$(date +%F-%H%M).sql

# Maintenance mode
php artisan down

# Ambil update
git pull origin main

# Update dependency & asset
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# Migrasi & refresh cache
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan view:cache

# Permission
chown -R www:www storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Matikan maintenance
php artisan up
```

Jika `git pull` gagal karena ada perubahan lokal:

```bash
git stash push -u -m "backup sebelum update"
git pull origin main
```

Atau reset paksa ke GitHub (perubahan lokal hilang):

```bash
git fetch origin && git reset --hard origin/main
```

---

## Import Database Lama

```bash
mysql -u root -p rujukan_pasien < backup.sql
php artisan migrate
php artisan optimize:clear
```

XAMPP macOS:

```bash
/Applications/XAMPP/xamppfiles/bin/mysql -u root rujukan_pasien < backup.sql
```

---

## Migrasi Berkas Medis ke Private Storage

Upload baru disimpan di `storage/app/private/berkas` dan diakses via route
yang wajib login. File lama di `storage/app/public/berkas` perlu dipindahkan.

Simulasi (tanpa memindahkan):

```bash
php artisan berkas:migrate-private
```

Jalankan migrasi setelah yakin backup aman:

```bash
php artisan berkas:migrate-private --execute
```

---

## Cara Development Harian

```bash
cd /Users/chandrair/Documents/GitHub/rujukan-pasien

git pull origin main
composer install   # jika ada perubahan dependency
npm install        # jika ada perubahan package

php artisan migrate          # jika ada migration baru
php artisan optimize:clear   # setelah ubah config/route

php artisan serve --host=127.0.0.1 --port=8000
npm run dev
```

Sebelum push:

```bash
php artisan test
npm run build
git status
```

---

## Catatan Git

File yang tidak perlu masuk Git (sudah ada di `.gitignore`):

```
.env
vendor/
node_modules/
public/build/
public/storage
storage/logs/*.log
.DS_Store
```

---

## Troubleshooting

### Error 500 di halaman Edit Kunjungan

Pastikan sudah menjalankan `php artisan optimize:clear` setelah update.
Jika masih terjadi, cek log:

```bash
tail -n 100 storage/logs/laravel.log
```

### `could not find driver`

PHP CLI belum memiliki extension `pdo_mysql`.

```bash
php -m | grep pdo_mysql
```

Aktifkan extension dari menu PHP aaPanel atau konfigurasi `php.ini`.

### `SQLSTATE[HY000] [2002] Connection refused`

MySQL belum berjalan atau konfigurasi salah. Untuk XAMPP macOS:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
```

Lalu:

```bash
php artisan optimize:clear
```

### `Vite manifest not found`

```bash
npm install && npm run build
```

### Email rujukan tidak terkirim

1. Pastikan `BREVO_API_KEY`, `BREVO_SENDER_EMAIL`, dan `BREVO_SENDER_NAME`
   sudah diisi di `.env`.
2. Pastikan `BREVO_SENDER_EMAIL` sudah diverifikasi di dashboard Brevo.
3. Pastikan queue worker berjalan (`php artisan queue:work`).
4. Cek antrian gagal: `php artisan queue:failed`.
5. Cek log: `tail -n 100 storage/logs/laravel.log`.

Untuk testing tanpa queue:

```env
QUEUE_CONNECTION=sync
```

### Pasien tidak muncul di dropdown SOAP (RS tujuan)

Pastikan rujukan sudah berstatus **Diterima** (tombol Terima ada di halaman
detail rujukan). Pasien dari RS asal hanya muncul setelah rujukan diterima.

### Upload atau berkas medis tidak tampil

```bash
php artisan storage:link --force
chmod -R 775 storage bootstrap/cache
```

aaPanel:

```bash
chown -R www:www storage bootstrap/cache
```

Jika `public/storage` adalah folder (bukan symlink) dari Windows:

```bash
ls -la public/storage
mv public/storage public/storage.backup
php artisan storage:link
```
