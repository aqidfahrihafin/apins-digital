<?php

namespace Database\Seeders;

use App\Models\SiteConfigs;
use Illuminate\Database\Seeder;

class SiteConfigSeeder extends Seeder
{
    public function run(): void
    {
        $configs = [
            'site_name' => ['APINS DIGITAL', 'general'],
            'company_name' => ['CV Apins Digital', 'general'],
            'footer_description' => ['Precision Engineered Digital Solutions.', 'general'],
            'email' => ['info@apinsdigital.com', 'contact'],
            'phone' => ['+62 812-3456-7890', 'contact'],
            'address' => ['Jakarta, Indonesia', 'contact'],
            'about_title' => ['Membangun Masa Depan Digital Melalui Presisi', 'about'],
            'about_description' => ['Kami menjembatani ide bisnis dan eksekusi teknis yang presisi.', 'about'],
            'vision' => ['Menjadi mitra transformasi digital dengan standar engineering kelas dunia.', 'about'],
            'mission' => ['Menghasilkan produk digital yang adaptif, aman, dan skalabel.', 'about'],
        ];

        foreach ($configs as $key => [$value, $group]) {
            SiteConfigs::firstOrCreate(
                ['config_key' => $key],
                ['config_value' => $value, 'group_name' => $group],
            );
        }
    }
}
