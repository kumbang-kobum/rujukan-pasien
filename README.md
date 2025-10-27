<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
Cara Menggunakan Untuk Linux Server :
1. Update & Install Basic Tools
sudo apt update && sudo apt upgrade -y
sudo apt install -y unzip curl git software-properties-common

2. Install PHP 8.3 + Extensions Laravel
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.3 php8.3-cli php8.3-common php8.3-mysql php8.3-xml \
php8.3-curl php8.3-mbstring php8.3-bcmath php8.3-zip php8.3-gd php8.3-intl

3. Install Database (MySQL/MariaDB)
sudo apt install -y mysql-server mysql-client
sudo mysql_secure_installation

Buat database:
CREATE DATABASE rujukan_pasien;
CREATE USER 'rujukan_user'@'localhost' IDENTIFIED BY 'passwordku';
GRANT ALL PRIVILEGES ON rujukan_pasien.* TO 'rujukan_user'@'localhost';
FLUSH PRIVILEGES;

4. Install Web Server
sudo apt install -y apache2 libapache2-mod-php8.3
sudo apt install -y nginx (opsional)

5. Install Composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
composer -V

6. Deploy Project
cd /var/www/
sudo git clone https://github.com/kumbang-kobum/rujukan-pasien.git
cd rujukan-pasien

Install dependency:
composer install --no-dev --optimize-autoloader

7. Setup Laravel
cp .env.example .env

Edit .env:
APP_NAME="Rujukan Pasien"
APP_ENV=production
APP_KEY=base64:xxxxx
APP_URL=http://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rujukan_pasien
DB_USERNAME=rujukan_user
DB_PASSWORD=passwordku

Generate key:
php artisan key:generate

Migrate & seed:
php artisan migrate --seed

8. Storage Link
php artisan storage:link

Pastikan folder writable:
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

9. Configure Virtual Host
/etc/apache2/sites-available/rujukan.conf
<VirtualHost *:80>
    ServerName rujukan.local
    DocumentRoot /var/www/rujukan-pasien/public

    <Directory /var/www/rujukan-pasien/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/rujukan-error.log
    CustomLog ${APACHE_LOG_DIR}/rujukan-access.log combined
</VirtualHost>


Aktifkan:
sudo a2ensite rujukan.conf
sudo a2enmod rewrite
sudo systemctl reload apache2

10. Tambahan (Optional)
    •    Supervisor → untuk queue worker (php artisan queue:work).
    •    Certbot → SSL gratis:
    
sudo apt install -y certbot python3-certbot-apache
sudo certbot --apache -d yourdomain.com

    •    Redis → untuk cache/session kalau mau lebih cepat.

⸻

✅ Ringkasannya

User di server Linux butuh install:
    •    PHP 8.3 + extensions
    •    MySQL/MariaDB
    •    Composer
    •    Apache/Nginx
    •    Git (opsional untuk clone repo)

Lalu langkah utama:
    1.    composer install
    2.    setting .env
    3.    php artisan migrate --seed
    4.    php artisan storage:link
    5.    atur virtual host Apache/Nginx ke /public
