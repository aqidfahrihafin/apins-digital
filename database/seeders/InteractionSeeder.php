<?php

namespace Database\Seeders;

use App\Models\ActivityLogs;
use App\Models\Contacts;
use App\Models\Faqs;
use App\Models\Newsletters;
use Illuminate\Database\Seeder;

class InteractionSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            ['Berapa lama pengerjaan sebuah proyek?', 'Durasi bergantung pada ruang lingkup. Website sederhana umumnya 2–4 minggu, sedangkan aplikasi khusus memerlukan analisis lebih lanjut.'],
            ['Apakah tersedia dukungan setelah aplikasi selesai?', 'Ya. Kami menyediakan masa pemeliharaan dan paket dukungan sesuai kebutuhan proyek.'],
            ['Bisakah sistem lama diintegrasikan?', 'Bisa. Tim kami akan melakukan audit API, database, dan alur kerja sebelum menentukan strategi integrasi.'],
            ['Bagaimana proses memulai proyek?', 'Mulai dengan konsultasi, dilanjutkan analisis kebutuhan, proposal, desain, pengembangan, pengujian, dan peluncuran.'],
        ];

        foreach ($faqs as $index => [$question, $answer]) {
            Faqs::updateOrCreate(
                ['question' => $question],
                ['answer' => $answer, 'order_index' => $index + 1, 'is_active' => true],
            );
        }

        foreach (['hello@example.com', 'bisnis@example.com', 'partner@example.com'] as $email) {
            Newsletters::subscribe($email);
        }

        Contacts::updateOrCreate(
            ['email' => 'prospek@example.com', 'message' => 'Kami membutuhkan dashboard operasional untuk tim.'],
            [
                'name' => 'Calon Klien',
                'phone' => '081234567890',
                'service' => 'Web Development',
                'is_read' => false,
            ],
        );

        ActivityLogs::updateOrCreate(
            ['action' => 'seeded', 'module' => 'demo_content'],
            [
                'description' => 'Data contoh awal berhasil disiapkan.',
                'ip_address' => '127.0.0.1',
                'old_data' => [],
                'new_data' => ['status' => 'ready'],
            ],
        );
    }
}
