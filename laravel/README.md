# Islam Textile

Public mill site in Blade and an Inertia React admin, in one Laravel app. The installed framework is Laravel 12. Do not use beta packages.

Public pages read MySQL (or SQLite locally) and hide empty fields. The admin writes the same records. Later deploys run migrations only. They do not seed, so editor changes are kept.

## PHP version

Production PHP must be **8.2 or 8.3** (or newer within Composer’s `^8.2` range). This lock file is resolved for PHP 8.2.33, so shared hosts on 8.2 or 8.3 can run `composer install`.

In the hosting panel, set the domain PHP version to match:

- cPanel: **Select PHP Version** or **MultiPHP Manager** → 8.2 or 8.3
- hPanel / Hostinger: **Advanced → PHP Configuration** → 8.2 or 8.3
- Plesk: **PHP Settings** → 8.2 or 8.3

On cPanel / CloudLinux, `composer` often uses the system PHP even when the site is set to another version. Prefer the matching binary:

```bash
# CloudLinux alt-php (panel shows PHP 8.3 (alt-php83))
/opt/alt/php83/usr/bin/php /opt/cpanel/composer/bin/composer install --no-dev --optimize-autoloader

# EasyApache PHP 8.3
/opt/cpanel/ea-php83/root/usr/bin/php /opt/cpanel/composer/bin/composer install --no-dev --optimize-autoloader

# EasyApache PHP 8.2
/opt/cpanel/ea-php82/root/usr/bin/php /opt/cpanel/composer/bin/composer install --no-dev --optimize-autoloader
```

If unsure which binary exists, run `ls /opt/alt /opt/cpanel | grep php` or `ls /opt/cpanel | grep ea-php`.

Confirm with `php -v` or the full binary path above. Deploy the project including `composer.lock`. Do not run `composer update` on the server.

## First deploy

After the host database exists and `.env` is filled (`DB_CONNECTION=mysql`, port `3306`, credentials, `APP_URL`, `APP_ENV=production`, `APP_DEBUG=false`):

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Set `ADMIN_PASSWORD` before the first seed. The seeder refuses to run in production if that value is missing or still `change-me`. The Super Admin email is `admin@islamtextile.com`.

`QUEUE_CONNECTION=database` in production. Run a queue worker so inquiry and application mail is sent. Local mail uses the `log` driver.

CV files stay on the private disk (`storage/app/cvs` and `storage/app/inquiries`). Public images stay on the `public` disk behind `storage:link`.

## Later deploys

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Do not run `db:seed` again. Seeders use `firstOrCreate` on slug, email, and media path, so a mistaken second seed inserts missing rows and does not overwrite existing ones. Do not roll back migrations on production.

Shared PHP hosting uses the same commands from the `laravel` directory, with the web root pointed at `public/`.
