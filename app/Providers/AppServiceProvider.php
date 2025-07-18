<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route; 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
public function boot()
{
    // Load API routes explicitly here
    Route::prefix('api')
         ->middleware('api')
         ->group(base_path('routes/api.php'));

    // ... any other boot logic
}
}
