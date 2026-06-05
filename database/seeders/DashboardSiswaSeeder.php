<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Batch;
use App\Models\Kelas;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\Evaluation;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Equipment;
use App\Models\StudentEquipment;
use App\Models\DocumentType;
use App\Models\StudentDocument;
use App\Models\Announcement;
use App\Models\Banner;
use App\Models\Siswa;
use App\Models\Sensei;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DashboardSiswaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Sensei
        $sensei = Sensei::create([
            'nomor_induk' => 'SEN001',
            'nama_lengkap' => 'Yamada Sensei',
            'email' => 'yamada@lpk-saitama.com',
            'password' => Hash::make('password123'),
        ]);

        // 2. Create Batch & Kelas
        $batch = Batch::create([
            'nama_angkatan' => 'Angkatan 10',
            'tahun' => 2026,
            'is_active' => true
        ]);

        $kelas = Kelas::create([
            'batch_id' => $batch->id,
            'nama_kelas' => 'N5-Reguler-A',
            'level' => 'N5'
        ]);

        // 3. Update or Create Siswa with Class
        $siswa = Siswa::firstOrCreate(
            ['email' => 'siswa@lpk-saitama.com'],
            [
                'nama_lengkap' => 'Budi Siswa',
                'password' => Hash::make('password123'),
                'nomor_induk' => 'SIS2026001',
                'no_hp_siswa' => '081234567890',
                'no_hp_orangtua' => '081298765432',
                'alamat' => 'Sleman, Yogyakarta',
                'status_siswa' => 'Aktif',
                'class_id' => $kelas->id
            ]
        );
        $siswa->update(['class_id' => $kelas->id]);

        // 4. Create Subjects
        $subjects = [
            Subject::create(['nama_pelajaran' => 'Bunpou']),
            Subject::create(['nama_pelajaran' => 'Kanji']),
            Subject::create(['nama_pelajaran' => 'Kotoba']),
            Subject::create(['nama_pelajaran' => 'FMD']),
            Subject::create(['nama_pelajaran' => 'Wawancara']),
        ];

        // 5. Create Grades (Nilai)
        if ($siswa) {
            foreach ($subjects as $subject) {
                Grade::create([
                    'siswa_id' => $siswa->id,
                    'subject_id' => $subject->id,
                    'sensei_id' => $sensei->id,
                    'nilai' => rand(70, 95),
                    'keterangan' => 'Lulus KKM',
                    'tanggal_input' => Carbon::now()->subDays(rand(1, 10))
                ]);
            }

            // 6. Create Evaluations
            Evaluation::create([
                'siswa_id' => $siswa->id,
                'sensei_id' => $sensei->id,
                'judul' => 'Evaluasi Bulan Pertama',
                'deskripsi' => 'Kemampuan membaca (Dokkai) sudah bagus, namun pendengaran (Choukai) masih butuh banyak latihan. Perbanyak mendengarkan audio Kaiwa.',
                'tanggal_evaluasi' => Carbon::now()->subDays(15)
            ]);

            // 7. Create Invoices & Payments
            $invoice1 = Invoice::create([
                'siswa_id' => $siswa->id,
                'judul' => 'Biaya Pendaftaran & Seragam',
                'nominal' => 1500000,
                'jatuh_tempo' => Carbon::now()->subMonths(1),
                'status' => 'Lunas'
            ]);

            Payment::create([
                'invoice_id' => $invoice1->id,
                'nominal_dibayar' => 1500000,
                'tanggal_bayar' => Carbon::now()->subMonths(1)->addDays(2),
                'status_verifikasi' => 'Diterima'
            ]);

            Invoice::create([
                'siswa_id' => $siswa->id,
                'judul' => 'SPP Bulan Pertama',
                'nominal' => 500000,
                'jatuh_tempo' => Carbon::now()->addDays(5),
                'status' => 'Belum Lunas'
            ]);

            // 8. Create Equipments & Student Equipments
            $equipSeragam = Equipment::create(['nama_perlengkapan' => 'Seragam LPK Saitama']);
            $equipBuku = Equipment::create(['nama_perlengkapan' => 'Buku Minna no Nihongo 1']);
            
            StudentEquipment::create([
                'siswa_id' => $siswa->id,
                'equipment_id' => $equipSeragam->id,
                'status' => 'Sudah Diambil',
                'tanggal_diambil' => Carbon::now()->subDays(20)
            ]);

            StudentEquipment::create([
                'siswa_id' => $siswa->id,
                'equipment_id' => $equipBuku->id,
                'status' => 'Bisa Diambil'
            ]);

            // 9. Create Document Types & Student Documents
            $docKtp = DocumentType::create(['nama_berkas' => 'KTP', 'is_mandatory' => true]);
            $docKk = DocumentType::create(['nama_berkas' => 'Kartu Keluarga', 'is_mandatory' => true]);
            $docIjazah = DocumentType::create(['nama_berkas' => 'Ijazah Terakhir', 'is_mandatory' => true]);

            StudentDocument::create([
                'siswa_id' => $siswa->id,
                'document_type_id' => $docKtp->id,
                'status' => 'Sudah Verifikasi',
                'tanggal_kumpul' => Carbon::now()->subDays(25)
            ]);

            StudentDocument::create([
                'siswa_id' => $siswa->id,
                'document_type_id' => $docKk->id,
                'status' => 'Menunggu Verifikasi',
                'tanggal_kumpul' => Carbon::now()->subDays(2)
            ]);
        }

        // 10. Create Announcements
        Announcement::create([
            'judul' => 'Jadwal Try Out JFT-Basic Bulan Ini',
            'isi' => 'Try out akan dilaksanakan pada hari Sabtu pukul 09:00 JST. Harap persiapkan diri Anda.',
            'role_target' => 'Siswa',
            'tanggal' => Carbon::now()
        ]);
        
        Announcement::create([
            'judul' => 'Pengumpulan Berkas Paspor',
            'isi' => 'Bagi siswa angkatan 10, batas akhir pengumpulan paspor adalah akhir bulan ini.',
            'role_target' => 'Siswa',
            'tanggal' => Carbon::now()->subDays(3)
        ]);

        // 11. Create Banners
        Banner::create([
            'judul' => 'Berangkat ke Jepang, Raih Impianmu!',
            'deskripsi' => 'Persiapkan dirimu untuk ujian SSW dan JFT-Basic. LPK Saitama mendampingimu dari nol hingga keberangkatan dengan fasilitas terbaik.',
            'role_target' => 'Siswa',
            'lokasi' => 'Auth'
        ]);

        Banner::create([
            'judul' => 'Materi Lengkap & Terstruktur',
            'deskripsi' => 'Akses modul pembelajaran, latihan soal, dan audio Choukai langsung dari portal siswa kapan saja dan di mana saja.',
            'role_target' => 'Siswa',
            'lokasi' => 'Auth'
        ]);

        Banner::create([
            'judul' => 'Pantau Progress Akademik',
            'deskripsi' => 'Gunakan dashboard interaktif untuk melihat grafik performa belajarmu, jadwal kelas, dan riwayat tagihan dengan mudah.',
            'role_target' => 'Siswa',
            'lokasi' => 'Auth'
        ]);

        Banner::create([
            'judul' => 'Pusat Informasi Dashboard',
            'deskripsi' => 'Tetap update dengan pengumuman terbaru dan informasi keberangkatan. Pastikan semua berkasmu lengkap.',
            'role_target' => 'Siswa',
            'lokasi' => 'Dashboard'
        ]);
    }
}
