<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Clear everything first
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    \Illuminate\Support\Facades\Artisan::call('event:clear');
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');

    // Optimize application
    \Illuminate\Support\Facades\Artisan::call('optimize');
    \Illuminate\Support\Facades\Artisan::call('view:cache');
    \Illuminate\Support\Facades\Artisan::call('config:cache');
    \Illuminate\Support\Facades\Artisan::call('route:cache');
    \Illuminate\Support\Facades\Artisan::call('event:cache');

    // Filament specific optimizations
    \Illuminate\Support\Facades\Artisan::call('filament:cache-components');
    \Illuminate\Support\Facades\Artisan::call('icons:cache');
    
    // Additional performance optimizations
    \Illuminate\Support\Facades\Artisan::call('auth:clear-resets');
    \Illuminate\Support\Facades\Artisan::call('cache:prune-stale-tags');

    echo "Application optimized successfully!<br>";
    echo "All caches have been rebuilt.<br>";
    echo "You can now delete this file.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
