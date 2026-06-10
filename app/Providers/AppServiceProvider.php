<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// 👇 Tambahkan 4 baris Use ini:
use App\Models\Ticket;
use App\Models\TicketFollowup;
use App\Observers\TicketObserver;
use App\Observers\TicketFollowupObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 👇 Daftarkan Observer di sini
        Ticket::observe(TicketObserver::class);
        TicketFollowup::observe(TicketFollowupObserver::class);
    }
}