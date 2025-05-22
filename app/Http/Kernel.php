<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    // Các middleware khác...

    protected $middlewareGroups = [
        'api' => [
            'throttle:60,1',  // giới hạn 60 request mỗi 1 phút
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    protected function schedule(Schedule $schedule)
{
    // Chạy mỗi 30 phút (hoặc tuỳ bạn)
    $schedule->command('stats:compute-subject-level')
            ->everyThirtyMinutes();
}


    // Các cấu hình khác...
}
