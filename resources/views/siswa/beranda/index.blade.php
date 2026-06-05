@extends('siswa.app')

@section('title', 'Beranda - LPK Saitama')

@section('content')
<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div x-data="{ showReqDocModal: false }" class="max-w-6xl mx-auto space-y-6 md:space-y-8 animate-fade-in-up relative">
    <!-- Welcome Banner & Roadmap Keberangkatan -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Welcome Banner -->
        <div class="lg:col-span-2 bg-primer rounded-md p-6 sm:p-8 text-white shadow-sm relative overflow-hidden group">
            <div class="relative z-10 flex flex-col justify-center h-full">
                <!-- Biodata Ringkas -->
                <div class="flex flex-col md:flex-row gap-5 items-start mb-6 border-b border-white/10 pb-6">
                    <div class="w-16 h-16 bg-white/10 rounded-md flex-shrink-0 flex items-center justify-center border border-white/20">
                        <svg class="w-8 h-8 text-white/80" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                    <div class="flex-1 w-full">
                        <div class="flex flex-wrap items-center gap-3 mb-3">
                            <h1 class="text-xl sm:text-2xl font-medium tracking-tight">{{ $siswa->nama_lengkap ?? 'Nama Lengkap Siswa' }}</h1>
                            <span class="text-[10px] bg-sekunder text-white px-2 py-0.5 rounded-sm font-medium border border-sekunder">Kelas {{ $siswa->kelas->nama_kelas ?? 'Belum Ditentukan' }}</span>
                        </div>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-4 gap-y-3 text-xs text-white/80 mb-4">
                            <div><span class="text-white/50 block text-[10px] mb-0.5 uppercase tracking-wide">No. Induk</span>{{ $siswa->nomor_induk ?? '-' }}</div>
                            <div><span class="text-white/50 block text-[10px] mb-0.5 uppercase tracking-wide">No. Handphone</span>{{ $siswa->no_hp_siswa ?? '-' }}</div>
                            <div><span class="text-white/50 block text-[10px] mb-0.5 uppercase tracking-wide">No. HP Orang Tua</span>{{ $siswa->no_hp_orangtua ?? '-' }}</div>
                            <div class="col-span-2 sm:col-span-3"><span class="text-white/50 block text-[10px] mb-0.5 uppercase tracking-wide">Alamat Domisili</span>{{ $siswa->alamat ?? '-' }}</div>
                        </div>
                        
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('siswa.profil') }}" class="text-xs font-medium bg-white/10 hover:bg-white/20 border border-white/20 text-white px-3 py-1.5 rounded-md transition-colors flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                Perbarui Profil
                            </a>
                            <button @click="showReqDocModal = true" class="text-xs font-medium bg-sekunder hover:bg-sekunder/90 border border-sekunder text-white px-3 py-1.5 rounded-md transition-colors flex items-center shadow-sm">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Request Dokumen LPK
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Roadmap Healthbar -->
                <div class="mt-auto">
                    <div class="flex justify-between items-end mb-2">
                        <span class="text-sm font-medium text-white">Progress Keberangkatan</span>
                        <span class="text-xs font-medium text-white/80 bg-white/10 px-2 py-0.5 rounded-sm">Tahap: Persiapan JFT</span>
                    </div>
                    
                    <div class="relative pt-2 pb-6">
                        <!-- Markers / Checkpoints -->
                        <div class="absolute w-full top-0 flex justify-between z-20 px-1">
                            <!-- N5 (Done) -->
                            <div class="flex flex-col items-center group/tooltip relative">
                                <div class="w-3 h-3 bg-white rounded-sm border-2 border-primer shadow-[0_0_8px_rgba(255,255,255,0.8)]"></div>
                                <span class="absolute top-5 text-[10px] font-medium text-white whitespace-nowrap">Lulus N5</span>
                            </div>
                            <!-- N4 / JFT (Current) -->
                            <div class="flex flex-col items-center group/tooltip relative">
                                <div class="w-4 h-4 bg-tersier rounded-sm border-2 border-white shadow-[0_0_12px_rgba(0,153,217,0.8)] animate-pulse"></div>
                                <span class="absolute top-5 text-[10px] font-medium text-white whitespace-nowrap">Ujian JFT</span>
                            </div>
                            <!-- Wawancara User (Locked) -->
                            <div class="flex flex-col items-center group/tooltip relative">
                                <div class="w-3 h-3 bg-white/30 rounded-sm border-2 border-white/50"></div>
                                <span class="absolute top-5 text-[10px] font-medium text-white/50 whitespace-nowrap">Wawancara</span>
                            </div>
                            <!-- MCU & Dokumen (Locked) -->
                            <div class="flex flex-col items-center group/tooltip relative">
                                <div class="w-3 h-3 bg-white/30 rounded-sm border-2 border-white/50"></div>
                                <span class="absolute top-5 text-[10px] font-medium text-white/50 whitespace-nowrap">MCU & Dokumen</span>
                            </div>
                            <!-- Berangkat (Locked) -->
                            <div class="flex flex-col items-center group/tooltip relative">
                                <div class="w-3 h-3 bg-white/30 rounded-sm border-2 border-white/50"></div>
                                <span class="absolute top-5 text-[10px] font-medium text-white/50 whitespace-nowrap">Berangkat 🇯🇵</span>
                            </div>
                        </div>
                        
                        <!-- Healthbar Track -->
                        <div class="absolute top-1 left-0 w-full h-1.5 bg-white/20 rounded-sm overflow-hidden z-10">
                            <!-- Healthbar Fill -->
                            <div class="h-full bg-tersier shadow-[0_0_10px_rgba(0,153,217,0.8)] transition-all duration-1000 w-[35%] relative">
                                <div class="absolute top-0 right-0 bottom-0 left-0 bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.2)_25%,rgba(255,255,255,0.2)_50%,transparent_50%,transparent_75%,rgba(255,255,255,0.2)_75%,rgba(255,255,255,0.2)_100%)] bg-[length:20px_20px] animate-[progress-stripe_2s_linear_infinite]"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Decorative Shapes -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 opacity-50 transform translate-x-10 -translate-y-10 group-hover:translate-x-5 group-hover:-translate-y-5 transition-transform duration-700"></div>
        </div>

        <!-- Banner Promosi / Info Cepat -->
        <div class="bg-sekunder rounded-md p-6 text-white shadow-sm flex flex-col justify-center relative overflow-hidden group">
            <div class="relative z-10">
                <span class="inline-block px-2 py-0.5 bg-white/20 text-[10px] font-medium rounded-sm mb-3 uppercase tracking-wider">Pengumuman</span>
                @if($announcements->count() > 0)
                    <h3 class="font-medium text-lg mb-2 leading-snug">{{ $announcements->first()->judul }}</h3>
                    <p class="text-white/80 text-xs mb-4">{{ Str::limit($announcements->first()->isi, 60) }}</p>
                    <a href="{{ route('siswa.informasi') }}" class="inline-flex items-center text-xs font-medium bg-white text-sekunder px-4 py-2 rounded-md hover:bg-gray-50 transition-colors shadow-sm">
                        Lihat Semua Info
                        <svg class="w-3.5 h-3.5 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                @else
                    <h3 class="font-medium text-lg mb-2 leading-snug">Tidak Ada Pengumuman Terbaru</h3>
                    <p class="text-white/80 text-xs mb-4">Saat ini tidak ada informasi baru untuk Anda.</p>
                @endif
            </div>
            <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-white/10 rounded-full blur-2xl transform group-hover:scale-110 transition-transform duration-500"></div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <!-- Stat Card 1 -->
        <div class="bg-white p-5 rounded-md border border-gray-100 shadow-sm hover:border-primer/30 transition-colors group">
            <div class="flex justify-between items-start mb-2">
                <div class="w-10 h-10 rounded-md bg-primer/10 text-primer flex items-center justify-center group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <span class="text-xs font-medium text-primer bg-primer/10 px-2 py-0.5 rounded-sm">Modul</span>
            </div>
            <div class="flex items-end gap-2">
                <h3 class="text-2xl font-medium text-primer tracking-tight">12</h3>
                <span class="text-xs font-medium text-gray-400 mb-1">/ 24 Selesai</span>
            </div>
        </div>
        
        <!-- Stat Card 2 -->
        <div class="bg-white p-5 rounded-md border border-gray-100 shadow-sm hover:border-tersier/30 transition-colors group">
            <div class="flex justify-between items-start mb-2">
                <div class="w-10 h-10 rounded-md bg-tersier/10 text-tersier flex items-center justify-center group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-xs font-medium text-tersier bg-tersier/10 px-2 py-0.5 rounded-sm">+2.5%</span>
            </div>
            <div class="flex items-end gap-2">
                <h3 class="text-2xl font-medium text-tersier tracking-tight">85.5</h3>
                <span class="text-xs font-medium text-gray-400 mb-1">Rata-rata Nilai</span>
            </div>
        </div>
        
        <!-- Stat Card 3 -->
        <div class="bg-white p-5 rounded-md border border-gray-100 shadow-sm hover:border-primer/30 transition-colors group">
            <div class="flex justify-between items-start mb-2">
                <div class="w-10 h-10 rounded-md bg-primer/10 text-primer flex items-center justify-center group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-0.5 rounded-sm">Absensi</span>
            </div>
            <div class="flex items-end gap-2">
                <h3 class="text-2xl font-medium text-primer tracking-tight">98%</h3>
                <span class="text-xs font-medium text-gray-400 mb-1">Kehadiran</span>
            </div>
        </div>
        
        <!-- Stat Card 4 -->
        <div class="bg-white p-5 rounded-md border border-gray-100 shadow-sm hover:border-sekunder/30 transition-colors group">
            <div class="flex justify-between items-start mb-2">
                <div class="w-10 h-10 rounded-md bg-sekunder/10 text-sekunder flex items-center justify-center group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-xs font-medium text-sekunder bg-sekunder/10 px-2 py-0.5 rounded-sm">Sisa Waktu</span>
            </div>
            <div class="flex items-end gap-2">
                <h3 class="text-2xl font-medium text-sekunder tracking-tight">3</h3>
                <span class="text-xs font-medium text-gray-400 mb-1">Bulan</span>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8 pb-4">
        
        <!-- Left/Center Column: Grafik & Kalender -->
        <div class="lg:col-span-2 space-y-6 md:space-y-8">
            
            <!-- Grafik Performa (Nilai & Absensi) -->
            <div class="bg-white rounded-md border border-gray-100 shadow-sm p-6" x-data="{ filter: 'sebulan' }">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <div>
                        <h2 class="text-lg font-medium text-primer flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primer" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                            Grafik Performa Siswa
                        </h2>
                        <p class="text-xs text-gray-500 mt-1">Gabungan rata-rata nilai evaluasi dan rasio kehadiran harian.</p>
                    </div>
                    
                    <!-- Filter -->
                    <div class="flex bg-gray-50 p-1 rounded-md border border-gray-200 self-stretch sm:self-auto">
                        <button @click="filter = 'kemarin'" :class="{'bg-white shadow-sm border-gray-200 text-primer': filter === 'kemarin', 'border-transparent text-gray-500 hover:text-gray-700': filter !== 'kemarin'}" class="px-3 py-1.5 text-xs font-medium rounded-sm border transition-all flex-1 sm:flex-none text-center">Kemarin</button>
                        <button @click="filter = 'today'" :class="{'bg-white shadow-sm border-gray-200 text-primer': filter === 'today', 'border-transparent text-gray-500 hover:text-gray-700': filter !== 'today'}" class="px-3 py-1.5 text-xs font-medium rounded-sm border transition-all flex-1 sm:flex-none text-center">Hari Ini</button>
                        <button @click="filter = 'sebulan'" :class="{'bg-white shadow-sm border-gray-200 text-primer': filter === 'sebulan', 'border-transparent text-gray-500 hover:text-gray-700': filter !== 'sebulan'}" class="px-3 py-1.5 text-xs font-medium rounded-sm border transition-all flex-1 sm:flex-none text-center">1 Bulan</button>
                        <button @click="filter = 'kalender'" :class="{'bg-white shadow-sm border-gray-200 text-primer': filter === 'kalender', 'border-transparent text-gray-500 hover:text-gray-700': filter !== 'kalender'}" class="px-3 py-1.5 text-xs font-medium rounded-sm border transition-all flex-1 sm:flex-none flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Pilih
                        </button>
                    </div>
                </div>

                <div class="h-[280px] w-full">
                    <canvas id="mainPerformanceChart"></canvas>
                </div>
            </div>

            <!-- Jadwal Hari Ini -->
            <div class="bg-white rounded-md border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-medium text-primer flex items-center">
                        <svg class="w-5 h-5 mr-2 text-sekunder" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Jadwal Hari Ini
                    </h3>
                    <a href="{{ route('siswa.kalender') }}" class="text-xs font-medium text-primer bg-primer/5 hover:bg-primer/10 px-3 py-1.5 rounded-md transition-colors">Lihat Semua</a>
                </div>

                @if($jadwals->count() > 0)
                <div class="grid grid-cols-1 gap-4">
                    @foreach($jadwals as $jadwal)
                    <div class="border border-primer/20 bg-primer/5 p-4 rounded-md flex justify-between items-center">
                        <div>
                            <h4 class="text-sm font-medium text-primer">{{ $jadwal->kegiatan }}</h4>
                            <div class="flex items-center text-xs font-medium text-gray-600 mt-1">
                                <svg class="w-3.5 h-3.5 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }} WIB
                            </div>
                        </div>
                        <span class="text-[10px] font-medium bg-sekunder text-white px-2 py-0.5 rounded-sm animate-pulse">Berlangsung</span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="p-6 text-center border-2 border-dashed border-gray-100 rounded-md">
                    <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <p class="text-sm font-medium text-gray-500">Tidak ada jadwal hari ini.</p>
                </div>
                @endif
            </div>
            
        </div>

        <!-- Right Column: Berkas & Pembayaran -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Berkas Status -->
            <div class="bg-white rounded-md border border-gray-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-medium text-primer flex items-center">
                        <svg class="w-5 h-5 mr-2 text-tersier" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Kelengkapan Berkas
                    </h3>
                    <a href="{{ route('siswa.berkas') }}" class="text-[10px] uppercase font-medium text-primer hover:underline">Detail</a>
                </div>
                
                <div class="mb-4">
                    <div class="flex justify-between text-xs font-medium mb-1.5">
                        <span class="text-gray-700">Progress Dokumen</span>
                        <span class="text-primer">80%</span>
                    </div>
                    <div class="w-full bg-gray-100 h-2 rounded-sm overflow-hidden">
                        <div class="bg-primer h-full" style="width: 80%"></div>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between p-2.5 bg-gray-50 border border-gray-100 rounded-md">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span class="text-xs font-medium text-gray-700">KTP & KK</span>
                        </div>
                        <span class="text-[10px] font-medium bg-green-100 text-green-700 px-2 py-0.5 rounded-sm">Valid</span>
                    </div>
                    <div class="flex items-center justify-between p-2.5 bg-gray-50 border border-gray-100 rounded-md">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span class="text-xs font-medium text-gray-700">Ijazah Terakhir</span>
                        </div>
                        <span class="text-[10px] font-medium bg-green-100 text-green-700 px-2 py-0.5 rounded-sm">Valid</span>
                    </div>
                    <div class="flex items-center justify-between p-2.5 bg-sekunder/5 border border-sekunder/20 rounded-md">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-sekunder" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-xs font-medium text-gray-800">Paspor (Jika Ada)</span>
                        </div>
                        <span class="text-[10px] font-medium bg-sekunder text-white px-2 py-0.5 rounded-sm">Belum Ada</span>
                    </div>
                </div>
            </div>

            <!-- Pembayaran Status -->
            <div class="bg-white rounded-md border border-gray-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-medium text-primer flex items-center">
                        <svg class="w-5 h-5 mr-2 text-primer" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        Info Pembayaran
                    </h3>
                    <a href="{{ route('siswa.pembayaran') }}" class="text-[10px] uppercase font-medium text-primer hover:underline">Tagihan</a>
                </div>
                
                <div class="bg-primer p-4 rounded-md text-white relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-16 h-16 bg-white/10 rounded-full transform translate-x-4 -translate-y-4"></div>
                    
                    <p class="text-xs text-white/80 font-medium mb-1">Tagihan Belum Lunas</p>
                    @if($invoices->count() > 0)
                        <h4 class="text-xl font-medium tracking-tight mb-3">Rp {{ number_format($invoices->sum('nominal'), 0, ',', '.') }}</h4>
                        <div class="flex items-center justify-between border-t border-white/20 pt-3 mt-1">
                            <span class="text-[10px] font-medium">Jatuh Tempo Terdekat: {{ $invoices->first()->jatuh_tempo ? \Carbon\Carbon::parse($invoices->first()->jatuh_tempo)->format('d M Y') : '-' }}</span>
                            <a href="{{ route('siswa.pembayaran') }}" class="text-xs font-medium bg-white text-primer px-3 py-1.5 rounded-sm hover:bg-gray-100 transition-colors">Bayar</a>
                        </div>
                    @else
                        <h4 class="text-xl font-medium tracking-tight mb-3">Rp 0</h4>
                        <div class="flex items-center justify-between border-t border-white/20 pt-3 mt-1">
                            <span class="text-[10px] font-medium">Tidak ada tagihan tertunggak</span>
                        </div>
                    @endif
                </div>
                
                @if($siswa->invoices()->where('status', 'Lunas')->count() > 0)
                <div class="mt-3 flex items-center p-3 bg-green-50 border border-green-100 rounded-md">
                    <svg class="w-4 h-4 text-green-500 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-xs font-medium text-green-800">Tagihan terakhir telah Lunas.</span>
                </div>
                @endif
            </div>

        </div>
        
    </div>

    <!-- Request Document Modal -->
    <div x-cloak x-show="showReqDocModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            
            <!-- Background Overlay -->
            <div x-show="showReqDocModal" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm transition-opacity" 
                 @click="showReqDocModal = false"
                 aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Panel -->
            <div x-show="showReqDocModal" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="inline-block align-bottom bg-white rounded-md text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-100">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-md bg-primer/10 sm:mx-0 sm:h-10 sm:w-10 border border-primer/20">
                            <svg class="h-5 w-5 text-primer" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-primer" id="modal-title">Formulir Request Dokumen LPK</h3>
                            <div class="mt-2">
                                <p class="text-xs text-gray-500 mb-4">Isi detail di bawah ini untuk mengajukan permintaan surat pengantar atau dokumen resmi dari LPK Saitama.</p>
                                
                                <form action="#" method="POST" class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Nama Lengkap (Sesuai KTP)</label>
                                        <input type="text" class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 outline-none focus:border-primer focus:ring-1 focus:ring-primer transition-colors" value="{{ $siswa->nama_lengkap }}" readonly>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Tempat Lahir</label>
                                            <input type="text" class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 outline-none focus:border-primer focus:ring-1 focus:ring-primer transition-colors" placeholder="Contoh: Sleman">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                                            <input type="date" class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 outline-none focus:border-primer focus:ring-1 focus:ring-primer transition-colors">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Nomor Induk Kependudukan (NIK)</label>
                                        <input type="number" class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 outline-none focus:border-primer focus:ring-1 focus:ring-primer transition-colors" placeholder="16 Digit NIK KTP">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Alamat Lengkap (Sesuai KTP)</label>
                                        <textarea rows="2" class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 outline-none focus:border-primer focus:ring-1 focus:ring-primer transition-colors" placeholder="Masukkan alamat lengkap RT/RW, Desa, Kecamatan"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Lokasi LPK / Cabang</label>
                                        <select class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 outline-none focus:border-primer focus:ring-1 focus:ring-primer transition-colors bg-white">
                                            <option>LPK Saitama Pusat (Yogyakarta)</option>
                                            <option>LPK Saitama Cabang Magelang</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Dokumen yang Direquest</label>
                                        <select class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 outline-none focus:border-primer focus:ring-1 focus:ring-primer transition-colors bg-white">
                                            <option>Surat Keterangan Aktif Belajar</option>
                                            <option>Surat Pengantar Pembuatan Paspor</option>
                                            <option>Surat Pengantar MCU</option>
                                            <option>Legalisir Sertifikat Kelulusan</option>
                                            <option>Lainnya</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Keperluan / Keterangan Tambahan</label>
                                        <textarea rows="2" class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 outline-none focus:border-primer focus:ring-1 focus:ring-primer transition-colors" placeholder="Tuliskan tujuan pembuatan dokumen (Misal: Untuk syarat administrasi paspor di Kanim)"></textarea>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                    <button type="button" @click="showReqDocModal = false" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primer text-base font-medium text-white hover:bg-primer/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primer sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Kirim Request
                    </button>
                    <button type="button" @click="showReqDocModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primer sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Batal
                    </button>
                </div>
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
    @keyframes progress-stripe {
        0% { background-position: 0 0; }
        100% { background-position: 40px 0; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('mainPerformanceChart').getContext('2d');
        
        // Setup Combo Chart (Bar for Absensi, Line for Nilai)
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
                datasets: [
                    {
                        type: 'line',
                        label: 'Nilai Rata-rata',
                        data: [80, 85, 82, 88],
                        borderColor: '#0099D9', // tersier
                        backgroundColor: '#0099D9',
                        borderWidth: 2,
                        pointBackgroundColor: '#0099D9',
                        pointBorderColor: '#fff',
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        tension: 0.3,
                        yAxisID: 'y'
                    },
                    {
                        type: 'bar',
                        label: 'Kehadiran (%)',
                        data: [100, 95, 100, 100],
                        backgroundColor: 'rgba(24, 52, 92, 0.8)', // primer
                        borderRadius: 4,
                        barPercentage: 0.5,
                        yAxisID: 'y1'
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
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            font: { family: 'Poppins', size: 11 },
                            color: '#6b7280'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#18345C',
                        padding: 10,
                        titleFont: { family: 'Poppins', size: 13 },
                        bodyFont: { family: 'Poppins', size: 12 },
                        cornerRadius: 4,
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Nilai (0-100)',
                            font: { family: 'Poppins', size: 10 },
                            color: '#9ca3af'
                        },
                        min: 50,
                        max: 100,
                        grid: {
                            color: '#f3f4f6',
                            drawBorder: false,
                        },
                        ticks: {
                            font: { family: 'Poppins', size: 11 },
                            color: '#6b7280'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Kehadiran (%)',
                            font: { family: 'Poppins', size: 10 },
                            color: '#9ca3af'
                        },
                        min: 50,
                        max: 100,
                        grid: {
                            drawOnChartArea: false, // only draw grid lines for one axis
                            drawBorder: false,
                        },
                        ticks: {
                            font: { family: 'Poppins', size: 11 },
                            color: '#6b7280'
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            font: { family: 'Poppins', size: 11 },
                            color: '#6b7280'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection