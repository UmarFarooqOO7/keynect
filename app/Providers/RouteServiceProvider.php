<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    // ...existing code...
    
    public const HOME = '/admin';  // Change this from '/dashboard' to '/admin'
    
    // ...existing code...
}
