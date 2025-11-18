<?php

use Dotenv\Dotenv;

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Load Environment File Dynamically
|--------------------------------------------------------------------------
|
| 1. CLI commands (artisan) تستخدم .env.local
| 2. Web server: إذا الدومين يحتوي localhost أو 127.0.0.1 استخدم .env.local
| 3. باقي الحالات استخدم .env.production
|
*/

$envFile = '.env.production'; // الافتراضي

if (app()->runningInConsole()) {
    $envFile = '.env.local';
} else {
    $host = $_SERVER['HTTP_HOST'] ?? '';
    if (str_contains($host, 'localhost') || str_contains($host, '127.0.0.1')) {
        $envFile = '.env.local';
    }
}

// تحميل ملف .env المناسب
$dotenv = Dotenv::createImmutable($app->basePath(), $envFile);
$dotenv->safeLoad(); // safeLoad أفضل لو الملف غير موجود

// Optional: debug values to confirm
// dd(env('DB_CONNECTION'), env('DB_USERNAME'), env('DB_DATABASE'));

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

return $app;
