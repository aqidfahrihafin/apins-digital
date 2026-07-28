<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceDetails;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['Web Development', 'web-development', 'Website dan aplikasi web modern untuk bisnis.', 'Kami membangun website cepat, aman, responsif, dan mudah dikembangkan.', 'language', ['Company profile dan landing page', 'E-commerce dan payment gateway', 'Dashboard dan aplikasi internal']],
            ['Mobile Development', 'mobile-development', 'Aplikasi Android dan iOS yang intuitif.', 'Pengembangan aplikasi mobile native maupun lintas platform.', 'smartphone', ['Aplikasi Android dan iOS', 'Integrasi API dan notifikasi', 'Publikasi ke Play Store dan App Store']],
            ['IoT Solutions', 'iot-solutions', 'Integrasi perangkat dan monitoring real-time.', 'Solusi IoT dari perangkat, konektivitas, sampai dashboard analitik.', 'sensors', ['Integrasi sensor dan perangkat', 'Dashboard monitoring real-time', 'Otomasi dan notifikasi']],
            ['IT Consulting', 'it-consulting', 'Pendampingan strategi dan arsitektur teknologi.', 'Kami membantu memilih solusi teknologi yang tepat dan terukur.', 'architecture', ['Audit sistem dan infrastruktur', 'Perencanaan transformasi digital', 'Cloud dan keamanan aplikasi']],
        ];

        foreach ($services as $index => [$name, $slug, $short, $long, $icon, $features]) {
            $service = Service::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'short_desc' => $short,
                    'long_desc' => $long,
                    'icon' => $icon,
                    'is_active' => true,
                    'order_index' => $index + 1,
                ],
            );

            foreach ($features as $order => $feature) {
                ServiceDetails::updateOrCreate(
                    ['service_id' => $service->id, 'feature' => $feature],
                    ['icon' => 'check_circle', 'order_index' => $order + 1],
                );
            }
        }
    }
}
