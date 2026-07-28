<?php

use Illuminate\Foundation\Inspiring;
use App\Models\PageView;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('analytics:prune {--days=35 : Jumlah hari data mentah yang dipertahankan}', function () {
    $days = max(30, (int) $this->option('days'));
    $deleted = PageView::where('viewed_at', '<', now()->subDays($days)->startOfDay())->delete();

    $this->info(number_format($deleted)." data kunjungan lebih lama dari {$days} hari telah dihapus.");
})->purpose('Menghapus data analytics mentah yang melewati masa retensi');

Schedule::command('analytics:prune --days=35')
    ->dailyAt('02:30')
    ->withoutOverlapping()
    ->onOneServer();
