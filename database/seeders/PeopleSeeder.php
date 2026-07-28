<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\TeamMembers;
use App\Models\Testimonials;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PeopleSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            ['Raka Pratama', 'raka@apinsdigital.com', 'Software Architect'],
            ['Nadia Putri', 'nadia@apinsdigital.com', 'Product Designer'],
            ['Dimas Ardi', 'dimas@apinsdigital.com', 'IoT Engineer'],
        ];

        foreach ($members as $index => [$name, $email, $position]) {
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('password123'),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ],
            );
            TeamMembers::updateOrCreate(
                ['user_id' => $user->id],
                ['position' => $position, 'order_index' => $index + 1, 'is_active' => true],
            );
            Profile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'position' => $position,
                    'bio' => "{$name} adalah bagian dari tim Apins Digital.",
                    'expertise' => [$position, 'Digital Product Development'],
                    'joined_at' => now()->subYears(2)->addMonths($index),
                ],
            );
        }

        $testimonials = [
            ['Andi Wijaya', 'Direktur', 'Koperasi Nusantara', 'Tim Apins komunikatif dan mampu menerjemahkan kebutuhan bisnis menjadi produk yang mudah digunakan.', 5],
            ['Sari Rahma', 'Operations Manager', 'Logistik Prima', 'Dashboard monitoring membantu tim kami mengambil keputusan jauh lebih cepat.', 5],
            ['Budi Santoso', 'Founder', 'Servis Cepat', 'Proses pengerjaan transparan dan hasil aplikasinya stabil.', 4],
        ];

        foreach ($testimonials as [$name, $position, $company, $content, $rating]) {
            Testimonials::updateOrCreate(
                ['name' => $name, 'company' => $company],
                compact('position', 'content', 'rating') + ['is_active' => true],
            );
        }
    }
}
