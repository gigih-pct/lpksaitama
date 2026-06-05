<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jadwal;
use App\Models\Kalender;
use App\Models\Kelas;
use Carbon\Carbon;

class JadwalKalenderSeeder extends Seeder
{
    public function run(): void
    {
        $kelas = Kelas::first(); // Assuming a class exists
        $subjects = \App\Models\Subject::all();
        
        if ($kelas && $subjects->count() >= 3) {
            $jadwals = [
                ['hari' => 'Senin', 'jam_mulai' => '13:00', 'jam_selesai' => '15:00', 'subject_id' => $subjects[0]->id, 'kegiatan' => 'Pelajaran ' . $subjects[0]->nama_pelajaran],
                ['hari' => 'Rabu', 'jam_mulai' => '13:00', 'jam_selesai' => '15:00', 'subject_id' => $subjects[1]->id, 'kegiatan' => 'Pelajaran ' . $subjects[1]->nama_pelajaran],
                ['hari' => 'Jumat', 'jam_mulai' => '08:00', 'jam_selesai' => '10:00', 'subject_id' => $subjects[2]->id, 'kegiatan' => 'Pelajaran ' . $subjects[2]->nama_pelajaran],
            ];

            foreach ($jadwals as $j) {
                Jadwal::create(array_merge($j, ['kelas_id' => $kelas->id]));
            }
        }

        $now = Carbon::now();
        Kalender::create([
            'tanggal_mulai' => $now->copy()->addDays(5)->format('Y-m-d'),
            'kegiatan' => 'Ujian Tengah Semester',
            'jenis' => 'Ujian'
        ]);
        
        Kalender::create([
            'tanggal_mulai' => $now->copy()->addDays(15)->format('Y-m-d'),
            'kegiatan' => 'Libur Nasional',
            'jenis' => 'Libur'
        ]);
        
        Kalender::create([
            'tanggal_mulai' => $now->copy()->addDays(20)->format('Y-m-d'),
            'tanggal_selesai' => $now->copy()->addDays(22)->format('Y-m-d'),
            'kegiatan' => 'Simulasi JFT-Basic',
            'jenis' => 'Umum'
        ]);
    }
}
