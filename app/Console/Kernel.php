<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        // ... الأوامر الأخرى
        \App\Console\Commands\CheckAdminPermissions::class,
    ];
    /**
     * Define the application's command schedule.
     */
protected function schedule(Schedule $schedule)
{
    // تحديث تتبع الشحنات كل 30 دقيقة
    $schedule->command('oto:v2-sync tracking')->everyThirtyMinutes();
    
    // مزامنة المدن أسبوعياً
    $schedule->command('oto:v2-sync cities')->weekly();
    
    // إنشاء الشحنات للطلاب الجديدة كل ساعة
    $schedule->command('oto:v2-sync shipments')->hourly();
    
    // تنظيف الكاش اليومي
    $schedule->command('cache:clear')->daily();
}

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}