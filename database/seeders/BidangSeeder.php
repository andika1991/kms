<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bidang = [
            ['id' => 1, 'nama' => 'Kasubag Umum dan Kepegawaian'],
            ['id' => 2, 'nama' => 'Kasubag Keuangan dan Aset'],
            ['id' => 3, 'nama' => 'Kasubag Perencanaan Ahli Muda'],
            ['id' => 4, 'nama' => 'Pengelolaan Layanan Informasi Publik'],
            ['id' => 5, 'nama' => 'Tata Kelola Pemerintahan Berbasis Elektronik'],
            ['id' => 6, 'nama' => 'Teknologi Informasi dan Komunikasi'],
            ['id' => 7, 'nama' => 'Pengelolaan Komunikasi Publik'],
            ['id' => 8, 'nama' => 'Persandian dan Statistik'],
        ];

        DB::table('bidang')->insert($bidang);
    }
}
