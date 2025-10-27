# Copilot Instructions for Rujukan Pasien

## Overview
This project is a Laravel-based web application for managing patient referrals. It leverages Laravel's MVC architecture, Eloquent ORM, and other built-in features to streamline development. The application is structured to handle patient data, medical records, and hospital visits efficiently.

## Key Components

### Models
- Located in `app/Models/`
- Examples: `Pasien.php`, `Kunjungan.php`, `Rujukan.php`
- Represent database tables and define relationships (e.g., `hasMany`, `belongsTo`).

### Controllers
- Located in `app/Http/Controllers/`
- Handle HTTP requests and responses.
- Example: `RujukanController` processes referral-related logic.

### Views
- Located in `resources/views/`
- Blade templates for rendering HTML.

### Routes
- Defined in `routes/web.php` for web routes.
- Use `Route::get`, `Route::post`, etc., to define endpoints.

### Migrations
- Located in `database/migrations/`
- Define schema changes for the database.
- Example: `2025_09_15_145824_create_rumah_sakit_table.php` creates the `rumah_sakit` table.

### Seeders
- Located in `database/seeders/`
- Populate the database with initial data.
- Example: `RumahSakitSeeder` seeds hospital data.

## Developer Workflows

### Setting Up the Project
1. Clone the repository:
   ```bash
   git clone https://github.com/kumbang-kobum/rujukan-pasien.git
   ```
2. Install dependencies:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
3. Configure environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Migrate and seed the database:
   ```bash
   php artisan migrate --seed
   ```
5. Link storage:
   ```bash
   php artisan storage:link
   ```

### Running the Application
- Start the development server:
  ```bash
  php artisan serve
  ```

### Testing
- Run PHPUnit tests:
  ```bash
  ./vendor/bin/phpunit
  ```

## Project-Specific Conventions

### Database
- Use migrations for schema changes.
- Seeders are used for populating initial data.

### Code Style
- Follow PSR-12 coding standards.
- Use Laravel's built-in validation and request classes for input handling.

### External Dependencies
- Composer for PHP packages.
- Tailwind CSS for styling (configured in `tailwind.config.js`).
- Vite for asset bundling (configured in `vite.config.js`).

### Deployment
- Ensure `storage` and `bootstrap/cache` directories are writable:
  ```bash
  sudo chown -R www-data:www-data storage bootstrap/cache
  sudo chmod -R 775 storage bootstrap/cache
  ```
- Configure virtual hosts for Apache (example in README).

## Examples

### Defining a Route
```php
Route::get('/pasien', [PasienController::class, 'index']);
```

### Creating a Model
```php
class Pasien extends Model {
    protected $fillable = ['name', 'dob', 'address'];
}
```

### Writing a Seeder
```php
DB::table('pasien')->insert([
    'name' => 'John Doe',
    'dob' => '1990-01-01',
    'address' => '123 Main St',
]);
```

---

For more details, refer to the Laravel documentation: https://laravel.com/docs.