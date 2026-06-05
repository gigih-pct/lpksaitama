@extends('sensei.app')

@section('title', 'Pembelajaran & Modul - LPK Saitama')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 animate-fade-in-up" x-data="{ tab: 'jadwal', showMateriModal: false }">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
        <div>
            <h1 class="text-2xl font-medium text-primer tracking-tight">Pembelajaran</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola sesi kelas (absensi) dan materi pengajaran untuk kelas Anda.</p>
        </div>
        <div>
            <button @click="$dispatch('open-add-modal', { tab: 'materi' })" class="bg-sekunder hover:bg-sekunder/90 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors flex items-center shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Modul / Jadwal
            </button>
        </div>
    </div>

    <!-- Kalender Akademik -->
    <div class="bg-white p-5 rounded-md border border-gray-100 shadow-sm mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-medium text-primer">Kalender Akademik</h2>
            <p class="text-xs text-gray-500">Klik tanggal untuk menambah Jadwal / Materi baru.</p>
        </div>
        <div id="pembelajaranCalendar" class="w-full h-[500px]"></div>
    </div>

    <!-- Tabs -->
    <div class="flex space-x-4 border-b border-gray-200">
        <button @click="tab = 'jadwal'" :class="{'border-primer text-primer': tab === 'jadwal', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'jadwal'}" class="py-2 px-1 border-b-2 font-medium text-sm transition-colors">
            Jadwal & Absensi (Hari Ini)
        </button>
        <button @click="tab = 'materi'" :class="{'border-primer text-primer': tab === 'materi', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'materi'}" class="py-2 px-1 border-b-2 font-medium text-sm transition-colors">
            Modul & Materi
        </button>
    </div>

    <!-- Filter/Search -->
    <div class="bg-white p-5 rounded-md border border-gray-100 shadow-sm">
        <form method="GET" action="{{ route('sensei.pembelajaran') }}" class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="w-full sm:w-1/3">
                <label class="block text-xs font-medium text-gray-700 mb-1">Filter Kelas</label>
                <select name="kelas_id" class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 outline-none focus:border-primer focus:ring-1 focus:ring-primer bg-white">
                    <option value="">Semua Kelas</option>
                    @foreach($kelases as $kelas)
                        <option value="{{ $kelas->id }}" {{ ($selectedKelasId ?? '') == $kelas->id ? 'selected' : '' }}>
                            Kelas {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:w-1/3">
                <label class="block text-xs font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                <select name="subject_id" class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 outline-none focus:border-primer focus:ring-1 focus:ring-primer bg-white">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ ($selectedSubjectId ?? '') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->nama_pelajaran }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="bg-primer hover:bg-primer/90 text-white px-5 py-2 rounded-md text-sm font-medium transition-colors shadow-sm w-full sm:w-auto">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Tab Jadwal -->
    <div x-show="tab === 'jadwal'" class="space-y-4">
        @php
            $hariIni = \Carbon\Carbon::now()->locale('id')->dayName;
            $jadwalHariIni = collect($jadwals)->where('hari', $hariIni);
        @endphp

        @if($jadwalHariIni->isEmpty())
            <div class="bg-white rounded-md border border-gray-100 shadow-sm p-12 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak Ada Jadwal Hari Ini</h3>
                <p class="text-sm text-gray-500 mb-4">Anda tidak memiliki jadwal mengajar pada hari {{ $hariIni }}.</p>
            </div>
        @else
            @foreach($jadwalHariIni as $index => $jadwal)
                <div class="bg-white rounded-md border border-gray-100 shadow-sm p-5 hover:shadow transition-shadow group flex items-start gap-4">
                    
                    <!-- Waktu -->
                    <div class="shrink-0 pt-1">
                        <div class="w-12 h-12 rounded-full bg-primer/10 text-primer flex items-center justify-center font-bold text-xs border border-primer/20 text-center leading-tight">
                            {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}<br>WIB
                        </div>
                    </div>

                    <!-- Konten -->
                    <div class="flex-1">
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2 mb-2">
                            <div>
                                <h3 class="text-lg font-medium text-primer">{{ $jadwal->kegiatan }}</h3>
                                <div class="text-xs text-gray-500 flex items-center gap-2 mt-1">
                                    <span class="bg-gray-100 px-2 py-0.5 rounded text-gray-600">
                                        Kelas: {{ $jadwal->kelas->nama_kelas ?? '-' }}
                                    </span>
                                    <span class="text-gray-400">&bull;</span>
                                    <span>Selesai: {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }} WIB</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3 pt-3 border-t border-gray-100" x-data="{ showWaktuModal: false }">
                            <button @click="showWaktuModal = true" class="text-xs bg-primer hover:bg-primer/90 text-white px-3 py-1.5 rounded flex items-center transition-colors">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Buka Sesi Absensi
                            </button>

                            <!-- Cek jika ada sesi aktif untuk jadwal ini -->
                            @php
                                $sessionAktif = \App\Models\AttendanceSession::where('jadwal_id', $jadwal->id)
                                    ->where('tanggal', now()->toDateString())
                                    ->latest()->first();
                            @endphp
                            @if($sessionAktif)
                                <a href="{{ route('sensei.pembelajaran.absensi.show', $sessionAktif->id) }}" class="text-xs bg-white border border-sekunder text-sekunder hover:bg-sekunder/5 px-3 py-1.5 rounded transition-colors flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Lihat Live Absensi
                                </a>
                            @endif

                            <!-- Modal Waktu Berakhir -->
                            <template x-teleport="body">
                            <div x-show="showWaktuModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
                                <div class="flex items-center justify-center min-h-screen px-4 text-center">
                                    <div x-show="showWaktuModal" @click="showWaktuModal = false" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-900/75" aria-hidden="true"></div>
                                    <div x-show="showWaktuModal" x-transition.scale.origin.bottom class="inline-block w-full max-w-sm p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-xl relative z-10">
                                        <h3 class="text-lg font-medium text-primer mb-2">Batas Waktu Absensi</h3>
                                        <p class="text-xs text-gray-500 mb-4">Tentukan jam berapa batas akhir siswa bisa melakukan absensi (lewat dari jam ini akan dianggap Alpa).</p>
                                        <form action="{{ route('sensei.pembelajaran.absensi.generate') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
                                            <input type="time" name="waktu_berakhir" value="{{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}" class="w-full border border-gray-300 rounded-md py-2 px-3 mb-4 focus:ring-primer focus:border-primer" required>
                                            <div class="flex justify-end gap-2">
                                                <button type="button" @click="showWaktuModal = false" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200">Batal</button>
                                                <button type="submit" class="px-4 py-2 text-sm text-white bg-primer rounded-md hover:bg-primer/90">Mulai Sesi</button>
                                            </div>
                                        </form>
                                        <form action="{{ route('sensei.pembelajaran.jadwal.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');" class="inline-block mt-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs bg-white border border-red-200 text-red-500 hover:text-white hover:bg-red-500 px-3 py-1.5 rounded transition-colors flex items-center">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                Hapus Jadwal
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            </template>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Tab Materi -->
    <div x-show="tab === 'materi'" class="space-y-4" style="display: none;">
        @forelse($modules as $module)
            <div class="bg-white rounded-md border border-gray-100 shadow-sm p-5 hover:shadow transition-shadow group flex items-start gap-4">
                <!-- Icon -->
                <div class="shrink-0 pt-1">
                    <div class="w-10 h-10 rounded-full bg-primer/10 text-primer flex items-center justify-center border border-primer/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                </div>

                <!-- Konten -->
                <div class="flex-1">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2 mb-2">
                        <div>
                            <h3 class="text-lg font-medium text-primer">{{ $module->judul }}</h3>
                            <div class="text-xs text-gray-500 flex items-center gap-2 mt-1">
                                <span class="bg-gray-100 px-2 py-0.5 rounded text-gray-600">
                                    Kelas: {{ $module->kelas->nama_kelas ?? '-' }}
                                </span>
                                <span class="bg-gray-100 px-2 py-0.5 rounded text-gray-600">
                                    Mapel: {{ $module->subject->nama_mata_pelajaran ?? '-' }}
                                </span>
                                <span class="text-gray-400">&bull;</span>
                                <span>{{ $module->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed mb-4">
                        {{ $module->deskripsi ?: 'Tidak ada deskripsi.' }}
                    </p>
                    <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
                        <a href="{{ asset('storage/' . $module->file_path) }}" target="_blank" class="text-xs bg-white border border-gray-200 text-gray-600 hover:text-primer hover:border-primer px-3 py-1.5 rounded transition-colors flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Lihat Dokumen
                        </a>
                        <form action="{{ route('sensei.pembelajaran.materi.destroy', $module->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?');" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs bg-white border border-red-200 text-red-500 hover:text-white hover:bg-red-500 px-3 py-1.5 rounded transition-colors flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-md border border-gray-100 shadow-sm p-12 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477-4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">Belum Ada Modul/Materi</h3>
                <p class="text-sm text-gray-500 mb-4">Anda belum mengunggah materi pembelajaran apapun.</p>
            </div>
        @endforelse
    </div>

    <!-- Modal Tambah Jadwal/Materi -->
    <template x-teleport="body">
        <div x-data="{ 
            open: false, 
            modalTab: 'jadwal', 
            selectedHari: 'Senin',
            selectedDate: '' 
        }"
        @open-add-modal.window="open = true; modalTab = $event.detail.tab || 'jadwal'; if($event.detail.hari) selectedHari = $event.detail.hari; if($event.detail.date) selectedDate = $event.detail.date;"
        x-show="open" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 py-10 text-center">
                <div x-show="open" @click="open = false" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-900/75" aria-hidden="true"></div>
                <div x-show="open" x-transition.scale.origin.bottom class="inline-block w-full max-w-lg p-0 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-xl relative z-10">
                    
                    <div class="p-5 bg-primer text-white flex justify-between items-center rounded-t-xl">
                        <h3 class="text-lg font-medium">Tambah Data Pembelajaran <span x-text="selectedDate ? '('+selectedDate+')' : ''" class="text-sm font-normal"></span></h3>
                        <button @click="open = false" type="button" class="text-white hover:text-gray-200 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="flex space-x-4 border-b border-gray-200 px-5 pt-3 bg-gray-50">
                        <button @click="modalTab = 'jadwal'" :class="{'border-primer text-primer border-b-2': modalTab === 'jadwal', 'border-transparent text-gray-500 hover:text-gray-700': modalTab !== 'jadwal'}" class="py-2 px-1 font-medium text-sm transition-colors border-b-2">
                            Jadwal Rutin
                        </button>
                        <button @click="modalTab = 'materi'" :class="{'border-primer text-primer border-b-2': modalTab === 'materi', 'border-transparent text-gray-500 hover:text-gray-700': modalTab !== 'materi'}" class="py-2 px-1 font-medium text-sm transition-colors border-b-2">
                            Modul / Materi
                        </button>
                    </div>

                    <div class="p-6 overflow-y-auto max-h-[60vh]">
                        <!-- Form Jadwal -->
                        <form x-show="modalTab === 'jadwal'" action="{{ route('sensei.pembelajaran.jadwal.store') }}" method="POST">
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
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                                        <select name="hari" x-model="selectedHari" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:ring-primer focus:border-primer" required>
                                            <option value="Senin">Senin</option>
                                            <option value="Selasa">Selasa</option>
                                            <option value="Rabu">Rabu</option>
                                            <option value="Kamis">Kamis</option>
                                            <option value="Jumat">Jumat</option>
                                            <option value="Sabtu">Sabtu</option>
                                            <option value="Minggu">Minggu</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Kegiatan</label>
                                        <input type="text" name="kegiatan" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:ring-primer focus:border-primer" required placeholder="Misal: Kelas Pagi">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                                        <input type="time" name="jam_mulai" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:ring-primer focus:border-primer" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                                        <input type="time" name="jam_selesai" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:ring-primer focus:border-primer" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="bg-primer hover:bg-primer/90 text-white px-6 py-2 rounded-md text-sm font-medium transition-colors shadow-sm">
                                    Simpan Jadwal
                                </button>
                            </div>
                        </form>

                        <!-- Form Materi -->
                        <form x-show="modalTab === 'materi'" action="{{ route('sensei.pembelajaran.materi.store') }}" method="POST" enctype="multipart/form-data" style="display: none;">
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
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Materi</label>
                                    <input type="text" name="judul" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:ring-primer focus:border-primer" required placeholder="Misal: Bab 1: Perkenalan">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat</label>
                                    <textarea name="deskripsi" rows="3" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:ring-primer focus:border-primer" placeholder="Tambahkan keterangan tentang materi ini..."></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Unggah File (PDF, DOC, dll)</label>
                                    <input type="file" name="file" class="w-full border border-gray-300 rounded-md py-1.5 px-3 focus:ring-primer focus:border-primer" required>
                                    <p class="text-xs text-gray-500 mt-1">Ukuran maksimal 10MB.</p>
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="bg-primer hover:bg-primer/90 text-white px-6 py-2 rounded-md text-sm font-medium transition-colors shadow-sm">
                                    Simpan Materi
                                </button>
                            </div>
                        </form>
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

@if(session('absensi_session_id'))
    @php $session = \App\Models\AttendanceSession::find(session('absensi_session_id')); @endphp
    @if($session)
    <!-- Modal Kode Absen Live Monitor -->
    <template x-teleport="body">
    <div x-data="{ 
            open: true, 
            attendances: [], 
            showFoto: false,
            fotoUrl: '',
            alasanText: '',
            fetchData() {
                fetch('{{ route('sensei.pembelajaran.absensi.live', $session->id) }}')
                    .then(res => res.json())
                    .then(data => {
                        this.attendances = data.attendances;
                    });
            },
            init() {
                this.fetchData();
                setInterval(() => this.fetchData(), 5000);
            }
        }" 
        x-show="open" 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm">
        
        <div @click.away="open = false" class="bg-white rounded-lg shadow-xl w-full max-w-5xl h-[80vh] flex overflow-hidden animate-fade-in-up relative">
            <button @click="open = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10 bg-white/50 rounded-full p-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <!-- Kiri: Kode Raksasa -->
            <div class="w-1/2 bg-primer p-8 text-center text-white flex flex-col items-center justify-center relative">
                <div class="mb-4">
                    <span class="bg-white/20 px-3 py-1 rounded-full text-xs font-medium border border-white/30 uppercase tracking-wider">Kode Absensi Kelas</span>
                </div>
                <div class="text-8xl font-bold tracking-[0.2em] font-mono mb-8 drop-shadow-lg">{{ $session->kode_absen }}</div>
                
                <div class="bg-black/20 p-4 rounded-xl backdrop-blur border border-white/10 w-full max-w-sm">
                    <div class="text-white/70 text-sm mb-1">Batas Waktu Absen</div>
                    <div class="text-2xl font-medium">{{ \Carbon\Carbon::parse($session->waktu_berakhir)->format('H:i') }} WIB</div>
                </div>
            </div>

            <!-- Kanan: Daftar Siswa Live -->
            <div class="w-1/2 bg-gray-50 flex flex-col h-full border-l border-gray-200">
                <div class="p-6 border-b border-gray-200 bg-white flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Live Monitor Absensi</h3>
                        <p class="text-xs text-gray-500 flex items-center mt-1">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                            Auto-refresh tiap 5 detik
                        </p>
                    </div>
                </div>
                
                <div class="flex-1 overflow-y-auto p-6">
                    <template x-if="attendances.length === 0">
                        <div class="text-center py-10">
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <p class="text-sm text-gray-500 font-medium">Belum ada siswa yang absen.</p>
                        </div>
                    </template>

                    <div class="space-y-3">
                        <template x-for="absen in attendances" :key="absen.id">
                            <div class="bg-white p-4 rounded-lg border shadow-sm flex items-center justify-between"
                                 :class="{
                                     'border-green-200 bg-green-50/30': absen.status === 'Hadir',
                                     'border-yellow-200 bg-yellow-50/30': absen.status === 'Izin' || absen.status === 'Sakit'
                                 }">
                                <div>
                                    <div class="font-medium text-gray-900" x-text="absen.siswa_nama"></div>
                                    <div class="text-xs text-gray-500 mt-0.5 flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span x-text="absen.waktu"></span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <!-- Status Badge -->
                                    <span class="px-2.5 py-1 rounded text-xs font-medium"
                                          :class="{
                                              'bg-green-100 text-green-700': absen.status === 'Hadir',
                                              'bg-yellow-100 text-yellow-700': absen.status === 'Izin' || absen.status === 'Sakit'
                                          }" x-text="absen.status">
                                    </span>
                                    
                                    <!-- Tombol Bukti Izin/Sakit -->
                                    <template x-if="(absen.status === 'Izin' || absen.status === 'Sakit') && absen.bukti_foto_url">
                                        <button @click="showFoto = true; fotoUrl = absen.bukti_foto_url; alasanText = absen.alasan;" class="text-sekunder hover:bg-sekunder/10 p-1.5 rounded-md transition-colors" title="Lihat Surat/Keterangan">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            
            <!-- Modal Overlay untuk Foto Izin/Sakit -->
            <div x-show="showFoto" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/80 backdrop-blur-sm" style="display: none;">
                <div @click.away="showFoto = false" class="bg-white rounded-lg p-1 w-full max-w-lg relative">
                    <button @click="showFoto = false" class="absolute top-3 right-3 text-white bg-black/50 hover:bg-black/70 rounded-full p-1 transition-colors z-10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                    <img :src="fotoUrl" class="w-full h-auto max-h-[60vh] object-contain rounded-t-lg bg-gray-100">
                    <div class="p-4 bg-white rounded-b-lg border-t border-gray-100">
                        <h4 class="text-sm font-medium text-gray-900 mb-1">Keterangan:</h4>
                        <p class="text-sm text-gray-600" x-text="alasanText"></p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </template>
    @endif
@endif

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('pembelajaranCalendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: '100%',
            locale: 'id',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            },
            dateClick: function(info) {
                let dateObj = new Date(info.dateStr);
                let days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                let dayName = days[dateObj.getDay()];
                let formattedDate = info.dateStr; 
                window.dispatchEvent(new CustomEvent('open-add-modal', { detail: { tab: 'jadwal', hari: dayName, date: formattedDate } }));
            },
            events: [
                @php
                    $dayMap = [
                        'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4,
                        'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 0
                    ];
                @endphp
                @foreach($jadwals as $jadwal)
                {
                    title: '{{ $jadwal->kegiatan }}',
                    daysOfWeek: [ {{ $dayMap[$jadwal->hari] ?? 1 }} ],
                    startTime: '{{ $jadwal->jam_mulai }}',
                    endTime: '{{ $jadwal->jam_selesai }}',
                    color: '#18345C'
                },
                @endforeach
            ]
        });
        calendar.render();
    });
</script>
@endsection
