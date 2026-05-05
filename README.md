# Rujukan Pasien

Aplikasi web rujukan pasien berbasis Laravel untuk pengelolaan pasien,
kunjungan, SOAP, rujukan, berkas medis, konsultasi antar dokter, rumah sakit,
dan user berdasarkan role.

Repo GitHub:

```bash
https://github.com/kumbang-kobum/rujukan-pasien.git
```

## Stack Project

- Laravel 12
- PHP 8.2 atau lebih baru
- MySQL/MariaDB
- Composer
- Node.js dan NPM untuk asset Vite/Tailwind
- Laravel Breeze untuk autentikasi
- DomPDF untuk cetak PDF

## Struktur Penting

- `app/Http/Controllers`: logic fitur aplikasi
- `app/Models`: model Eloquent
- `database/migrations`: struktur database
- `database/seeders/UserSeeder.php`: user awal untuk development/testing
- `resources/views`: Blade template
- `routes/web.php`: route web aplikasi
- `public`: document root web server
- `storage/app/public`: file upload yang dipublikasikan lewat `php artisan storage:link`

## Akun Awal Seeder

Jika menjalankan `php artisan migrate --seed`, aplikasi membuat akun contoh:

- `admin.rsa@example.com`
- `admin.rsb@example.com`
- `dokter.rsa@example.com`
- `dokter.rsb@example.com`
- `perawat.rsa@example.com`
- `perawat.rsb@example.com`

Password awal semua akun contoh adalah:

```text
password
```

Ganti password setelah login, terutama jika data ini pernah dipakai di server
online.

## Setup Development di macOS dengan MySQL XAMPP

Project ini sebelumnya dikembangkan di Windows. Di macOS, lebih aman dependency
PHP dan Node di-install ulang dari awal, sedangkan database bisa tetap memakai
MySQL dari XAMPP.

### 1. Install kebutuhan lokal

Pastikan sudah ada:

- XAMPP for macOS, untuk MySQL/phpMyAdmin
- PHP 8.2 atau lebih baru
- Composer
- Node.js LTS dan NPM
- Git

Cek versi:

```bash
php -v
composer -V
node -v
npm -v
git --version
```

Laravel 12 membutuhkan PHP minimal 8.2. Jika PHP bawaan macOS tidak sesuai,
pakai PHP dari Homebrew atau PHP dari XAMPP, lalu pastikan command `php` di
terminal menunjuk ke versi yang benar.

### 2. Jalankan MySQL XAMPP

Buka XAMPP Manager, lalu start service MySQL.

Database bisa dibuat lewat phpMyAdmin:

```text
http://localhost/phpmyadmin
```

Atau lewat terminal:

```bash
/Applications/XAMPP/xamppfiles/bin/mysql -u root
```

Lalu jalankan SQL berikut:

```sql
CREATE DATABASE rujukan_pasien CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Untuk development lokal XAMPP, biasanya user MySQL adalah `root` dengan password
kosong. Jika ingin lebih rapi, buat user khusus:

```sql
CREATE USER 'rujukan_user'@'localhost' IDENTIFIED BY 'passwordku';
GRANT ALL PRIVILEGES ON rujukan_pasien.* TO 'rujukan_user'@'localhost';
FLUSH PRIVILEGES;
```

### 3. Clone atau masuk ke folder project

Jika belum clone:

```bash
cd /Users/chandrair/Documents/GitHub
git clone https://github.com/kumbang-kobum/rujukan-pasien.git
cd rujukan-pasien
```

Jika folder sudah ada:

```bash
cd /Users/chandrair/Documents/GitHub/rujukan-pasien
```

### 4. Install dependency

```bash
composer install
npm install
```

Jangan memakai `vendor` dan `node_modules` hasil copy dari Windows. Biarkan
Composer dan NPM memasang ulang dependency sesuai environment macOS.

### 5. Buat dan isi `.env`

Jika `.env` belum ada:

```bash
cp .env.example .env
```

Ubah bagian utama `.env` menjadi seperti ini:

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

Catatan penting untuk XAMPP macOS:

- Pakai `DB_HOST=127.0.0.1`, bukan `localhost`, supaya Laravel memakai koneksi
  TCP dan tidak mencari socket MySQL bawaan macOS.
- Jika MySQL XAMPP memakai port lain, sesuaikan `DB_PORT`.
- Jika memakai user khusus, isi `DB_USERNAME=rujukan_user` dan
  `DB_PASSWORD=passwordku`.

### 6. Generate key, migrasi database, dan storage link

```bash
php artisan key:generate
php artisan migrate --seed
php artisan storage:link --force
php artisan optimize:clear
```

Jika database sudah berisi data lama dari Windows, backup dulu sebelum migrasi.
Untuk database kosong, perintah di atas aman dijalankan langsung.

### 7. Jalankan aplikasi

Terminal pertama:

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Terminal kedua:

```bash
npm run dev
```

Buka:

```text
http://127.0.0.1:8000
```

Alternatif, project ini juga punya script Composer untuk menjalankan server,
queue, log, dan Vite sekaligus:

```bash
composer run dev
```

Gunakan mode ini setelah database dan migrasi sudah siap.

## Cara Development Harian di macOS

Masuk folder project:

```bash
cd /Users/chandrair/Documents/GitHub/rujukan-pasien
```

Ambil update terbaru dari GitHub:

```bash
git pull origin main
```

Jika ada perubahan dependency:

```bash
composer install
npm install
```

Jika ada migration baru:

```bash
php artisan migrate
```

Jalankan ulang cache saat config atau route berubah:

```bash
php artisan optimize:clear
```

Jalankan aplikasi:

```bash
php artisan serve --host=127.0.0.1 --port=8000
npm run dev
```

Sebelum push perubahan:

```bash
php artisan test
npm run build
git status
```

## Install di Server aaPanel

Panduan ini mengasumsikan server memakai aaPanel dengan Nginx atau Apache,
PHP 8.2/8.3, dan MySQL/MariaDB.

### 1. Install software di aaPanel

Dari App Store aaPanel, install:

- Nginx atau Apache
- PHP 8.2 atau PHP 8.3
- MySQL atau MariaDB
- phpMyAdmin, opsional
- Redis, opsional
- Supervisor Manager, opsional untuk queue worker

Aktifkan extension PHP yang umum dipakai Laravel:

- `fileinfo`
- `pdo_mysql`
- `mysqli`
- `mbstring`
- `openssl`
- `tokenizer`
- `xml`
- `ctype`
- `json`
- `bcmath`
- `curl`
- `zip`
- `gd`
- `intl`

Pastikan Composer tersedia di server. Jika command `php` aaPanel berbeda
versi, pakai path PHP aaPanel, contoh:

```bash
/www/server/php/82/bin/php -v
```

### 2. Buat database

Di menu Database aaPanel, buat database:

```text
Database : rujukan_pasien
User     : rujukan_user
Password : isi_password_kuat
```

### 3. Clone project dari GitHub

Masuk terminal server:

```bash
cd /www/wwwroot
git clone https://github.com/kumbang-kobum/rujukan-pasien.git
cd rujukan-pasien
```

Install dependency production:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
```

Jika server tidak memakai Node.js, jalankan `npm run build` di lokal, lalu
pastikan hasil build `public/build` ikut tersedia di server lewat proses deploy
yang dipakai.

### 4. Setup `.env` production

```bash
cp .env.example .env
php artisan key:generate
```

Isi `.env` production:

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
```

Jangan commit file `.env` ke GitHub.

### 5. Migrasi, storage, permission, dan cache

```bash
php artisan migrate --seed --force
php artisan storage:link --force
php artisan optimize:clear
php artisan config:cache
php artisan view:cache
```

Catatan: jangan jalankan `php artisan route:cache` dulu selama `routes/web.php`
masih memakai closure route untuk redirect.

Set permission untuk user web aaPanel:

```bash
chown -R www:www storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

Jika command `php` mengarah ke versi yang salah, gunakan PHP aaPanel:

```bash
/www/server/php/82/bin/php artisan migrate --seed --force
```

### 6. Setting website aaPanel

Di menu Website aaPanel:

1. Tambahkan domain.
2. Arahkan document root ke:

```text
/www/wwwroot/rujukan-pasien/public
```

3. Pilih PHP 8.2 atau 8.3.
4. Aktifkan SSL jika domain sudah mengarah ke server.

Untuk Nginx, tambahkan rewrite Laravel:

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

Untuk Apache, file `public/.htaccess` Laravel biasanya sudah cukup. Pastikan
rewrite Apache aktif.

### 7. Queue worker, jika dipakai

Project memakai `QUEUE_CONNECTION=database`. Jika fitur notifikasi atau proses
background perlu berjalan otomatis, jalankan queue worker.

Contoh command Supervisor:

```bash
cd /www/wwwroot/rujukan-pasien && php artisan queue:work --tries=3 --timeout=90
```

Jika memakai PHP aaPanel:

```bash
cd /www/wwwroot/rujukan-pasien && /www/server/php/82/bin/php artisan queue:work --tries=3 --timeout=90
```

## Update Server aaPanel dari Repo GitHub

Langkah update aman:

```bash
cd /www/wwwroot/rujukan-pasien
```

Backup database dulu:

```bash
mysqldump -u rujukan_user -p rujukan_pasien > backup-rujukan-pasien-$(date +%F-%H%M).sql
```

Aktifkan maintenance mode:

```bash
php artisan down
```

Cek perubahan lokal di server:

```bash
git status
```

Ambil update terbaru:

```bash
git pull origin main
```

Update dependency dan asset:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
```

Jalankan migrasi dan refresh cache:

```bash
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan view:cache
```

Catatan: jangan jalankan `php artisan route:cache` dulu selama `routes/web.php`
masih memakai closure route untuk redirect.

Pastikan permission tetap benar:

```bash
chown -R www:www storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

Matikan maintenance mode:

```bash
php artisan up
```

Jika `git pull` gagal karena ada perubahan lokal di server, pilih salah satu:

```bash
git stash push -u -m "backup perubahan lokal sebelum update"
git pull origin main
```

Atau, jika server harus mengikuti GitHub persis dan semua perubahan lokal boleh
dibuang:

```bash
git fetch origin
git reset --hard origin/main
```

Gunakan `git reset --hard` hanya setelah yakin tidak ada perubahan lokal penting
di server.

## Import Database Lama

Jika membawa database dari Windows atau backup server:

```bash
mysql -u root -p rujukan_pasien < backup.sql
```

Untuk XAMPP macOS:

```bash
/Applications/XAMPP/xamppfiles/bin/mysql -u root rujukan_pasien < backup.sql
```

Setelah import:

```bash
php artisan migrate
php artisan optimize:clear
```

## Troubleshooting

### `could not find driver`

PHP CLI belum punya extension `pdo_mysql`.

Cek:

```bash
php -m | grep pdo_mysql
```

Aktifkan extension MySQL pada PHP yang dipakai terminal. Di aaPanel, aktifkan
`pdo_mysql` dari menu PHP extension.

### `SQLSTATE[HY000] [2002] Connection refused`

MySQL belum berjalan, host/port salah, atau Laravel mencoba socket yang salah.

Untuk XAMPP macOS, gunakan:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
```

Lalu clear cache:

```bash
php artisan optimize:clear
```

### `Vite manifest not found`

Asset production belum dibuild.

```bash
npm install
npm run build
```

### Halaman 500 setelah update

Cek log:

```bash
tail -n 100 storage/logs/laravel.log
```

Clear dan rebuild cache:

```bash
php artisan optimize:clear
php artisan config:cache
php artisan view:cache
```

### Upload atau storage tidak tampil

Pastikan storage link dan permission:

```bash
php artisan storage:link --force
chmod -R 775 storage bootstrap/cache
```

Di aaPanel:

```bash
chown -R www:www storage bootstrap/cache
```

Jika muncul `The [public/storage] link already exists`, cek dulu apakah
`public/storage` adalah folder hasil copy dari Windows, bukan symlink:

```bash
ls -la public/storage
```

Jika isinya sudah ada juga di `storage/app/public`, backup folder lama lalu buat
ulang symlink:

```bash
mv public/storage public/storage.backup
php artisan storage:link
```

## Catatan Git

File dan folder berikut tidak perlu masuk Git:

- `.env`
- `vendor`
- `node_modules`
- `public/build`
- `public/storage`
- `storage/logs/*.log`
- `.DS_Store`

Jika pindah dari Windows ke macOS, lebih baik clone repo bersih dari GitHub lalu
jalankan `composer install` dan `npm install`, bukan menyalin seluruh folder
hasil development Windows.
