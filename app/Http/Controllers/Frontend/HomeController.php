<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogPosts;
use App\Models\Faqs;
use App\Models\Portofolios;
use App\Models\Service;
use App\Models\Settings;
use App\Models\SiteConfigs;
use App\Models\TeamMembers;
use App\Models\Testimonials;

class HomeController extends Controller
{
    public function index()
    {
        $setting = Settings::where('is_active', true)->first() ?? new Settings([
            'hero_title' => 'Digital Solutions',
            'headline' => 'Solusi Digital untuk Masa Depan',
            'subheadline' => 'Presisi Arsitektur Perangkat Lunak',
            'description' => 'Kami menghadirkan solusi perangkat lunak untuk kebutuhan bisnis modern Anda.',
            'portfolio_button_text' => 'Lihat Portofolio',
            'portfolio_url' => '#portfolio',
            'consultation_button_text' => 'Konsultasi Gratis',
            'consultation_url' => '#contact',
        ]);
        $services = Service::with('details')
            ->where('is_active', true)
            ->orderBy('order_index')
            ->get();
        $portfolios = Portofolios::with(['category', 'images'])
            ->where('is_active', true)
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();
        $testimonials = Testimonials::where('is_active', true)->latest()->get();
        $teams = TeamMembers::with('user')
            ->where('is_active', true)
            ->whereHas('user', fn ($query) => $query->where('is_active', true))
            ->orderBy('order_index')
            ->get();
        $faqs = Faqs::where('is_active', true)
            ->orderBy('order_index')
            ->get();
        $blogs = BlogPosts::published()
            ->with('author')
            ->latest('published_at')
            ->limit(3)
            ->get();

        $totalProjects = Portofolios::where('is_active', true)->count();
        $totalClients = Portofolios::where('is_active', true)
            ->whereNotNull('client_name')
            ->distinct()
            ->count('client_name');
        $totalTeam = $teams->count();
        $siteConfig = SiteConfigs::query()
            ->pluck('config_value', 'config_key')
            ->all();

        return view('home.index', compact(
            'setting',
            'services',
            'portfolios',
            'teams',
            'testimonials',
            'faqs',
            'blogs',
            'totalProjects',
            'totalClients',
            'totalTeam',
            'siteConfig',
        ));
    }
}
