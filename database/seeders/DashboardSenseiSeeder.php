<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sensei;
use App\Models\Kelas;
use App\Models\Curriculum;
use App\Models\Banner;
use Carbon\Carbon;

class DashboardSenseiSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Get or create Sensei
        $sensei = Sensei::first() ?? Sensei::create([
            'nomor_induk' => 'SEN001',
            'nama_lengkap' => 'Yamada Sensei',
            'email' => 'yamada@lpk-saitama.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);

        // 2. Get or create Kelas
        $kelas = Kelas::first();
        if (!$kelas) {
            $batch = \App\Models\Batch::firstOrCreate(
                ['tahun' => 2026],
                ['nama_angkatan' => 'Angkatan 10', 'is_active' => true]
            );
            $kelas = Kelas::create([
                'batch_id' => $batch->id,
                'nama_kelas' => 'N5-Reguler-A',
                'level' => 'N5'
            ]);
        }

        // 3. Attach Sensei to Kelas (Pivot Table)
        if (!$sensei->classes()->where('class_id', $kelas->id)->exists()) {
            $sensei->classes()->attach($kelas->id);
        }

        // 4. Create Curriculums
        $curriculums = [
            [
                'judul' => 'Bab 1: Perkenalan Diri (Jikoshoukai)',
                'deskripsi' => 'Mempelajari cara memperkenalkan diri, salam dasar (Aisatsu), dan pola kalimat dasar N5.',
                'urutan' => 1
            ],
            [
                'judul' => 'Bab 2: Menanyakan Benda dan Harga',
                'deskripsi' => 'Belajar menggunakan Kore/Sore/Are dan Kono/Sono/Ano beserta cara menanyakan harga di supermarket Jepang.',
                'urutan' => 2
            ],
            [
                'judul' => 'Bab 3: Lokasi dan Keberadaan',
                'deskripsi' => 'Pola kalimat penunjukan tempat (Koko/Soko/Asoko) dan cara menanyakan lokasi ruangan atau stasiun.',
                'urutan' => 3
            ],
            [
                'judul' => 'Bab 4: Jam dan Hari',
                'deskripsi' => 'Belajar mengucapkan waktu, hari dalam seminggu, dan rutinitas harian.',
                'urutan' => 4
            ]
        ];

        foreach ($curriculums as $curr) {
            Curriculum::firstOrCreate([
                'kelas_id' => $kelas->id,
                'sensei_id' => $sensei->id,
                'judul' => $curr['judul']
            ], [
                'deskripsi' => $curr['deskripsi'],
                'urutan' => $curr['urutan']
            ]);
        }

        // 5. Additional Banners targeted for Sensei if needed
        Banner::firstOrCreate([
            'judul' => 'Persiapkan Materi Kelasmu',
            'deskripsi' => 'Susun bagan pembelajaran yang terstruktur di menu Pembelajaran agar siswa dapat mengikuti alur belajar dengan mudah.',
            'role_target' => 'Semua',
            'lokasi' => 'Dashboard'
        ]);

        Banner::firstOrCreate([
            'judul' => 'Evaluasi Kemampuan Siswa',
            'deskripsi' => 'Berikan catatan evaluasi secara berkala untuk memantau perkembangan kompetensi bahasa Jepang siswa Anda.',
            'role_target' => 'Semua',
            'lokasi' => 'Dashboard'
        ]);
    }
}
