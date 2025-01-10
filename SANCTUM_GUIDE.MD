# Laravel Sanctum API Setup With Middleware

## Steps to Set Up Laravel with Sanctum

### 1. Install API
- Run the following command:

```bash
php artisan install:api
```

### 2. Install Sanctum
- Run the following command:

```bash
composer require laravel/sanctum
```

### 3. Publish Sanctum Configuration
- Run the following command:

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

- This will create a `config/sanctum.php` file where you can configure Sanctum settings.

### 4. Migrate the Database
- Run the migration command to update the database:

```bash
php artisan migrate
```

## Configure Sanctum Auth Guard

Update the `config/auth.php` file to set the API guard to use Sanctum:

```php
'guards' => [
    'api' => [
        'driver' => 'sanctum',
        'provider' => 'users',
    ],
],
```

## Steps to Add Middleware Group

### Update Middleware in `bootstrap/app.php`
Include Sanctum's middleware in the `api` middleware group:

### Add Custom Middleware
Add custom middleware for role checking after creating one:

```php
$middleware->alias([
    'auth' => CustomAuthenticate::class,
    'checkRole' => CheckRoleMiddleware::class
]);
```

Your Laravel application is now ready to use Sanctum for API authentication with middleware.
