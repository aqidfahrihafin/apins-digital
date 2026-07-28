<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPosts;
use App\Models\Comments;
use App\Models\Contacts;
use App\Models\Portofolios;
use App\Models\PageView;
use App\Models\Service;
use App\Models\User;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $since30Days = now()->subDays(29)->startOfDay();
        $since7Days = now()->subDays(6)->startOfDay();
        $views30Days = PageView::where('viewed_at', '>=', $since30Days);
        $totalViews30Days = (clone $views30Days)->count();
        $uniqueVisitors30Days = (clone $views30Days)->distinct('visitor_hash')->count('visitor_hash');
        $contacts30Days = Contacts::where('created_at', '>=', $since30Days)->count();

        $dailyRaw = PageView::query()
            ->where('viewed_at', '>=', now()->subDays(13)->startOfDay())
            ->selectRaw('DATE(viewed_at) as day, COUNT(*) as total, COUNT(DISTINCT visitor_hash) as visitors')
            ->groupBy('day')
            ->pluck('total', 'day');
        $dailyViews = collect(CarbonPeriod::create(now()->subDays(13)->startOfDay(), now()->startOfDay()))
            ->map(fn ($date) => [
                'date' => $date->translatedFormat('d M'),
                'value' => (int) ($dailyRaw[$date->format('Y-m-d')] ?? 0),
            ]);

        $popularPages = PageView::query()
            ->where('viewed_at', '>=', $since30Days)
            ->selectRaw('path, MAX(page_title) as title, COUNT(*) as total, COUNT(DISTINCT visitor_hash) as visitors')
            ->groupBy('path')
            ->orderByDesc('total')
            ->limit(7)
            ->get();
        $deviceStats = PageView::query()
            ->where('viewed_at', '>=', $since30Days)
            ->selectRaw('device, COUNT(*) as total')
            ->groupBy('device')
            ->pluck('total', 'device');
        $referrerStats = PageView::query()
            ->where('viewed_at', '>=', $since30Days)
            ->whereNotNull('referrer_host')
            ->selectRaw('referrer_host, COUNT(*) as total')
            ->groupBy('referrer_host')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $stats = [
            ['label' => 'Portofolio aktif', 'value' => Portofolios::active()->count(), 'icon' => 'work', 'tone' => 'mint'],
            ['label' => 'Artikel terbit', 'value' => BlogPosts::published()->count(), 'icon' => 'article', 'tone' => 'blue'],
            ['label' => 'Pesan masuk', 'value' => Contacts::count(), 'icon' => 'inbox', 'tone' => 'amber'],
            ['label' => 'Komentar tampil', 'value' => Comments::where('is_approved', true)->count(), 'icon' => 'forum', 'tone' => 'violet'],
        ];

        return view('admin.dashboard', [
            'stats' => $stats,
            'latestContacts' => Contacts::latest()->limit(5)->get(),
            'latestPosts' => BlogPosts::with('author')->latest()->limit(5)->get(),
            'pendingComments' => Comments::where('is_approved', false)->latest()->limit(5)->get(),
            'activeUsers' => User::where('is_active', true)->count(),
            'activeServices' => Service::where('is_active', true)->count(),
            'analytics' => [
                'views30Days' => $totalViews30Days,
                'visitors30Days' => $uniqueVisitors30Days,
                'views7Days' => PageView::where('viewed_at', '>=', $since7Days)->count(),
                'avgResponseMs' => (int) round((clone $views30Days)->avg('response_ms') ?? 0),
                'conversionRate' => $uniqueVisitors30Days > 0 ? round(($contacts30Days / $uniqueVisitors30Days) * 100, 1) : 0,
                'dailyViews' => $dailyViews,
                'popularPages' => $popularPages,
                'deviceStats' => $deviceStats,
                'referrerStats' => $referrerStats,
            ],
        ]);
    }
}
