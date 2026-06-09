<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Subject;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\EvaluasiBunpou;
use App\Models\EvaluasiFmd;
use App\Models\EvaluasiWawancara;
use App\Models\NilaiBunpou;
use App\Models\NilaiFmd;
use App\Models\NilaiWawancara;
use App\Models\Jadwal;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\Absensi;

class NilaiSeeder extends Seeder
{
    public function run()
    {
        // Ensure we have a Batch
        $batch = \App\Models\Batch::firstOrCreate(
            ['nama_angkatan' => 'Angkatan 1', 'tahun' => date('Y')],
            ['is_active' => true]
        );

        // Ensure we have a Class
        $kelas = Kelas::firstOrCreate(
            ['nama_kelas' => 'Intensif A'],
            ['batch_id' => $batch->id, 'level' => 'N5']
        );

        // Subjects (BKK)
        $subjectBunpou = Subject::firstOrCreate(['nama_pelajaran' => 'Bunpou', 'kkm' => 75]);
        $subjectKanji = Subject::firstOrCreate(['nama_pelajaran' => 'Kanji', 'kkm' => 75]);
        $subjectKotoba = Subject::firstOrCreate(['nama_pelajaran' => 'Kotoba', 'kkm' => 75]);

        // Generate 50 Students
        $siswas = [];
        for ($i = 0; $i < 50; $i++) {
            $siswa = Siswa::updateOrCreate(
                ['nomor_induk' => 'NIS' . str_pad($i + 1, 4, '0', STR_PAD_LEFT)],
                [
                    'class_id' => $kelas->id,
                    'nama_lengkap' => 'Siswa Dummy ' . ($i + 1),
                    'email' => 'siswa' . ($i + 1) . '@lpk-saitama.com',
                    'password' => Hash::make('password'),
                    'tanggal_lahir' => '2000-01-01',
                    'tempat_lahir' => 'Jakarta',
                    'alamat' => 'Jl. Merdeka No. ' . ($i + 1),
                    'no_hp_siswa' => '0812345678' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'status_siswa' => 'Aktif',
                    'is_approved' => true,
                ]
            );
            $siswas[] = $siswa;
        }

        // Generate Jadwal, AttendanceSession & Attendance
        $jadwal = Jadwal::firstOrCreate([
            'kelas_id' => $kelas->id,
            'hari' => 'Senin',
            'kegiatan' => 'Belajar Bahasa Jepang',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '12:00:00',
        ]);

        $session = \App\Models\AttendanceSession::firstOrCreate([
            'jadwal_id' => $jadwal->id,
            'tanggal' => now()->format('Y-m-d'),
            'kode_absen' => 'ABS' . Str::random(5)
        ]);

        $attendanceStatuses = ['Hadir', 'Hadir', 'Hadir', 'Izin', 'Sakit', 'Alpa'];
        foreach ($siswas as $siswa) {
            \App\Models\Attendance::firstOrCreate([
                'attendance_session_id' => $session->id,
                'siswa_id' => $siswa->id,
            ], [
                'status' => $attendanceStatuses[array_rand($attendanceStatuses)],
            ]);
        }

        // Evaluasi BKK
        $evalBkk = EvaluasiBunpou::firstOrCreate([
            'kelas_id' => $kelas->id,
            'sensei_id' => 1,
            'kategori' => 'Sumatif',
            'judul' => 'Ujian Sumatif 1',
            'tanggal' => now()->format('Y-m-d'),
            'kkm' => 75,
        ]);

        foreach ($siswas as $siswa) {
            NilaiBunpou::updateOrCreate(
                ['evaluasi_bunpou_id' => $evalBkk->id, 'siswa_id' => $siswa->id],
                ['nilai' => rand(50, 100)]
            );
        }

        // Evaluasi FMD
        $evalFmd = EvaluasiFmd::firstOrCreate([
            'kelas_id' => $kelas->id,
            'sensei_id' => 1,
            'kategori' => 'Mingguan',
            'judul' => 'FMD Minggu 1',
            'tanggal' => now()->format('Y-m-d'),
        ]);

        $lTlOptions = ['L', 'TL'];
        foreach ($siswas as $siswa) {
            NilaiFmd::updateOrCreate(
                ['evaluasi_fmd_id' => $evalFmd->id, 'siswa_id' => $siswa->id],
                [
                    'skor_mtk' => rand(50, 100),
                    'skor_lari' => rand(50, 100),
                    'skor_push_up' => rand(50, 100),
                    'skor_sit_up' => rand(50, 100),
                    'ket' => $lTlOptions[array_rand($lTlOptions)]
                ]
            );
        }

        // Evaluasi Wawancara
        $evalWawancara = EvaluasiWawancara::firstOrCreate([
            'kelas_id' => $kelas->id,
            'sensei_id' => 1,
            'kategori' => 'Bulanan',
            'judul' => 'Wawancara Bulan 1',
            'tanggal' => now()->format('Y-m-d'),
            'kkm_materi' => 75,
            'kkm_sikap' => 75,
        ]);

        $ketOptions = ['Sangat Baik', 'Baik', 'Cukup', 'Kurang', 'Sangat Kurang'];
        foreach ($siswas as $siswa) {
            NilaiWawancara::updateOrCreate(
                ['eval_wawancara_id' => $evalWawancara->id, 'siswa_id' => $siswa->id],
                [
                    'materi_program' => rand(60, 100),
                    'materi_umum' => rand(60, 100),
                    'materi_jepang' => rand(60, 100),
                    'materi_indonesia' => rand(60, 100),
                    'sikap_cara_duduk' => rand(60, 100),
                    'sikap_suara' => rand(60, 100),
                    'sikap_fokus' => rand(60, 100),
                    'ket' => $ketOptions[array_rand($ketOptions)],
                    'catatan' => 'Catatan evaluasi wawancara.',
                ]
            );
        }
    }
}
