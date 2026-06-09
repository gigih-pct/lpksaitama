@extends('siswa.app')

@section('title', 'Pembelajaran - LPK Saitama')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 md:space-y-8 animate-fade-in-up">
    <!-- Header: Data Diri Lengkap Siswa -->
    <div class="bg-white rounded-md border border-gray-100 shadow-sm p-6 flex flex-col md:flex-row gap-6 items-center md:items-start relative overflow-hidden">
        <div class="absolute right-0 top-0 w-32 h-32 bg-primer/5 transform translate-x-10 -translate-y-10 rotate-45"></div>
        <div class="absolute bottom-0 right-10 w-24 h-24 bg-tersier/5 transform translate-y-10 rotate-45"></div>
        
        <div class="relative shrink-0">
            <div class="w-24 h-24 md:w-32 md:h-32 bg-primer p-1 shadow-sm rounded-md">
                <div class="w-full h-full bg-white flex items-center justify-center overflow-hidden rounded-md border-2 border-white">
                    <svg class="w-12 h-12 md:w-16 md:h-16 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
            </div>
            <div class="absolute -bottom-2 -right-2 bg-sekunder text-white text-[10px] font-medium px-2 py-0.5 rounded-sm border border-white">Aktif</div>
        </div>
        
        <div class="flex-1 text-center md:text-left z-10 w-full">
            <h1 class="text-2xl md:text-3xl font-medium text-primer tracking-tight mb-1">{{ $siswa->nama_lengkap }}</h1>
            <p class="text-gray-500 font-medium text-sm md:text-base mb-4">Siswa Program Bahasa Jepang - {{ $siswa->kelas?->nama_kelas ?? 'Belum Ditentukan' }}</p>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 border-t border-gray-100 pt-4 text-left">
                <div>
                    <p class="text-xs text-gray-400 font-medium mb-0.5">Nomor Induk</p>
                    <p class="text-sm text-gray-800 font-medium">{{ $siswa->nomor_induk ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-medium mb-0.5">Kelas</p>
                    <p class="text-sm text-gray-800 font-medium">{{ $siswa->kelas?->nama_kelas ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-medium mb-0.5">Periode</p>
                    <p class="text-sm text-gray-800 font-medium">{{ \App\Models\Batch::find($siswa->kelas?->batch_id)?->tahun ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-medium mb-0.5">Wali Kelas</p>
                    <p class="text-sm text-gray-800 font-medium">Sensei Pendamping</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Banner Pengumuman Landscape -->
    <div class="bg-primer rounded-md p-4 sm:p-6 text-white shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/10 flex items-center justify-center rounded-md shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </div>
            <div>
                <h3 class="font-medium text-lg">Pendaftaran Ujian JFT-Basic Gelombang 3</h3>
                <p class="text-white/80 text-sm mt-0.5">Pendaftaran ditutup pada tanggal 20 Oktober 2026. Segera daftarkan dirimu!</p>
            </div>
        </div>
        <a href="#" class="bg-white text-primer px-4 py-2 rounded-md font-medium text-sm hover:bg-gray-50 active:scale-95 transition-all whitespace-nowrap border border-transparent shadow-sm">
            Daftar Sekarang
        </a>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
        
        <!-- Left Column: Absensi, Jadwal & Kalender -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Absensi Harian -->
            <div class="bg-white rounded-md border border-gray-100 shadow-sm p-5" x-data="{ showAbsenModal: false, activeTab: 'hadir' }">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-primer flex items-center">
                        <svg class="w-5 h-5 mr-2 text-sekunder" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Absensi Kelas
                    </h3>
                </div>
                
                <div class="bg-gray-50 border border-gray-200 rounded-md p-4 text-center">
                    <p class="text-sm text-gray-500 mb-1">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                    
                    @if(isset($activeSessions) && $activeSessions->count() > 0)
                        <div class="mb-4">
                            <span class="inline-block bg-sekunder/10 text-sekunder text-xs font-medium px-2 py-1 rounded-md border border-sekunder/20">
                                {{ $activeSessions->count() }} Sesi Aktif
                            </span>
                        </div>
                        <button @click="showAbsenModal = true" class="w-full bg-primer text-white py-2.5 rounded-md font-medium text-sm hover:bg-primer/90 transition-colors shadow-sm">
                            Buka Form Absensi
                        </button>
                    @else
                        <div class="py-4">
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2 text-gray-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <p class="text-sm text-gray-500 font-medium">Tidak ada sesi kelas yang aktif saat ini.</p>
                        </div>
                    @endif
                </div>

                <!-- Modal Absensi -->
                <template x-teleport="body">
                <div x-show="showAbsenModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                        <div x-show="showAbsenModal" @click="showAbsenModal = false" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-900/75" aria-hidden="true"></div>

                        <div x-show="showAbsenModal" x-transition.scale.origin.bottom class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-xl relative z-10">
                            <div class="flex justify-between items-center mb-5 pb-4 border-b border-gray-100">
                                <h3 class="text-lg font-medium text-primer leading-6" id="modal-title">Form Kehadiran Siswa</h3>
                                <button @click="showAbsenModal = false" class="text-gray-400 hover:text-gray-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>

                            <!-- Tabs -->
                            <div class="flex border-b border-gray-200 mb-6">
                                <button @click="activeTab = 'hadir'" :class="{'border-primer text-primer': activeTab === 'hadir', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'hadir'}" class="flex-1 py-2 text-sm font-medium border-b-2 text-center transition-colors">
                                    Hadir di Kelas
                                </button>
                                <button @click="activeTab = 'izin'" :class="{'border-sekunder text-sekunder': activeTab === 'izin', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'izin'}" class="flex-1 py-2 text-sm font-medium border-b-2 text-center transition-colors">
                                    Pengajuan Izin / Sakit
                                </button>
                            </div>

                            <!-- Tab: Hadir (Input Kode) -->
                            <div x-show="activeTab === 'hadir'" x-transition.opacity>
                                <form action="{{ route('siswa.absensi.hadir') }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode Kehadiran (6 Digit)</label>
                                        <p class="text-xs text-gray-500 mb-3">Masukkan kode absen yang ditampilkan oleh Sensei di layar depan kelas.</p>
                                        <input type="text" name="kode_absen" maxlength="6" class="w-full text-center text-3xl tracking-widest font-bold border border-gray-300 rounded-md shadow-sm py-3 px-4 focus:ring-primer focus:border-primer" placeholder="------" required>
                                    </div>
                                    <button type="submit" class="w-full bg-primer text-white px-4 py-3 rounded-md font-medium text-sm hover:bg-primer/90 transition-colors">
                                        Konfirmasi Kehadiran
                                    </button>
                                </form>
                            </div>

                            <!-- Tab: Izin/Sakit -->
                            <div x-show="activeTab === 'izin'" style="display: none;" x-transition.opacity>
                                <form action="{{ route('siswa.absensi.izin') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Sesi Kelas</label>
                                            <select name="attendance_session_id" required class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-sekunder focus:border-sekunder text-sm">
                                                <option value="">-- Pilih Sesi --</option>
                                                @if(isset($activeSessions))
                                                    @foreach($activeSessions as $session)
                                                        <option value="{{ $session->id }}">{{ $session->jadwal->kegiatan }} - {{ $session->jadwal->hari }}, Sesi {{ \Carbon\Carbon::parse($session->jadwal->jam_mulai)->format('H:i') }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                            <div class="grid grid-cols-2 gap-3">
                                                <label class="border border-gray-200 rounded-md p-3 flex items-center cursor-pointer hover:bg-gray-50">
                                                    <input type="radio" name="status" value="Izin" required class="text-sekunder focus:ring-sekunder mr-2">
                                                    <span class="text-sm font-medium">Izin</span>
                                                </label>
                                                <label class="border border-gray-200 rounded-md p-3 flex items-center cursor-pointer hover:bg-gray-50">
                                                    <input type="radio" name="status" value="Sakit" required class="text-sekunder focus:ring-sekunder mr-2">
                                                    <span class="text-sm font-medium">Sakit</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Lengkap</label>
                                            <textarea name="alasan" rows="2" required class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-sekunder focus:border-sekunder text-sm" placeholder="Jelaskan alasan ketidakhadiran Anda..."></textarea>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Foto / Surat (Wajib)</label>
                                            <input type="file" name="bukti_foto" accept="image/*" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-sekunder/10 file:text-sekunder hover:file:bg-sekunder/20">
                                        </div>
                                    </div>
                                    <button type="submit" class="w-full mt-6 bg-sekunder text-white px-4 py-3 rounded-md font-medium text-sm hover:bg-sekunder/90 transition-colors">
                                        Kirim Pengajuan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                </template>
            </div>

            <!-- Jadwal Total -->
            <div class="bg-white rounded-md border border-gray-100 shadow-sm p-5">
                <h3 class="text-lg font-medium text-primer mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-tersier" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Jadwal Total
                </h3>
                
                <div class="space-y-3">
                    @foreach($jadwals as $jadwal)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md border border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-primer rounded-sm"></div>
                            <span class="text-sm font-medium text-gray-700">{{ $jadwal->hari }}</span>
                            <span class="text-xs text-gray-500">{{ $jadwal->kegiatan }}</span>
                        </div>
                        <span class="text-xs font-medium bg-white px-2 py-1 rounded-md border border-gray-200">{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</span>
                    </div>
                    @endforeach
                    @if($jadwals->isEmpty())
                    <p class="text-sm text-gray-500 text-center py-4">Belum ada jadwal tersimpan.</p>
                    @endif
                </div>
            </div>

            <!-- Kalender Akademik (Mini) -->
            <div class="bg-white rounded-md border border-gray-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-primer flex items-center">
                        <svg class="w-5 h-5 mr-2 text-sekunder" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Kalender Akademik
                    </h3>
                    <button class="text-primer hover:bg-primer/5 p-1 rounded-md transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center justify-center w-12 h-12 bg-primer/5 border border-primer/20 rounded-md shrink-0">
                            <span class="text-[10px] text-primer font-medium">Okt</span>
                            <span class="text-lg font-medium text-primer leading-none">15</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Ujian Tengah Semester</h4>
                            <p class="text-xs text-gray-500 mt-0.5">Wajib hadir untuk seluruh siswa N4</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center justify-center w-12 h-12 bg-sekunder/5 border border-sekunder/20 rounded-md shrink-0">
                            <span class="text-[10px] text-sekunder font-medium">Nov</span>
                            <span class="text-lg font-medium text-sekunder leading-none">01</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Libur Nasional</h4>
                            <p class="text-xs text-gray-500 mt-0.5">Tidak ada kegiatan belajar mengajar</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center justify-center w-12 h-12 bg-gray-50 border border-gray-200 rounded-md shrink-0">
                            <span class="text-[10px] text-gray-500 font-medium">Des</span>
                            <span class="text-lg font-medium text-gray-700 leading-none">20</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Simulasi JFT-Basic</h4>
                            <p class="text-xs text-gray-500 mt-0.5">Persiapan akhir sebelum ujian sesungguhnya</p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('siswa.kalender') }}" class="block w-full mt-4 text-center text-sm font-medium text-primer border border-primer hover:bg-primer hover:text-white transition-colors py-2 rounded-md">
                    Lihat Kalender Penuh
                </a>
            </div>
        </div>

        <!-- Right Column: Materi & Submission -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Materi -->
            <div class="bg-white rounded-md border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-medium text-primer flex items-center">
                        <svg class="w-5 h-5 mr-2 text-tersier" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477-4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        Materi Terkini
                    </h3>
                    <a href="#" class="text-sm font-medium text-primer hover:underline">Semua Materi</a>
                </div>

                <div class="grid gap-4">
                    @forelse($modules as $module)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-white border border-gray-200 hover:border-primer/30 transition-colors rounded-md group">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-tersier/10 text-tersier rounded-md flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 group-hover:text-primer transition-colors">{{ $module->judul }}</h4>
                                <p class="text-xs text-gray-500 mt-0.5">Diunggah pada {{ $module->created_at->format('d M Y') }} • {{ $module->subject->nama_mata_pelajaran ?? '-' }}</p>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $module->file_path) }}" target="_blank" class="mt-3 sm:mt-0 w-full sm:w-auto bg-gray-50 hover:bg-gray-100 text-gray-700 px-4 py-2 rounded-md text-xs font-medium border border-gray-200 transition-colors inline-flex justify-center items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Unduh / Lihat
                        </a>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 p-4 text-center">Belum ada materi pembelajaran.</p>
                    @endforelse
                </div>
            </div>

            <!-- Submission (Tugas) -->
            <div x-data="{ showTugasModal: false }" class="bg-white rounded-md border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-medium text-primer flex items-center">
                        <svg class="w-5 h-5 mr-2 text-sekunder" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        Tugas & Submission
                    </h3>
                </div>

                <div class="space-y-4">
                    <!-- Tombol Pengumpulan -->
                    <div class="border border-sekunder/30 bg-sekunder/5 p-4 rounded-md">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="text-sm font-medium text-gray-900">Formulir Pengumpulan Tugas</h4>
                            <span class="text-[10px] font-medium bg-sekunder/10 text-sekunder px-2 py-0.5 rounded-sm border border-sekunder/20">Wajib</span>
                        </div>
                        <p class="text-xs text-gray-600 mb-3">Kumpulkan file tugas Anda di sini. Gambar otomatis dikonversi ke WebP.</p>
                        
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mt-4 border-t border-sekunder/10 pt-3">
                            <button @click="showTugasModal = true" class="bg-primer hover:bg-primer/90 text-white px-4 py-2 rounded-md text-xs font-medium transition-colors w-full sm:w-auto text-center">
                                Buka Form Pengumpulan
                            </button>
                        </div>
                    </div>

                    <!-- Tugas Selesai -->
                    @foreach($submissions as $sub)
                    <div class="border border-gray-200 bg-gray-50 p-4 rounded-md opacity-80">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="text-sm font-medium text-gray-900">{{ $sub->judul_tugas }}</h4>
                            <span class="text-[10px] font-medium bg-green-100 text-green-700 px-2 py-0.5 rounded-sm border border-green-200 flex items-center">
                                <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Terkirim
                            </span>
                        </div>
                        <div class="flex items-center text-xs font-medium text-gray-500 mt-2">
                            {{ $sub->created_at->format('d M Y, H:i') }} • {{ strtoupper($sub->file_type) }}
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Modal Form Tugas -->
                <template x-teleport="body">
                    <div x-show="showTugasModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
                        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                            <div x-show="showTugasModal" @click="showTugasModal = false" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-900/75" aria-hidden="true"></div>
                            
                            <div x-show="showTugasModal" x-transition.scale.origin.bottom class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-xl relative z-10">
                                <div class="flex justify-between items-center mb-5 pb-4 border-b border-gray-100">
                                    <h3 class="text-lg font-medium text-primer leading-6">Formulir Tugas</h3>
                                    <button @click="showTugasModal = false" class="text-gray-400 hover:text-gray-500">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                                <form action="{{ route('siswa.pembelajaran.tugas') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Tugas</label>
                                            <input type="text" name="judul_tugas" required class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-sekunder focus:border-sekunder text-sm" placeholder="Contoh: Tugas Terjemahan Bab 12">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Bukti File</label>
                                            <p class="text-[10px] text-gray-500 mb-2">Ekstensi yang didukung: PDF, DOC, DOCX, PNG, JPG, JPEG (Max 5MB).</p>
                                            <input type="file" name="bukti_tugas" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-sekunder/10 file:text-sekunder hover:file:bg-sekunder/20">
                                        </div>
                                    </div>
                                    <button type="submit" class="w-full mt-6 bg-sekunder text-white px-4 py-3 rounded-md font-medium text-sm hover:bg-sekunder/90 transition-colors">
                                        Kirim Tugas
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

        </div>
    </div>
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
