<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect('/admin') : redirect('/admin/login');
});

// Just redirect any dashboard access to admin
Route::get('/dashboard', function () {
    return redirect('/admin');
});
