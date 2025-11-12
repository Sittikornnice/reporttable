<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // เพิ่มหน่วยความจำเป็น 512MB เพื่อป้องกัน Error Memory Limit
        ini_set('memory_limit', '53248M');
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}
