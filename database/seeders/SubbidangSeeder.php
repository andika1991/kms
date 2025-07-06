<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubbidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id' => 1, 'nama' => 'Pengelolaan Opini Publik', 'bidang_id' => 4],
            ['id' => 2, 'nama' => 'Pengelolaan Informasi Publik', 'bidang_id' => 4],
            ['id' => 3, 'nama' => 'Layanan Informasi Publik', 'bidang_id' => 4],
            ['id' => 4, 'nama' => 'Tata Kelola Pemerintahan Berbasis Elektronik', 'bidang_id' => 5],
            ['id' => 5, 'nama' => 'Pemanfaatan Aplikasi', 'bidang_id' => 5],
            ['id' => 6, 'nama' => 'Hubungan Antar Lembaga TIK', 'bidang_id' => 5],
            ['id' => 7, 'nama' => 'Infrastruktur dan Teknologi', 'bidang_id' => 6],
            ['id' => 8, 'nama' => 'Pengelolaan Data dan Integrasi SI', 'bidang_id' => 6],
            ['id' => 9, 'nama' => 'Keamanan Sistem Informasi', 'bidang_id' => 6],
            ['id' => 10, 'nama' => 'Pengelolaan Media Komunikasi Publik', 'bidang_id' => 7],
            ['id' => 11, 'nama' => 'Sumber Daya Komunikasi Publik', 'bidang_id' => 7],
            ['id' => 12, 'nama' => 'Hubungan Sosial Media', 'bidang_id' => 7],
            ['id' => 13, 'nama' => 'Persandian', 'bidang_id' => 8],
            ['id' => 14, 'nama' => 'Statistik', 'bidang_id' => 8],
        ];

        DB::table('subbidang')->insert($data);
    }
}
