@extends('sensei.app')

@section('title', 'Evaluasi & Nilai - LPK Saitama')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 animate-fade-in-up" x-data="{
    kelases: {{ $kelases->toJson() }},
    showExamModal: false,
    showScoreModal: false,
    currentExam: null,
    currentStudents: [],
    openScoreModal(exam) {
        this.currentExam = exam;
        let kelas = this.kelases.find(k => k.id === exam.kelas_id);
        
        let scores = exam.scores || [];
        this.currentStudents = kelas.siswas.map(siswa => {
            let existing = scores.find(s => s.siswa_id === siswa.id);
            return {
                id: siswa.id,
                nama: siswa.nama_lengkap,
                benar: existing ? existing.benar : '',
                total_soal: existing ? existing.total_soal : ''
            };
        });
        
        this.showScoreModal = true;
    }
}">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
        <div>
            <h1 class="text-2xl font-medium text-primer tracking-tight">Evaluasi & Nilai</h1>
            <p class="text-sm text-gray-500 mt-1">Buat komponen ujian (misal: Kanji Bab 1) dan masukkan nilai siswa.</p>
        </div>
        <div>
            <button @click="showExamModal = true" class="bg-sekunder hover:bg-sekunder/90 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors flex items-center shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Evaluasi Baru
            </button>
        </div>
    </div>

    <!-- Timeline Evaluasi -->
    <div class="bg-white rounded-md border border-gray-100 shadow-sm p-6">
        <h2 class="text-lg font-medium text-primer mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2 text-sekunder" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            Daftar Komponen Ujian/Evaluasi
        </h2>

        <div class="space-y-4">
            @forelse($exams as $exam)
                <div class="bg-gray-50 rounded-md border border-gray-200 p-5 hover:shadow-sm transition-shadow flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="bg-primer/10 text-primer px-2 py-0.5 rounded text-xs font-semibold uppercase">{{ $exam->kategori }}</span>
                            <h3 class="text-lg font-medium text-gray-900">{{ $exam->judul }}</h3>
                        </div>
                        <div class="text-sm text-gray-600 flex items-center gap-3 mt-2">
                            <span>📅 {{ \Carbon\Carbon::parse($exam->tanggal)->format('d M Y') }}</span>
                            <span>📖 {{ $exam->subject->nama_mata_pelajaran ?? '-' }}</span>
                            <span>🏫 Kelas {{ $exam->kelas->nama_kelas ?? '-' }}</span>
                            <span class="font-medium text-primer">KKM: {{ $exam->kkm }}</span>
                        </div>
                        
                        <!-- Statistik singkat -->
                        @php
                            $totalSiswa = $exam->kelas->siswas->count();
                            $sudahDinilai = $exam->scores->count();
                            $persentase = $totalSiswa > 0 ? round(($sudahDinilai / $totalSiswa) * 100) : 0;
                        @endphp
                        <div class="mt-3 flex items-center gap-2">
                            <div class="w-48 bg-gray-200 rounded-full h-1.5">
                                <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $persentase }}%"></div>
                            </div>
                            <span class="text-xs text-gray-500">{{ $sudahDinilai }}/{{ $totalSiswa }} Dinilai</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2 shrink-0">
                        <!-- Convert the exam instance to json for Alpine -->
                        <button @click="openScoreModal({{ $exam->toJson() }})" class="bg-white border border-primer text-primer hover:bg-primer hover:text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            Input / Edit Nilai
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center py-10">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">Belum Ada Evaluasi</h3>
                    <p class="text-sm text-gray-500">Anda belum membuat ujian atau evaluasi untuk kelas Anda.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal Buat Evaluasi -->
    <template x-teleport="body">
        <div x-show="showExamModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div x-show="showExamModal" @click="showExamModal = false" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-900/75" aria-hidden="true"></div>
                <div x-show="showExamModal" x-transition.scale.origin.bottom class="inline-block w-full max-w-lg p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-xl relative z-10">
                    <h3 class="text-lg font-medium text-primer mb-4">Buat Ujian / Evaluasi Baru</h3>
                    <form action="{{ route('sensei.evaluasi.store') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelas</label>
                                <select name="kelas_id" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:ring-primer focus:border-primer" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($kelases as $kelas)
                                        <option value="{{ $kelas->id }}">Kelas {{ $kelas->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                                <select name="subject_id" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:ring-primer focus:border-primer" required>
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->nama_mata_pelajaran }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                    <select name="kategori" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:ring-primer focus:border-primer" required>
                                        <option value="Formatif">Formatif (Harian/Bab)</option>
                                        <option value="Sumatif">Sumatif (Akhir Bulan/Term)</option>
                                        <option value="Ujian Akhir">Ujian Akhir</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:ring-primer focus:border-primer" required>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul / Keterangan</label>
                                    <input type="text" name="judul" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:ring-primer focus:border-primer" placeholder="Contoh: Kanji Bab 1-3" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nilai KKM (%)</label>
                                    <input type="number" name="kkm" value="75" min="0" max="100" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:ring-primer focus:border-primer" required>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end gap-2 mt-6">
                            <button type="button" @click="showExamModal = false" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200">Batal</button>
                            <button type="submit" class="px-4 py-2 text-sm text-white bg-sekunder rounded-md hover:bg-sekunder/90">Buat Evaluasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>

    <!-- Modal Input Nilai -->
    <template x-teleport="body">
        <div x-show="showScoreModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 py-10 text-center">
                <div x-show="showScoreModal" @click="showScoreModal = false" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-900/75" aria-hidden="true"></div>
                <div x-show="showScoreModal" x-transition.scale.origin.bottom class="inline-block w-full max-w-4xl p-0 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-xl relative z-10 flex flex-col max-h-[85vh]">
                    <div class="p-5 bg-primer text-white flex justify-between items-center rounded-t-xl shrink-0">
                        <div>
                            <h3 class="text-lg font-medium" x-text="'Input Nilai: ' + (currentExam ? currentExam.judul : '')"></h3>
                            <p class="text-white/80 text-sm" x-text="currentExam ? 'KKM: ' + currentExam.kkm + '%' : ''"></p>
                        </div>
                        <button @click="showScoreModal = false" class="text-white/70 hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="p-6 overflow-y-auto flex-1">
                        <form action="{{ route('sensei.evaluasi.score.store') }}" method="POST" id="scoreForm">
                            @csrf
                            <input type="hidden" name="exam_id" :value="currentExam ? currentExam.id : ''">
                            
                            <table class="w-full text-sm text-left">
                                <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3">No</th>
                                        <th class="px-4 py-3">Nama Siswa</th>
                                        <th class="px-4 py-3 w-32">Jawaban Benar</th>
                                        <th class="px-4 py-3 w-32">Total Soal</th>
                                        <th class="px-4 py-3 w-24">Skor Akhir</th>
                                        <th class="px-4 py-3 w-24">Ket</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(siswa, index) in currentStudents" :key="siswa.id">
                                        <tr class="border-b">
                                            <td class="px-4 py-3 font-medium text-gray-900" x-text="index + 1"></td>
                                            <td class="px-4 py-3 font-medium text-gray-900">
                                                <span x-text="siswa.nama"></span>
                                                <input type="hidden" :name="'scores['+index+'][siswa_id]'" :value="siswa.id">
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="number" :name="'scores['+index+'][benar]'" x-model.number="siswa.benar" min="0" class="w-full border border-gray-300 rounded px-2 py-1 focus:ring-primer focus:border-primer">
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="number" :name="'scores['+index+'][total_soal]'" x-model.number="siswa.total_soal" min="1" class="w-full border border-gray-300 rounded px-2 py-1 focus:ring-primer focus:border-primer">
                                            </td>
                                            <td class="px-4 py-3 font-bold text-primer">
                                                <span x-text="(siswa.benar !== '' && siswa.total_soal !== '' && siswa.total_soal > 0) ? Math.round((siswa.benar / siswa.total_soal) * 100) + '%' : '-'"></span>
                                            </td>
                                            <td class="px-4 py-3 font-medium">
                                                <template x-if="siswa.benar !== '' && siswa.total_soal !== '' && siswa.total_soal > 0">
                                                    <span :class="Math.round((siswa.benar / siswa.total_soal) * 100) >= currentExam.kkm ? 'text-green-600 bg-green-100 px-2 py-0.5 rounded' : 'text-red-600 bg-red-100 px-2 py-0.5 rounded'" x-text="Math.round((siswa.benar / siswa.total_soal) * 100) >= currentExam.kkm ? 'L' : 'TL'"></span>
                                                </template>
                                                <template x-if="siswa.benar === '' || siswa.total_soal === '' || siswa.total_soal == 0">
                                                    <span class="text-gray-400">-</span>
                                                </template>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </form>
                    </div>

                    <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-2 shrink-0 rounded-b-xl">
                        <button type="button" @click="showScoreModal = false" class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Batal</button>
                        <button type="button" @click="document.getElementById('scoreForm').submit()" class="px-5 py-2 text-sm font-medium text-white bg-sekunder rounded-md hover:bg-sekunder/90">Simpan Semua Nilai</button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.5s ease-out forwards;
    }
</style>
@endsection
