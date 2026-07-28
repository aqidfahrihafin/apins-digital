<?php

namespace Database\Seeders;

use App\Models\Portofolios;
use App\Models\PortofoliosCategory;
use App\Models\PortofoliosImages;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'web-app' => 'Web Application',
            'mobile-app' => 'Mobile Application',
            'iot' => 'Internet of Things',
        ];

        foreach ($categories as $slug => $name) {
            PortofoliosCategory::updateOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'description' => "Kumpulan proyek {$name}."],
            );
        }

        $projects = [
            ['portal-umkm-nusantara', 'Portal UMKM Nusantara', 'Web Application', 'Koperasi Nusantara', ['Laravel', 'Tailwind CSS', 'MySQL'], true],
            ['smart-warehouse-monitoring', 'Smart Warehouse Monitoring', 'Internet of Things', 'Logistik Prima', ['ESP32', 'MQTT', 'Laravel'], true],
            ['aplikasi-layanan-lapangan', 'Aplikasi Layanan Lapangan', 'Mobile Application', 'Servis Cepat', ['Flutter', 'Firebase', 'REST API'], false],
        ];

        foreach ($projects as [$slug, $title, $categoryName, $client, $technologies, $featured]) {
            $category = PortofoliosCategory::where('name', $categoryName)->firstOrFail();
            $portfolio = Portofolios::updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $category->id,
                    'client_name' => $client,
                    'title' => $title,
                    'description' => "Solusi digital untuk meningkatkan efisiensi operasional {$client}.",
                    'challenge' => 'Proses bisnis tersebar dan sulit dipantau secara real-time.',
                    'solution' => 'Membangun sistem terintegrasi dengan dashboard yang mudah digunakan.',
                    'result' => 'Proses lebih cepat, transparan, dan mudah diukur.',
                    'technologies' => $technologies,
                    'is_featured' => $featured,
                    'is_active' => true,
                ],
            );

            $imagePath = "portofolios/seed/{$slug}.svg";
            Storage::disk('public')->put($imagePath, $this->placeholder($title));
            PortofoliosImages::updateOrCreate(
                ['portofolios_id' => $portfolio->id, 'is_primary' => true],
                ['image_url' => $imagePath, 'caption' => $title, 'order_index' => 1],
            );
        }
    }

    private function placeholder(string $title): string
    {
        $safeTitle = htmlspecialchars($title, ENT_XML1);

        return <<<SVG
        <svg xmlns="http://www.w3.org/2000/svg" width="1200" height="800" viewBox="0 0 1200 800">
          <rect width="1200" height="800" fill="#e8f5f2"/>
          <circle cx="1050" cy="110" r="260" fill="#b8eadf"/>
          <circle cx="80" cy="760" r="300" fill="#d6e2fa"/>
          <text x="80" y="380" fill="#0b172a" font-size="68" font-family="Arial, sans-serif" font-weight="700">{$safeTitle}</text>
          <text x="84" y="450" fill="#0f8f7b" font-size="30" font-family="Arial, sans-serif">Apins Digital Case Study</text>
        </svg>
        SVG;
    }
}
