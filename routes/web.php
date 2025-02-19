<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('/optimize', function () {
    if (!app()->environment('production')) {
        return 'Only available in production';
    }

    try {
        Artisan::call('optimize:clear');
        Artisan::call('optimize');
        Artisan::call('view:cache');
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('event:cache');
        Artisan::call('filament:cache-components');

        return 'Application optimized successfully!';
    } catch (Exception $e) {
        return "Error: " . $e->getMessage();
    }
})->middleware([
            'auth',
            function ($request, $next) {
                if (!auth()->user()->hasRole('super_admin')) {
                    abort(403);
                }
                return $next($request);
            }
        ]);
