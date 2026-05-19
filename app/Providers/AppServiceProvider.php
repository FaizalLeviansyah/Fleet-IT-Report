<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\IncidentReport;
use App\Observers\IncidentReportObserver;

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
    public function boot(): void
    {
        // 👇 Mendaftarkan Observer agar sistem memantau tabel IncidentReport 24/7
        IncidentReport::observe(IncidentReportObserver::class);
    }
}