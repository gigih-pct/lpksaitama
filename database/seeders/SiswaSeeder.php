<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Siswa;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Siswa::create([
            'nomor_induk' => '20261001',
            'nama_lengkap' => 'Rio Baskoro',
            'email' => 'siswa@lpk-saitama.com',
            'password' => Hash::make('password123'),
            'nik' => '3301012345678901',
            'tempat_lahir' => 'Cilacap',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Jl. Pahlawan No. 123, Cilacap',
            'no_hp_siswa' => '081234567890',
            'status_siswa' => 'Aktif',
            'tahap_roadmap' => 'Belajar Bahasa Jepang (N5)',
            'is_approved' => true, // Akun sudah di ACC
        ]);
        
        // Akun Siswa 2 yang belum di ACC (sebagai perbandingan jika diperlukan)
        Siswa::create([
            'nomor_induk' => '20261002',
            'nama_lengkap' => 'Budi Santoso',
            'email' => 'budi@lpk-saitama.com',
            'password' => Hash::make('password123'),
            'status_siswa' => 'Calon',
            'is_approved' => false,
        ]);
    }
}
