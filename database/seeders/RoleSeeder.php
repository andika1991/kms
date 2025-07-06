<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['id' => 1, 'nama_role' => 'Kepala Dinas', 'role_group' => 'Kadis', 'parent_id' => null, 'bidang_id' => null, 'subbidang_id' => null],
            ['id' => 2, 'nama_role' => 'Sekretaris', 'role_group' => 'sekretaris', 'parent_id' => 1, 'bidang_id' => null, 'subbidang_id' => null],
            ['id' => 3, 'nama_role' => 'Kasubag Umum dan Kepegawaian', 'role_group' => 'kepalabagian', 'parent_id' => 2, 'bidang_id' => 1, 'subbidang_id' => null],
            ['id' => 4, 'nama_role' => 'Pegawai Umum dan Kepegawaian', 'role_group' => 'pegawai', 'parent_id' => 3, 'bidang_id' => 1, 'subbidang_id' => null],
            ['id' => 5, 'nama_role' => 'Kasubag Keuangan dan Aset', 'role_group' => 'kepalabagian', 'parent_id' => 2, 'bidang_id' => 2, 'subbidang_id' => null],
            ['id' => 6, 'nama_role' => 'Pegawai Keuangan dan Aset', 'role_group' => 'pegawai', 'parent_id' => 5, 'bidang_id' => 2, 'subbidang_id' => null],
            ['id' => 7, 'nama_role' => 'Kasubag Perencanaan Ahli Muda', 'role_group' => 'kepalabagian', 'parent_id' => 2, 'bidang_id' => 3, 'subbidang_id' => null],
            ['id' => 8, 'nama_role' => 'Pegawai Perencanaan Ahli Muda', 'role_group' => 'pegawai', 'parent_id' => 7, 'bidang_id' => 3, 'subbidang_id' => null],
            ['id' => 9, 'nama_role' => 'Kepala Bidang Pengelolaan Layanan Informasi Publik', 'role_group' => 'kepalabagian', 'parent_id' => 1, 'bidang_id' => 4, 'subbidang_id' => null],
            ['id' => 10, 'nama_role' => 'Pengelolaan Opini Publik', 'role_group' => 'kasubbidang', 'parent_id' => 9, 'bidang_id' => 4, 'subbidang_id' => 1],
            ['id' => 11, 'nama_role' => 'Pegawai Pengelolaan Opini Publik', 'role_group' => 'pegawai', 'parent_id' => 10, 'bidang_id' => 4, 'subbidang_id' => 1],
            ['id' => 12, 'nama_role' => 'Magang Pengelolaan Opini Publik', 'role_group' => 'magang', 'parent_id' => 10, 'bidang_id' => 4, 'subbidang_id' => 1],
            ['id' => 13, 'nama_role' => 'Pengelolaan Informasi Publik', 'role_group' => 'kasubbidang', 'parent_id' => 9, 'bidang_id' => 4, 'subbidang_id' => 2],
            ['id' => 14, 'nama_role' => 'Pegawai Pengelolaan Informasi Publik', 'role_group' => 'pegawai', 'parent_id' => 13, 'bidang_id' => 4, 'subbidang_id' => 2],
            ['id' => 15, 'nama_role' => 'Magang Pengelolaan Informasi Publik', 'role_group' => 'magang', 'parent_id' => 13, 'bidang_id' => 4, 'subbidang_id' => 2],
            ['id' => 16, 'nama_role' => 'Layanan Informasi Publik', 'role_group' => 'kasubbidang', 'parent_id' => 9, 'bidang_id' => 4, 'subbidang_id' => 3],
            ['id' => 17, 'nama_role' => 'Pegawai Layanan Informasi Publik', 'role_group' => 'pegawai', 'parent_id' => 16, 'bidang_id' => 4, 'subbidang_id' => 3],
            ['id' => 18, 'nama_role' => 'Magang Layanan Informasi Publik', 'role_group' => 'magang', 'parent_id' => 16, 'bidang_id' => 4, 'subbidang_id' => 3],
            ['id' => 19, 'nama_role' => 'Kepala Bidang Tata Kelola Pemerintahan BE', 'role_group' => 'kepalabagian', 'parent_id' => 1, 'bidang_id' => 5, 'subbidang_id' => null],
            ['id' => 20, 'nama_role' => 'Tata Kelola Pemerintahan BE', 'role_group' => 'kasubbidang', 'parent_id' => 19, 'bidang_id' => 5, 'subbidang_id' => 4],
            ['id' => 21, 'nama_role' => 'Pegawai Tata Kelola Pemerintahan BE', 'role_group' => 'pegawai', 'parent_id' => 20, 'bidang_id' => 5, 'subbidang_id' => 4],
            ['id' => 22, 'nama_role' => 'Magang Tata Kelola Pemerintahan BE', 'role_group' => 'magang', 'parent_id' => 20, 'bidang_id' => 5, 'subbidang_id' => 4],
            ['id' => 23, 'nama_role' => 'Pemanfaatan Aplikasi', 'role_group' => 'kasubbidang', 'parent_id' => 19, 'bidang_id' => 5, 'subbidang_id' => 5],
            ['id' => 24, 'nama_role' => 'Pegawai Pemanfaatan Aplikasi', 'role_group' => 'pegawai', 'parent_id' => 23, 'bidang_id' => 5, 'subbidang_id' => 5],
            ['id' => 25, 'nama_role' => 'Magang Pemanfaatan Aplikasi', 'role_group' => 'magang', 'parent_id' => 23, 'bidang_id' => 5, 'subbidang_id' => 5],
            ['id' => 26, 'nama_role' => 'Hubungan Antar Lembaga TIK', 'role_group' => 'kasubbidang', 'parent_id' => 19, 'bidang_id' => 5, 'subbidang_id' => 6],
            ['id' => 27, 'nama_role' => 'Pegawai Hubungan Antar Lembaga TIK', 'role_group' => 'pegawai', 'parent_id' => 26, 'bidang_id' => 5, 'subbidang_id' => 6],
            ['id' => 28, 'nama_role' => 'Magang Hubungan Antar Lembaga TIK', 'role_group' => 'magang', 'parent_id' => 26, 'bidang_id' => 5, 'subbidang_id' => 6],
            ['id' => 29, 'nama_role' => 'Kepala Bidang Teknologi Informasi dan Komunikasi', 'role_group' => 'kepalabagian', 'parent_id' => 1, 'bidang_id' => 6, 'subbidang_id' => null],
            ['id' => 30, 'nama_role' => 'Infrastruktur dan Teknologi', 'role_group' => 'kasubbidang', 'parent_id' => 29, 'bidang_id' => 6, 'subbidang_id' => 7],
            ['id' => 31, 'nama_role' => 'Pegawai Infrastruktur dan Teknologi', 'role_group' => 'pegawai', 'parent_id' => 30, 'bidang_id' => 6, 'subbidang_id' => 7],
            ['id' => 32, 'nama_role' => 'Magang Infrastruktur dan Teknologi', 'role_group' => 'magang', 'parent_id' => 30, 'bidang_id' => 6, 'subbidang_id' => 7],
            ['id' => 33, 'nama_role' => 'Pengelolaan Data dan Integrasi Sistem Informasi', 'role_group' => 'kasubbidang', 'parent_id' => 29, 'bidang_id' => 6, 'subbidang_id' => 8],
            ['id' => 34, 'nama_role' => 'Pegawai Pengelolaan Data dan Integrasi SI', 'role_group' => 'pegawai', 'parent_id' => 33, 'bidang_id' => 6, 'subbidang_id' => 8],
            ['id' => 35, 'nama_role' => 'Magang Pengelolaan Data dan Integrasi SI', 'role_group' => 'magang', 'parent_id' => 33, 'bidang_id' => 6, 'subbidang_id' => 8],
            ['id' => 36, 'nama_role' => 'Keamanan Sistem Informasi', 'role_group' => 'kasubbidang', 'parent_id' => 29, 'bidang_id' => 6, 'subbidang_id' => 9],
            ['id' => 37, 'nama_role' => 'Pegawai Keamanan Sistem Informasi', 'role_group' => 'pegawai', 'parent_id' => 36, 'bidang_id' => 6, 'subbidang_id' => 9],
            ['id' => 38, 'nama_role' => 'Magang Keamanan Sistem Informasi', 'role_group' => 'magang', 'parent_id' => 36, 'bidang_id' => 6, 'subbidang_id' => 9],
            ['id' => 39, 'nama_role' => 'Kepala Bidang Pengelolaan Komunikasi Publik', 'role_group' => 'kepalabagian', 'parent_id' => 1, 'bidang_id' => 7, 'subbidang_id' => null],
            ['id' => 40, 'nama_role' => 'Pengelolaan Media Komunikasi Publik', 'role_group' => 'kasubbidang', 'parent_id' => 39, 'bidang_id' => 7, 'subbidang_id' => 10],
            ['id' => 41, 'nama_role' => 'Pegawai Pengelolaan Media Komunikasi Publik', 'role_group' => 'pegawai', 'parent_id' => 40, 'bidang_id' => 7, 'subbidang_id' => 10],
            ['id' => 42, 'nama_role' => 'Magang Pengelolaan Media Komunikasi Publik', 'role_group' => 'magang', 'parent_id' => 40, 'bidang_id' => 7, 'subbidang_id' => 10],
            ['id' => 43, 'nama_role' => 'Sumber Daya Komunikasi Publik', 'role_group' => 'kasubbidang', 'parent_id' => 39, 'bidang_id' => 7, 'subbidang_id' => 11],
            ['id' => 44, 'nama_role' => 'Pegawai Sumber Daya Komunikasi Publik', 'role_group' => 'pegawai', 'parent_id' => 43, 'bidang_id' => 7, 'subbidang_id' => 11],
            ['id' => 45, 'nama_role' => 'Magang Sumber Daya Komunikasi Publik', 'role_group' => 'magang', 'parent_id' => 43, 'bidang_id' => 7, 'subbidang_id' => 11],
            ['id' => 46, 'nama_role' => 'Hubungan Sosial Media', 'role_group' => 'kasubbidang', 'parent_id' => 39, 'bidang_id' => 7, 'subbidang_id' => 12],
            ['id' => 47, 'nama_role' => 'Pegawai Hubungan Sosial Media', 'role_group' => 'pegawai', 'parent_id' => 46, 'bidang_id' => 7, 'subbidang_id' => 12],
            ['id' => 48, 'nama_role' => 'Magang Hubungan Sosial Media', 'role_group' => 'magang', 'parent_id' => 46, 'bidang_id' => 7, 'subbidang_id' => 12],
            ['id' => 49, 'nama_role' => 'Kepala Bidang Persandian dan Statistik', 'role_group' => 'kepalabagian', 'parent_id' => 1, 'bidang_id' => 8, 'subbidang_id' => null],
            ['id' => 50, 'nama_role' => 'Persandian', 'role_group' => 'kasubbidang', 'parent_id' => 49, 'bidang_id' => 8, 'subbidang_id' => 13],
            ['id' => 51, 'nama_role' => 'Pegawai Persandian', 'role_group' => 'pegawai', 'parent_id' => 50, 'bidang_id' => 8, 'subbidang_id' => 13],
            ['id' => 52, 'nama_role' => 'Magang Persandian', 'role_group' => 'magang', 'parent_id' => 50, 'bidang_id' => 8, 'subbidang_id' => 13],
            ['id' => 53, 'nama_role' => 'Statistik', 'role_group' => 'kasubbidang', 'parent_id' => 49, 'bidang_id' => 8, 'subbidang_id' => 14],
            ['id' => 54, 'nama_role' => 'Pegawai Statistik', 'role_group' => 'pegawai', 'parent_id' => 53, 'bidang_id' => 8, 'subbidang_id' => 14],
            ['id' => 55, 'nama_role' => 'Magang Statistik', 'role_group' => 'magang', 'parent_id' => 53, 'bidang_id' => 8, 'subbidang_id' => 14],
            ['id' => 56, 'nama_role' => 'admin', 'role_group' => 'admin', 'parent_id' => null, 'bidang_id' => null, 'subbidang_id' => null],
        ];

        DB::table('role')->insert($roles);
    }
}
