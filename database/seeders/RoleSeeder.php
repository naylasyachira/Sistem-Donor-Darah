<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator PMI',
                'description' => 'Memiliki akses penuh ke seluruh sistem.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'petugas',
                'display_name' => 'Petugas PMI',
                'description' => 'Mengelola pendonor, donor darah, dan stok darah.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'rumah_sakit',
                'display_name' => 'Rumah Sakit',
                'description' => 'Mengajukan permintaan darah dan melihat riwayat permintaan.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        \Illuminate\Support\Facades\DB::table('roles')->insert($roles);
    }
}
