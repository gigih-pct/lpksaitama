@extends('sensei.app')

@section('title', 'Penilaian Siswa - LPK Saitama')

@section('content')

<div class="max-w-7xl mx-auto space-y-6 animate-fade-in-up" x-data="{
    showExamModal: false,
    showScoreModal: false,
    showEditExamModal: false,
    showNilaiAkhirExamModal: false,
    showEditNilaiAkhirExamModal: false,
    showNilaiAkhirScoreModal: false,
    editNilaiAkhirData: {},
    editKriteriaBuilder: [],
    kriteriaBuilder: ['Nilai', 'Huruf'],
    editExamData: {},
    editExamType: '',
    currentEval: null,
    currentStudents: [],
    modalSearch: '',
    modalPerPage: 10,
    modalCurrentPage: 1,
    subjectType: '{{ $subjectType ?? '' }}',
    
    openEditNilaiAkhirModal(evalData) {
        this.editNilaiAkhirData = evalData;
        this.editKriteriaBuilder = evalData.kriteria_kolom ? evalData.kriteria_kolom : [];
        this.showEditNilaiAkhirExamModal = true;
    },

    init() {
        this.$watch('showExamModal', val => document.body.style.overflow = val ? 'hidden' : '');
        this.$watch('showEditExamModal', val => document.body.style.overflow = val ? 'hidden' : '');
        this.$watch('showScoreModal', val => document.body.style.overflow = val ? 'hidden' : '');
        this.$watch('showNilaiAkhirExamModal', val => document.body.style.overflow = val ? 'hidden' : '');
        this.$watch('showEditNilaiAkhirExamModal', val => document.body.style.overflow = val ? 'hidden' : '');
        this.$watch('showNilaiAkhirScoreModal', val => document.body.style.overflow = val ? 'hidden' : '');
    },
    get paginatedStudents() {
        let filtered = this.currentStudents;
        if (this.modalSearch.trim() !== '') {
            let s = this.modalSearch.toLowerCase();
            filtered = filtered.filter(st => st.nama.toLowerCase().includes(s));
        }
        let start = (this.modalCurrentPage - 1) * this.modalPerPage;
        let end = start + parseInt(this.modalPerPage);
        return filtered.slice(start, end);
    },
    get totalPages() {
        let filtered = this.currentStudents;
        if (this.modalSearch.trim() !== '') {
            let s = this.modalSearch.toLowerCase();
            filtered = filtered.filter(st => st.nama.toLowerCase().includes(s));
        }
        return Math.ceil(filtered.length / this.modalPerPage);
    },
    openEditExamModal(evalJson, type) {
        this.editExamData = evalJson;
        this.editExamData.tanggal = evalJson.tanggal ? evalJson.tanggal.substring(0, 10) : '';
        this.editExamType = type;
        this.showEditExamModal = true;
    },
    openScoreModal(evalJson) {
        this.currentEval = evalJson;
        this.modalSearch = '';
        this.modalCurrentPage = 1;
        let siswas = {{ isset($allSiswas) && $allSiswas->count() > 0 ? $allSiswas->toJson() : '[]' }};
        let nilais = evalJson.nilais || [];
        
        if (this.subjectType === 'bunpou' || this.subjectType === 'kanji' || this.subjectType === 'kotoba') {
            this.currentStudents = siswas.map(s => {
                let existing = nilais.find(n => n.siswa_id === s.id);
                return { id: s.id, nama: s.nama_lengkap, nilai: existing ? existing.nilai : '' };
            });
        } else if (this.subjectType === 'fmd') {
            this.currentStudents = siswas.map(s => {
                let existing = nilais.find(n => n.siswa_id === s.id);
                return {
                    id: s.id, nama: s.nama_lengkap,
                    skor_mtk: existing ? existing.skor_mtk : '',
                    skor_lari: existing ? existing.skor_lari : '',
                    skor_push_up: existing ? existing.skor_push_up : '',
                    skor_sit_up: existing ? existing.skor_sit_up : '',
                    ket: existing ? existing.ket : '',
                };
            });
        } else if (this.subjectType === 'wawancara') {
            this.currentStudents = siswas.map(s => {
                let existing = nilais.find(n => n.siswa_id === s.id);
                return {
                    id: s.id, nama: s.nama_lengkap,
                    materi_program: existing ? existing.materi_program : '',
                    materi_umum: existing ? existing.materi_umum : '',
                    materi_jepang: existing ? existing.materi_jepang : '',
                    materi_indonesia: existing ? existing.materi_indonesia : '',
                    sikap_cara_duduk: existing ? existing.sikap_cara_duduk : '',
                    sikap_suara: existing ? existing.sikap_suara : '',
                    sikap_fokus: existing ? existing.sikap_fokus : '',
                    ket: existing ? existing.ket : '',
                    catatan: existing ? existing.catatan : '',
                };
            });
        } else if (this.subjectType === 'nilai_akhir') {
            this.currentStudents = siswas.map(s => {
                let existing = nilais.find(n => n.siswa_id === s.id);
                return {
                    id: s.id, nama: s.nama_lengkap,
                    nilai_data: existing ? existing.nilai_data : [],
                };
            });
        }
        
        if (this.subjectType === 'nilai_akhir') {
            this.showNilaiAkhirScoreModal = true;
        } else {
            this.showScoreModal = true;
        }
    }
}">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
        <div>
            <h1 class="text-2xl font-medium text-primer tracking-tight">Data Penilaian Kelas</h1>
            <p class="text-sm text-gray-500 mt-1">Rekapitulasi dan input nilai berdasarkan kelas dan mata pelajaran.</p>
        </div>
    </div>

    <!-- Filter Form: Umum (Kelas & Waktu) -->
    <div class="bg-white p-5 rounded-md border border-gray-100 shadow-sm mb-6">
        <h2 class="text-sm font-bold text-gray-700 mb-3 border-b border-gray-100 pb-2">Filter Umum (Kelas & Waktu)</h2>
        <form method="GET" action="{{ route('sensei.penilaian') }}" class="flex flex-col sm:flex-row gap-4 items-end flex-wrap">
            <div class="w-full sm:w-auto flex-1 min-w-[200px]">
                <label class="block text-xs font-medium text-gray-700 mb-1">Filter Kelas</label>
                <select name="kelas_id" class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 outline-none focus:border-primer focus:ring-1 focus:ring-primer bg-white">
                    @foreach($kelases as $kelas)
                        <option value="{{ $kelas->id }}" {{ $selectedKelasId == $kelas->id ? 'selected' : '' }}>
                            Kelas {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:w-auto w-32">
                <label class="block text-xs font-medium text-gray-700 mb-1">Bulan</label>
                <select name="bulan" class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 outline-none focus:border-primer focus:ring-1 focus:ring-primer bg-white">
                    <option value="all" {{ ($selectedBulan ?? '') == 'all' ? 'selected' : '' }}>Semua</option>
                    @for($i=1; $i<=12; $i++)
                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ ($selectedBulan ?? date('m')) == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 10)) }}</option>
                    @endfor
                </select>
            </div>
            <div class="w-full sm:w-auto w-32">
                <label class="block text-xs font-medium text-gray-700 mb-1">Tahun</label>
                <select name="tahun" class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 outline-none focus:border-primer focus:ring-1 focus:ring-primer bg-white">
                    <option value="all" {{ ($selectedTahun ?? '') == 'all' ? 'selected' : '' }}>Semua</option>
                    @php $currentY = date('Y'); @endphp
                    @for($y = $currentY - 2; $y <= $currentY + 2; $y++)
                        <option value="{{ $y }}" {{ ($selectedTahun ?? $currentY) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="w-full sm:w-auto w-24">
                <label class="block text-xs font-medium text-gray-700 mb-1">Tampil</label>
                <select name="per_page_absensi" class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 outline-none focus:border-primer focus:ring-1 focus:ring-primer bg-white">
                    <option value="5" {{ ($perPageAbsensi ?? 10) == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ ($perPageAbsensi ?? 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="50" {{ ($perPageAbsensi ?? 10) == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ ($perPageAbsensi ?? 10) == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>
            <div class="w-full sm:w-auto">
                <button type="submit" class="bg-sekunder hover:bg-primer/90 text-white px-5 py-2 rounded-md text-sm font-medium transition-colors shadow-sm w-full">
                    Filter
                </button>
            </div>
        </form>
    </div>

    @if($selectedKelasId)
        {{-- ============================================================ --}}
        {{-- PENILAIAN ABSENSI (GENERAL KELAS) --}}
        {{-- ============================================================ --}}
        @if(isset($absensiData) && $absensiData->count() > 0)
        <div class="bg-white p-5 rounded-md border border-gray-100 shadow-sm mb-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-medium text-primer text-lg">Rekapitulasi Absensi Kelas</h3>
                <span class="text-xs bg-primer/10 text-primer px-3 py-1 rounded font-medium">
                    Sesi Terlaksana: {{ $absensiData->first()['total_sessions'] ?? 0 }}
                </span>
            </div>

            <!-- Rerata Absensi Metric -->
            <div class="mb-4 bg-tersier/5 border border-tersier/10 p-4 rounded-md flex items-center justify-between max-w-sm">
                <div>
                    <p class="text-xs text-gray-500 font-medium">Rerata Absensi Kelas</p>
                    <h3 class="text-2xl font-bold text-tersier">{{ $kelasRerataAbsensi ?? 0 }}%</h3>
                </div>
                <div class="w-10 h-10 bg-tersier/10 rounded-full flex items-center justify-center text-tersier">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-primer text-white">
                        <tr>
                            <th class="px-4 py-3 w-10 text-center">No</th>
                            <th class="px-4 py-3">Nama Siswa</th>
                            <th class="px-4 py-3 text-center w-24">Hadir</th>
                            <th class="px-4 py-3 text-center w-24">Izin</th>
                            <th class="px-4 py-3 text-center w-24">Sakit</th>
                            <th class="px-4 py-3 text-center w-24">Alpa</th>
                            <th class="px-4 py-3 text-center w-32">Rerata (%)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($siswasAbsensi as $index => $siswa)
                            @php $abs = $absensiData->get($siswa->id); @endphp
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-4 py-3 text-center text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-medium text-primer">{{ $siswa->nama_lengkap }}</td>
                                <td class="px-4 py-3 text-center font-medium text-primer">{{ $abs['hadir'] ?? 0 }}</td>
                                <td class="px-4 py-3 text-center font-medium text-tersier">{{ $abs['izin'] ?? 0 }}</td>
                                <td class="px-4 py-3 text-center font-medium text-tersier">{{ $abs['sakit'] ?? 0 }}</td>
                                <td class="px-4 py-3 text-center font-medium text-sekunder">{{ $abs['alpa'] ?? 0 }}</td>
                                <td class="px-4 py-3 text-center font-bold text-tersier">
                                    <span class="bg-tersier/10 px-3 py-1 rounded">{{ $abs['persentase'] ?? 0 }}%</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($siswasAbsensi instanceof \Illuminate\Pagination\LengthAwarePaginator && $siswasAbsensi->hasPages())
                <div class="mt-4 pt-4 border-t border-gray-100">
                    {{ $siswasAbsensi->links() }}
                </div>
            @endif
        </div>
        @endif

        <!-- Filter Form: Mata Pelajaran -->
        <div class="bg-white p-5 rounded-md border border-gray-100 shadow-sm mb-6">
            <h2 class="text-sm font-bold text-gray-700 mb-3 border-b border-gray-100 pb-2">Filter Penilaian Mata Pelajaran</h2>
            <form method="GET" action="{{ route('sensei.penilaian') }}" class="flex flex-col sm:flex-row gap-4 items-end flex-wrap">
                <input type="hidden" name="kelas_id" value="{{ $selectedKelasId }}">
                <input type="hidden" name="bulan" value="{{ $selectedBulan }}">
                <input type="hidden" name="tahun" value="{{ $selectedTahun }}">
                <div class="w-full sm:w-auto flex-1 min-w-[200px]">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                    <select name="subject_id" class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 outline-none focus:border-primer focus:ring-1 focus:ring-primer bg-white">
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ $selectedSubjectId == $subject->id ? 'selected' : '' }}>
                                {{ $subject->nama_pelajaran }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full sm:w-auto w-24">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tampil</label>
                    <select name="per_page_nilai" class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 outline-none focus:border-primer focus:ring-1 focus:ring-primer bg-white">
                        <option value="5" {{ ($perPageNilai ?? 10) == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ ($perPageNilai ?? 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="50" {{ ($perPageNilai ?? 10) == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ ($perPageNilai ?? 10) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
                <div class="w-full sm:w-auto">
                    <button type="submit" class="bg-sekunder hover:bg-sekunder/90 text-white px-5 py-2 rounded-md text-sm font-medium transition-colors shadow-sm w-full">
                        Filter
                    </button>
                </div>
            </form>

            @if($selectedSubjectId && $subjectType)
                <!-- Global Metrics Nilai -->
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <div class="bg-primer/5 border border-primer/10 p-4 rounded-md flex items-center justify-between max-w-sm mb-6">
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Rerata Nilai Kelas</p>
                            <h3 class="text-2xl font-bold text-primer">{{ $kelasRerataNilai ?? 0 }}{{ in_array($subjectType, ['fmd', 'wawancara']) ? '%' : '' }}</h3>
                        </div>
                        <div class="w-10 h-10 bg-primer/10 rounded-full flex items-center justify-center text-primer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                    </div>

                    <!-- Chart Performance -->
                    <div class="border border-gray-100 p-4 rounded-md mb-6" x-data="chartComponent()">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-sm font-medium text-gray-700">Grafik Performa Nilai</h3>
                            <div class="flex gap-2">
                                <button @click="toggleDataset('nilai')" :class="{'bg-primer text-white': showNilai, 'bg-gray-100 text-gray-500': !showNilai}" class="px-3 py-1 text-xs rounded transition-colors font-medium">Nilai</button>
                            </div>
                        </div>
                        <div class="h-64 w-full">
                            <canvas id="performanceChart"></canvas>
                        </div>
                    </div>

                    <!-- Search Bar + Actions -->
                    <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                        <form method="GET" action="{{ route('sensei.penilaian') }}" class="flex items-center gap-2 w-full sm:w-auto">
                            <input type="hidden" name="kelas_id" value="{{ $selectedKelasId }}">
                            <input type="hidden" name="subject_id" value="{{ $selectedSubjectId }}">
                            <input type="hidden" name="bulan" value="{{ $selectedBulan }}">
                            <input type="hidden" name="tahun" value="{{ $selectedTahun }}">
                            <div class="relative flex-1 sm:w-64">
                                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                <input type="text" name="search" value="{{ $searchQuery ?? '' }}" placeholder="Cari nama siswa..." class="w-full text-sm border border-gray-300 rounded-md pl-9 pr-3 py-2.5 outline-none focus:border-primer focus:ring-1 focus:ring-primer">
                            </div>
                            <button type="submit" class="bg-sekunder hover:bg-sekunder/90 text-white px-4 py-2.5 rounded-md text-sm font-medium transition-colors">Cari</button>
                            @if($searchQuery)
                                <a href="{{ route('sensei.penilaian', ['kelas_id' => $selectedKelasId, 'subject_id' => $selectedSubjectId, 'bulan' => $selectedBulan, 'tahun' => $selectedTahun]) }}" class="text-xs text-sekunder hover:underline">Reset</a>
                            @endif
                        </form>
                        <div class="flex items-center gap-2 mb-3">
                            @if($subjectType)
                                <button onclick="printPdfTable('{{ strtoupper($subjectType) }}')" class="px-4 py-2.5 bg-sekunder text-white rounded text-sm hover:bg-sekunder/90 flex items-center justify-center gap-1 font-medium w-full sm:w-auto">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    <span class="hidden sm:inline">Cetak / PDF</span>
                                </button>
                                <button @click="showExamModal = true" class="px-4 py-2.5 bg-sekunder text-white rounded text-sm hover:bg-sekunder/90 flex items-center justify-center gap-1 font-medium w-full sm:w-auto">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    <span class="hidden sm:inline">Tambah Evaluasi</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- ============================================================ --}}
            {{-- ============================================================ --}}
            {{-- MAIN CONTENT: TABLES PER SUBJECT TYPE --}}
            {{-- ============================================================ --}}

            @if(($siswasNilai instanceof \Illuminate\Pagination\LengthAwarePaginator ? $siswasNilai->total() : $siswasNilai->count()) > 0 && $subjectType)

                @if(in_array($subjectType, ['bunpou', 'kanji', 'kotoba']))
                    @include('sensei.penilaian.partials.tabel-bkk', ['evaluasis' => $evaluasis, 'siswas' => $siswasNilai, 'subjectType' => $subjectType])
                @endif

                @if($subjectType === 'fmd')
                    @include('sensei.penilaian.partials.tabel-fmd', ['evaluasis' => $evaluasis, 'siswas' => $siswasNilai, 'kelasRerataNilai' => $kelasRerataNilai, 'subjectType' => $subjectType])
                @endif

                @if($subjectType === 'wawancara')
                    @include('sensei.penilaian.partials.tabel-wawancara', ['evaluasis' => $evaluasis, 'siswas' => $siswasNilai, 'kelasRerataNilai' => $kelasRerataNilai, 'subjectType' => $subjectType])
                @endif

                {{-- Pagination --}}
                @if($siswasNilai instanceof \Illuminate\Pagination\LengthAwarePaginator && $siswasNilai->hasPages())
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        {{ $siswasNilai->links() }}
                    </div>
                @endif

            @elseif($selectedSubjectId)
                <div class="mt-6 p-10 text-center border border-gray-100 rounded-md bg-gray-50">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <h3 class="text-md font-medium text-primer mb-1">Tidak Ditemukan</h3>
                    <p class="text-sm text-gray-500">Siswa dengan nama "<strong>{{ $searchQuery }}</strong>" tidak ditemukan di kelas ini.</p>
                </div>
            @endif
        </div> <!-- Close Filter Penilaian Mata Pelajaran div unconditionally -->

    @if(!$selectedSubjectId)
        <div class="bg-white rounded-md border border-gray-100 shadow-sm p-16 text-center mb-6">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            <h3 class="text-lg font-medium text-primer mb-1">Pilih Filter Data</h3>
            <p class="text-sm text-gray-500">Pilih Kelas dan Mata Pelajaran pada filter di atas untuk melihat rekapitulasi penilaian.</p>
        </div>
    @endif
    
    @include('sensei.penilaian.partials.tabel-nilai-akhir')
    
    @endif

    {{-- ============================================================ --}}
    {{-- MODAL: BUAT EVALUASI BARU --}}
    {{-- ============================================================ --}}
    @if($subjectType)
    <template x-teleport="body">
        <div x-show="showExamModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div x-show="showExamModal" @click="showExamModal = false" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-900/75" aria-hidden="true"></div>
                <div x-show="showExamModal" x-transition.scale.origin.bottom class="inline-block w-full max-w-lg p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-xl relative z-10">
                    <h3 class="text-lg font-medium text-primer mb-4">Buat Evaluasi Baru — <span class="uppercase">{{ $subjectType }}</span></h3>
                    <form action="{{ route('sensei.penilaian.evaluasi.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="kelas_id" value="{{ $selectedKelasId }}">
                        <input type="hidden" name="subject_type" value="{{ $subjectType }}">
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                                    <input type="text" name="judul" class="w-full border border-gray-300 rounded-md py-2.5 px-3 focus:ring-primer focus:border-primer" placeholder="Contoh: Bab 1 / Eva 1 / Minggu 1" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                    <select name="kategori" class="w-full border border-gray-300 rounded-md py-2.5 px-3 focus:ring-primer focus:border-primer" required>
                                        <option value="Formatif">Formatif (Harian/Bab)</option>
                                        <option value="Sumatif">Sumatif (Akhir Bulan)</option>
                                        <option value="Ujian Akhir">Ujian Akhir</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-md py-2.5 px-3 focus:ring-primer focus:border-primer" required>
                                </div>
                            </div>
                            @if($subjectType !== 'wawancara')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">KKM (%)</label>
                                <input type="number" name="kkm" value="75" min="0" max="100" class="w-full border border-gray-300 rounded-md py-2.5 px-3 focus:ring-primer focus:border-primer" required>
                            </div>
                            @else
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">KKM Materi (%)</label>
                                    <input type="number" name="kkm_materi" value="75" min="0" max="100" class="w-full border border-gray-300 rounded-md py-2.5 px-3 focus:ring-primer focus:border-primer">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">KKM Sikap (%)</label>
                                    <input type="number" name="kkm_sikap" value="75" min="0" max="100" class="w-full border border-gray-300 rounded-md py-2.5 px-3 focus:ring-primer focus:border-primer">
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="flex justify-end gap-2 mt-6">
                            <button type="button" @click="showExamModal = false" class="px-4 py-2.5 text-sm text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200">Batal</button>
                            <button type="submit" class="px-4 py-2.5 text-sm text-white bg-sekunder rounded-md hover:bg-sekunder/90">Buat Evaluasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>

    {{-- ============================================================ --}}
    {{-- MODAL: EDIT EVALUASI --}}
    {{-- ============================================================ --}}
    <template x-teleport="body">
        <div x-show="showEditExamModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div x-show="showEditExamModal" @click="showEditExamModal = false" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-900/75" aria-hidden="true"></div>
                <div x-show="showEditExamModal" x-transition.scale.origin.bottom class="inline-block w-full max-w-lg p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-xl relative z-10">
                    <h3 class="text-lg font-medium text-primer mb-4">Edit Evaluasi — <span class="uppercase">{{ $subjectType }}</span></h3>
                    <form :action="`/sensei/penilaian/evaluasi/${editExamType}/${editExamData.id}`" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4 text-left">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori / Topik</label>
                                <input type="text" name="kategori" x-model="editExamData.kategori" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-primer focus:ring-1 focus:ring-primer" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Evaluasi</label>
                                <input type="text" name="judul" x-model="editExamData.judul" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-primer focus:ring-1 focus:ring-primer" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                <input type="date" name="tanggal" x-model="editExamData.tanggal" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-primer focus:ring-1 focus:ring-primer" required>
                            </div>
                            <template x-if="['bunpou', 'kanji', 'kotoba', 'fmd'].includes(editExamType)">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">KKM (Kriteria Ketuntasan Minimal)</label>
                                    <input type="number" name="kkm" x-model="editExamData.kkm" min="0" max="100" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-primer focus:ring-1 focus:ring-primer" required>
                                </div>
                            </template>
                        </div>
                        <div class="flex justify-end gap-2 mt-6">
                            <button type="button" @click="showEditExamModal = false" class="px-4 py-2.5 text-sm text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200 font-medium">Batal</button>
                            <button type="submit" class="px-4 py-2.5 text-sm text-white bg-sekunder rounded-md hover:bg-sekunder/90 font-medium">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>

    {{-- ============================================================ --}}
    {{-- MODAL: INPUT NILAI --}}
    {{-- ============================================================ --}}
    <template x-teleport="body">
        <div x-show="showScoreModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 py-10 text-center">
                <div x-show="showScoreModal" @click="showScoreModal = false" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-900/75" aria-hidden="true"></div>
                <div x-show="showScoreModal" x-transition.scale.origin.bottom class="w-full p-0 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-xl relative z-10" :class="subjectType === 'wawancara' ? 'max-w-6xl' : (subjectType === 'fmd' ? 'max-w-5xl' : 'max-w-3xl')">
                    <div class="p-5 bg-primer text-white flex justify-between items-center rounded-t-xl">
                        <div>
                            <h3 class="text-lg font-medium" x-text="'Input Nilai: ' + (currentEval ? currentEval.judul : '')"></h3>
                            <p class="text-white/80 text-sm" x-text="currentEval ? (subjectType === 'wawancara' ? '' : 'KKM: ' + (currentEval.kkm || currentEval.kkm_materi) + '%') : ''"></p>
                        </div>
                        <button @click="showScoreModal = false" class="text-white/70 hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="p-4 md:p-6 pb-2">
                        <!-- Search and Pagination Controls -->
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50 p-3 rounded-lg border border-gray-100">
                            <div class="w-full sm:w-1/3">
                                <input type="text" x-model="modalSearch" placeholder="Cari nama siswa..." class="w-full border border-gray-300 rounded-md py-2 px-3 text-sm focus:ring-primer focus:border-primer">
                            </div>
                            <div class="flex items-center gap-3 text-sm w-full sm:w-auto justify-end">
                                <label class="text-gray-600">Tampil:</label>
                                <select x-model="modalPerPage" @change="modalCurrentPage = 1" class="border border-gray-300 rounded-md py-1.5 px-3 focus:ring-primer focus:border-primer bg-white">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                    </div>
                    
                    <div class="p-0 overflow-y-auto overflow-x-auto max-h-[60vh] bg-white relative">
                        <form action="{{ route('sensei.penilaian.nilai.store') }}" method="POST" id="nilaiForm">
                            @csrf
                            <input type="hidden" name="subject_type" value="{{ $subjectType }}">
                            <input type="hidden" name="evaluasi_id" :value="currentEval ? currentEval.id : ''">

                            {{-- BKK --}}
                            <template x-if="subjectType === 'bunpou' || subjectType === 'kanji' || subjectType === 'kotoba'">
                                <table class="w-full text-sm text-left whitespace-nowrap min-w-max">
                                    <thead class="text-xs text-white uppercase bg-primer sticky top-0 z-10 shadow-sm">
                                        <tr>
                                            <th class="px-4 py-3 w-16">No</th>
                                            <th class="px-4 py-3">Nama Siswa</th>
                                            <th class="px-4 py-3 w-32 text-center">Nilai</th>
                                            <th class="px-4 py-3 w-20 text-center">Ket</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(siswa, index) in paginatedStudents" :key="siswa.id">
                                            <tr class="border-b">
                                                <td class="px-4 py-3 text-gray-500" x-text="index + 1"></td>
                                                <td class="px-4 py-3 font-medium text-primer">
                                                    <span x-text="siswa.nama"></span>
                                                    <input type="hidden" :name="'nilais['+index+'][siswa_id]'" :value="siswa.id">
                                                </td>
                                                <td class="px-4 py-3">
                                                    <input type="number" :name="'nilais['+index+'][nilai]'" x-model.number="siswa.nilai" min="0" max="100" class="w-full border border-gray-300 rounded px-2 py-1 focus:ring-primer focus:border-primer text-center font-bold">
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <template x-if="siswa.nilai !== '' && currentEval">
                                                        <span :class="siswa.nilai >= currentEval.kkm ? 'text-primer bg-primer/10 px-2 py-0.5 rounded text-xs font-bold' : 'text-sekunder bg-sekunder/10 px-2 py-0.5 rounded text-xs font-bold'" x-text="siswa.nilai >= currentEval.kkm ? 'L' : 'TL'"></span>
                                                    </template>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                    </table>
                                </div>
                            </template>

                            {{-- FMD --}}
                            <template x-if="subjectType === 'fmd'">
                                <table class="w-full text-sm text-left whitespace-nowrap min-w-max">
                                    <thead class="text-xs text-white uppercase bg-primer sticky top-0 z-10 shadow-sm">
                                        <tr>
                                            <th class="px-3 py-3">No</th>
                                            <th class="px-3 py-3">Nama Siswa</th>
                                            <th class="px-3 py-3 w-20 text-center">MTK</th>
                                            <th class="px-3 py-3 w-20 text-center">Lari</th>
                                            <th class="px-3 py-3 w-20 text-center">Push Up</th>
                                            <th class="px-3 py-3 w-20 text-center">Sit Up</th>
                                            <th class="px-3 py-3 w-24 text-center">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(siswa, index) in paginatedStudents" :key="siswa.id">
                                            <tr class="border-b">
                                                <td class="px-3 py-3 text-gray-500" x-text="index + 1"></td>
                                                <td class="px-3 py-3 font-medium text-primer">
                                                    <span x-text="siswa.nama"></span>
                                                    <input type="hidden" :name="'nilais['+index+'][siswa_id]'" :value="siswa.id">
                                                </td>
                                                <td class="px-3 py-3"><input type="number" :name="'nilais['+index+'][skor_mtk]'" x-model.number="siswa.skor_mtk" min="0" class="w-full border border-gray-300 rounded px-1 py-1 focus:ring-primer focus:border-primer text-center"></td>
                                                <td class="px-3 py-3"><input type="number" :name="'nilais['+index+'][skor_lari]'" x-model.number="siswa.skor_lari" min="0" class="w-full border border-gray-300 rounded px-1 py-1 focus:ring-primer focus:border-primer text-center"></td>
                                                <td class="px-3 py-3"><input type="number" :name="'nilais['+index+'][skor_push_up]'" x-model.number="siswa.skor_push_up" min="0" class="w-full border border-gray-300 rounded px-1 py-1 focus:ring-primer focus:border-primer text-center"></td>
                                                <td class="px-3 py-3"><input type="number" :name="'nilais['+index+'][skor_sit_up]'" x-model.number="siswa.skor_sit_up" min="0" class="w-full border border-gray-300 rounded px-1 py-1 focus:ring-primer focus:border-primer text-center"></td>
                                                <td class="px-3 py-3">
                                                    <select :name="'nilais['+index+'][ket]'" x-model="siswa.ket" class="w-full border border-gray-300 rounded px-1 py-1.5 focus:ring-primer focus:border-primer text-center text-xs font-bold" :class="siswa.ket === 'L' ? 'text-primer bg-primer/10' : (siswa.ket === 'TL' ? 'text-sekunder bg-sekunder/10' : '')">
                                                        <option value="">-Pilih-</option>
                                                        <option value="L">L</option>
                                                        <option value="TL">TL</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                    </table>
                                </div>
                            </template>

                            {{-- WAWANCARA --}}
                            <template x-if="subjectType === 'wawancara'">
                                <div>
                                    <div class="p-4 flex flex-wrap gap-2 sm:gap-6 text-xs font-bold uppercase text-gray-500 bg-white sticky left-0">
                                        <span class="bg-tersier/10 text-tersier px-3 py-1 rounded">Materi: Program, Umum, Jepang, Indonesia</span>
                                        <span class="bg-sekunder/10 text-sekunder px-3 py-1 rounded">Sikap: Cara Duduk, Suara, Fokus</span>
                                    </div>
                                    <table class="w-full text-sm text-left whitespace-nowrap min-w-max">
                                        <thead class="text-xs text-white uppercase bg-primer sticky top-0 z-10 shadow-sm">
                                            <tr>
                                                <th class="px-2 py-3">No</th>
                                                <th class="px-2 py-3">Nama</th>
                                                <th class="px-2 py-3 text-center bg-tersier w-14">Prog</th>
                                                <th class="px-2 py-3 text-center bg-tersier w-14">Umum</th>
                                                <th class="px-2 py-3 text-center bg-tersier w-14">Jpng</th>
                                                <th class="px-2 py-3 text-center bg-tersier w-14">Indo</th>
                                                <th class="px-2 py-3 text-center w-12">Σ</th>
                                                <th class="px-2 py-3 text-center bg-sekunder w-14">Duduk</th>
                                                <th class="px-2 py-3 text-center bg-sekunder w-14">Suara</th>
                                                <th class="px-2 py-3 text-center bg-sekunder w-14">Fokus</th>
                                                <th class="px-2 py-3 text-center w-12">Σ</th>
                                                <th class="px-2 py-3 text-center bg-gray-500 w-20">Ket</th>
                                                <th class="px-2 py-3 text-center bg-gray-500 w-32">Catatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="(siswa, index) in paginatedStudents" :key="siswa.id">
                                                <tr class="border-b">
                                                    <td class="px-2 py-2 text-gray-500" x-text="index + 1"></td>
                                                    <td class="px-2 py-2 font-medium text-primer text-xs">
                                                        <span x-text="siswa.nama"></span>
                                                        <input type="hidden" :name="'nilais['+index+'][siswa_id]'" :value="siswa.id">
                                                    </td>
                                                    <td class="px-1 py-2"><input type="number" :name="'nilais['+index+'][materi_program]'" x-model.number="siswa.materi_program" min="0" class="w-full border border-gray-300 rounded px-1 py-1 text-center text-xs focus:ring-primer focus:border-primer"></td>
                                                    <td class="px-1 py-2"><input type="number" :name="'nilais['+index+'][materi_umum]'" x-model.number="siswa.materi_umum" min="0" class="w-full border border-gray-300 rounded px-1 py-1 text-center text-xs focus:ring-primer focus:border-primer"></td>
                                                    <td class="px-1 py-2"><input type="number" :name="'nilais['+index+'][materi_jepang]'" x-model.number="siswa.materi_jepang" min="0" class="w-full border border-gray-300 rounded px-1 py-1 text-center text-xs focus:ring-primer focus:border-primer"></td>
                                                    <td class="px-1 py-2"><input type="number" :name="'nilais['+index+'][materi_indonesia]'" x-model.number="siswa.materi_indonesia" min="0" class="w-full border border-gray-300 rounded px-1 py-1 text-center text-xs focus:ring-primer focus:border-primer"></td>
                                                    <td class="px-1 py-2 text-center font-bold text-tersier text-xs" x-text="((+siswa.materi_program||0)+(+siswa.materi_umum||0)+(+siswa.materi_jepang||0)+(+siswa.materi_indonesia||0)) || '-'"></td>
                                                    <td class="px-1 py-2"><input type="number" :name="'nilais['+index+'][sikap_cara_duduk]'" x-model.number="siswa.sikap_cara_duduk" min="0" class="w-full border border-gray-300 rounded px-1 py-1 text-center text-xs focus:ring-primer focus:border-primer"></td>
                                                    <td class="px-1 py-2"><input type="number" :name="'nilais['+index+'][sikap_suara]'" x-model.number="siswa.sikap_suara" min="0" class="w-full border border-gray-300 rounded px-1 py-1 text-center text-xs focus:ring-primer focus:border-primer"></td>
                                                    <td class="px-1 py-2"><input type="number" :name="'nilais['+index+'][sikap_fokus]'" x-model.number="siswa.sikap_fokus" min="0" class="w-full border border-gray-300 rounded px-1 py-1 text-center text-xs focus:ring-primer focus:border-primer"></td>
                                                    <td class="px-1 py-2 text-center font-bold text-sekunder text-xs" x-text="((+siswa.sikap_cara_duduk||0)+(+siswa.sikap_suara||0)+(+siswa.sikap_fokus||0)) || '-'"></td>
                                                    <td class="px-1 py-2">
                                                        <select :name="'nilais['+index+'][ket]'" x-model="siswa.ket" class="w-full border border-gray-300 rounded px-0.5 py-1 text-center text-[11px] font-bold" :class="siswa.ket === 'Sangat Baik' || siswa.ket === 'Baik' ? 'text-primer bg-primer/10' : (siswa.ket === 'Sangat Kurang' ? 'text-sekunder bg-sekunder/10' : (siswa.ket === 'Kurang' ? 'text-sekunder bg-sekunder/10' : (siswa.ket === 'Cukup' ? 'text-tersier bg-tersier/10' : '')))">
                                                            <option value="">-Pilih-</option>
                                                            <option value="Sangat Baik">Sangat Baik</option>
                                                            <option value="Baik">Baik</option>
                                                            <option value="Cukup">Cukup</option>
                                                            <option value="Kurang">Kurang</option>
                                                            <option value="Sangat Kurang">Sangat Kurang</option>
                                                        </select>
                                                    </td>
                                                    <td class="px-1 py-2">
                                                        <input type="text" :name="'nilais['+index+'][catatan]'" x-model="siswa.catatan" class="w-full border border-gray-300 rounded px-1 py-1 text-xs focus:ring-primer focus:border-primer" placeholder="Opsional...">
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </template>
                        </form>

                    </div>

                    <div class="p-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4 rounded-b-xl">
                        <!-- Pagination Navigation for Modal -->
                        <div class="flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-600 w-full sm:w-auto" x-show="totalPages > 1">
                            <div>
                                Halaman <span x-text="modalCurrentPage" class="font-medium"></span> dari <span x-text="totalPages" class="font-medium"></span>
                            </div>
                            <div class="flex gap-1">
                                <button type="button" @click="if(modalCurrentPage > 1) modalCurrentPage--" class="px-4 py-2 border border-sekunder text-sekunder rounded font-medium bg-white hover:bg-sekunder hover:text-white transition-colors disabled:opacity-50" :disabled="modalCurrentPage === 1">&laquo; Prev</button>
                                <button type="button" @click="if(modalCurrentPage < totalPages) modalCurrentPage++" class="px-4 py-2 border border-sekunder text-sekunder rounded font-medium bg-white hover:bg-sekunder hover:text-white transition-colors disabled:opacity-50" :disabled="modalCurrentPage === totalPages">Next &raquo;</button>
                            </div>
                        </div>

                        <div class="flex gap-2 w-full sm:w-auto justify-end">
                            <button type="button" @click="showScoreModal = false" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 w-full sm:w-auto">Batal</button>
                            <button type="button" @click="document.getElementById('nilaiForm').submit()" class="px-5 py-2.5 text-sm font-medium text-white bg-sekunder rounded-md hover:bg-sekunder/90 shadow-sm w-full sm:w-auto">Simpan Semua Nilai</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
    @endif
    
    @include('sensei.penilaian.partials.modal-nilai-akhir')

</div>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.5s ease-out forwards;
    }
    .overflow-x-auto th.sticky, .overflow-x-auto td.sticky {
        box-shadow: 2px 0 5px -2px rgba(0,0,0,0.05);
    }
</style>

<script>
    function printPdfTable(subjectName) {
        let el = document.getElementById('printable-table');
        if (!el) return;
        
        let win = window.open('', '_blank');
        win.document.write('<html><head><title>Penilaian ' + subjectName + ' - LPK Saitama</title>');
        win.document.write('<style>');
        win.document.write('body{font-family:"Arial",sans-serif;font-size:12px;color:#333;margin:0;padding:20px;}');
        win.document.write('.header{text-align:center;border-bottom:3px solid #18345C;padding-bottom:10px;margin-bottom:20px;}');
        win.document.write('.header h1{color:#18345C;margin:0;font-size:24px;text-transform:uppercase;letter-spacing:1px;}');
        win.document.write('.header p{margin:5px 0 0;color:#666;font-size:13px;}');
        win.document.write('.info-section{margin-bottom:20px;display:flex;justify-content:space-between;font-size:13px;}');
        win.document.write('table{width:100%;border-collapse:collapse;margin-bottom:30px;}');
        win.document.write('th,td{border:1px solid #ddd;padding:8px;text-align:center;}');
        win.document.write('th{background-color:#18345C;color:#fff;font-size:12px;font-weight:bold;}');
        win.document.write('td{font-size:12px;}');
        win.document.write('tr:nth-child(even){background-color:#f9f9f9;}');
        win.document.write('.text-left{text-align:left;}');
        win.document.write('.font-bold{font-weight:700;}');
        win.document.write('.lulus{color:#18345C;font-weight:700;} .tidak-lulus{color:#D3575F;font-weight:700;}');
        win.document.write('.no-print{display:none!important;}');
        win.document.write('.signature{margin-top:40px;width:100%;display:table;}');
        win.document.write('.signature-box{display:table-cell;text-align:center;width:33%;}');
        win.document.write('.signature-box p{margin:0 0 60px 0;}');
        win.document.write('.signature-box span{border-top:1px solid #333;padding-top:5px;display:inline-block;width:150px;}');
        win.document.write('@media print{@page{size:landscape;margin:15mm;}}');
        win.document.write('</style></head><body>');
        
        win.document.write('<div class="header">');
        win.document.write('<h1>LPK SAITAMA</h1>');
        win.document.write('<p>Lembaga Pelatihan Kerja & Bahasa Jepang</p>');
        win.document.write('</div>');

        win.document.write('<div class="info-section">');
        win.document.write('<div><strong>Mata Pelajaran:</strong> ' + subjectName + '</div>');
        win.document.write('<div><strong>Tanggal Cetak:</strong> ' + new Date().toLocaleDateString('id-ID', {day:'numeric',month:'long',year:'numeric'}) + '</div>');
        win.document.write('</div>');

        win.document.write(el.outerHTML);

        win.document.write('<div class="signature">');
        win.document.write('<div class="signature-box"><p>Mengetahui,</p><span>Kepala LPK</span></div>');
        win.document.write('<div class="signature-box"></div>');
        win.document.write('<div class="signature-box"><p>Guru Pengajar,</p><span>.......................</span></div>');
        win.document.write('</div>');

        win.document.write('</body></html>');
        win.document.close();
        
        // Timeout to allow styles to load before printing
        setTimeout(() => {
            win.print();
        }, 500);
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function chartComponent() {
        return {
            chart: null,
            showNilai: true,
            showAbsensi: true,
            init() {
                const ctx = document.getElementById('performanceChart');
                if (!ctx) return;
                
                const labels = {!! json_encode($chartLabels ?? []) !!};
                const nilaiData = {!! json_encode($chartNilaiData ?? []) !!};
                const absensiData = {!! json_encode($chartAbsensiData ?? []) !!};
                
                this.chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Rerata Nilai',
                                data: nilaiData,
                                borderColor: '#18345C',
                                backgroundColor: '#18345C20',
                                yAxisID: 'y',
                                tension: 0.3,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        scales: {
                            y: {
                                type: 'linear',
                                display: true,
                                position: 'left',
                                suggestedMin: 0,
                                suggestedMax: 100,
                            }
                        }
                    }
                });
            },
            toggleDataset(type) {
                if (!this.chart) return;
                if (type === 'nilai') {
                    this.showNilai = !this.showNilai;
                    this.chart.setDatasetVisibility(0, this.showNilai);
                } else if (type === 'absensi') {
                    this.showAbsensi = !this.showAbsensi;
                    this.chart.setDatasetVisibility(1, this.showAbsensi);
                }
                this.chart.update();
            }
        }
    }
</script>

@endsection
