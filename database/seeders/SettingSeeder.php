<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('settings')->insertOrIgnore([
            'id' => 1,
            'headline' => 'Solusi Digital untuk Masa Depan',
            'subheadline' => 'Presisi Arsitektur Perangkat Lunak',
            'description' => 'Kami menghadirkan presisi arsitektur perangkat lunak untuk kebutuhan bisnis modern Anda. Layanan pembuatan aplikasi web, mobile, IoT, dan konsultasi IT.',
            'hero_title' => 'Digital Solutions',
            'services' => json_encode([
                ['name' => 'Aplikasi Web', 'description' => 'Pengembangan aplikasi web modern', 'order' => 1],
                ['name' => 'Aplikasi Mobile', 'description' => 'Aplikasi mobile native dan cross-platform', 'order' => 2],
                ['name' => 'IoT Solutions', 'description' => 'Integrasi perangkat pintar dan IoT', 'order' => 3],
                ['name' => 'Konsultasi IT', 'description' => 'Konsultasi arsitektur software', 'order' => 4],
            ]),
            'portfolio_button_text' => 'Lihat Portofolio',
            'portfolio_url' => '#',
            'consultation_button_text' => 'Konsultasi Gratis',
            'consultation_url' => '#',
            'image' => 'images/hero-bg.jpg', // path default gambar
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
