@extends('sensei.app')

@section('title', 'Beranda Sensei - LPK Saitama')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 md:space-y-8 animate-fade-in-up relative">
    
    <!-- Welcome Banner -->
    <div class="bg-primer rounded-md p-6 sm:p-8 text-white shadow-sm relative overflow-hidden group">
        <div class="relative z-10 flex flex-col justify-center h-full">
            <div class="flex flex-col md:flex-row gap-5 items-center md:items-start mb-2">
                <div class="w-16 h-16 bg-white/10 rounded-md flex-shrink-0 flex items-center justify-center border border-white/20">
                    <svg class="w-8 h-8 text-white/80" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
                <div class="flex-1 w-full text-center md:text-left">
                    <h1 class="text-2xl font-medium tracking-tight mb-1">Selamat Datang, Sensei {{ explode(' ', $sensei->nama_lengkap)[0] ?? 'Sensei' }}! 👋</h1>
                    <p class="text-white/80 text-sm max-w-lg mb-4">Pantau perkembangan kelas dan siswa yang Anda asuh. Jangan lupa untuk memperbarui nilai dan evaluasi tepat waktu.</p>
                    <div class="flex flex-wrap gap-2 justify-center md:justify-start">
                        <a href="{{ route('sensei.profil') }}" class="text-xs font-medium bg-white/10 hover:bg-white/20 border border-white/20 text-white px-3 py-1.5 rounded-md transition-colors flex items-center">
                            Profil Saya
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Decorative Shapes -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 opacity-50 transform translate-x-10 -translate-y-10 group-hover:translate-x-5 group-hover:-translate-y-5 transition-transform duration-700"></div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <!-- Stat Card 1 -->
        <div class="bg-white p-5 rounded-md border border-gray-100 shadow-sm hover:border-primer/30 transition-colors group">
            <div class="flex justify-between items-start mb-2">
                <div class="w-10 h-10 rounded-md bg-primer/10 text-primer flex items-center justify-center group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <span class="text-xs font-medium text-primer bg-primer/10 px-2 py-0.5 rounded-sm">Kelas</span>
            </div>
            <div class="flex items-end gap-2">
                <h3 class="text-2xl font-medium text-primer tracking-tight">{{ $totalKelas }}</h3>
                <span class="text-xs font-medium text-gray-400 mb-1">Kelas Aktif</span>
            </div>
        </div>
        
        <!-- Stat Card 2 -->
        <div class="bg-white p-5 rounded-md border border-gray-100 shadow-sm hover:border-tersier/30 transition-colors group">
            <div class="flex justify-between items-start mb-2">
                <div class="w-10 h-10 rounded-md bg-tersier/10 text-tersier flex items-center justify-center group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <span class="text-xs font-medium text-tersier bg-tersier/10 px-2 py-0.5 rounded-sm">Siswa</span>
            </div>
            <div class="flex items-end gap-2">
                <h3 class="text-2xl font-medium text-tersier tracking-tight">{{ $totalSiswa }}</h3>
                <span class="text-xs font-medium text-gray-400 mb-1">Total Siswa</span>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white p-5 rounded-md border border-gray-100 shadow-sm hover:border-sekunder/30 transition-colors group">
            <div class="flex justify-between items-start mb-2">
                <div class="w-10 h-10 rounded-md bg-sekunder/10 text-sekunder flex items-center justify-center group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <span class="text-xs font-medium text-sekunder bg-sekunder/10 px-2 py-0.5 rounded-sm">Penilaian</span>
            </div>
            <div class="flex items-end gap-2">
                <h3 class="text-2xl font-medium text-sekunder tracking-tight">-</h3>
                <span class="text-xs font-medium text-gray-400 mb-1">Butuh Dinilai</span>
            </div>
        </div>

        <!-- Stat Card 4 -->
        <div class="bg-white p-5 rounded-md border border-gray-100 shadow-sm hover:border-primer/30 transition-colors group">
            <div class="flex justify-between items-start mb-2">
                <div class="w-10 h-10 rounded-md bg-primer/10 text-primer flex items-center justify-center group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-0.5 rounded-sm">Evaluasi</span>
            </div>
            <div class="flex items-end gap-2">
                <h3 class="text-2xl font-medium text-primer tracking-tight">{{ collect($recentEvaluations)->count() }}</h3>
                <span class="text-xs font-medium text-gray-400 mb-1">Riwayat</span>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8 pb-4">
        
        <!-- Left: Agenda Mengajar Terdekat -->
        <div class="bg-white rounded-md border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-medium text-primer flex items-center">
                    <svg class="w-5 h-5 mr-2 text-sekunder" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Jadwal Mengajar Terdekat
                </h3>
            </div>

            <div class="space-y-4">
                <div class="border border-primer/20 bg-primer/5 p-4 rounded-md">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="text-sm font-medium text-primer">Kelas N4 A - Tata Bahasa</h4>
                        <span class="text-[10px] font-medium bg-sekunder text-white px-2 py-0.5 rounded-sm animate-pulse">Hari Ini</span>
                    </div>
                    <div class="flex items-center text-xs font-medium text-gray-600 mb-4">
                        <svg class="w-3.5 h-3.5 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        08:00 - 10:00 WIB
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Evaluasi Terakhir -->
        <div class="bg-white rounded-md border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-medium text-primer flex items-center">
                    <svg class="w-5 h-5 mr-2 text-tersier" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Evaluasi Terakhir Diberikan
                </h3>
                <a href="{{ route('sensei.evaluasi') }}" class="text-[10px] uppercase font-medium text-primer hover:underline">Lihat Semua</a>
            </div>

            <div class="space-y-3">
                @forelse($recentEvaluations as $eval)
                    <div class="flex flex-col p-3 bg-gray-50 border border-gray-100 rounded-md">
                        <div class="flex justify-between mb-1">
                            <span class="text-xs font-medium text-gray-800">{{ $eval->judul }}</span>
                            <span class="text-[10px] text-gray-500">{{ \Carbon\Carbon::parse($eval->tanggal_evaluasi)->format('d M Y') }}</span>
                        </div>
                        <span class="text-xs text-gray-500 truncate">{{ $eval->deskripsi }}</span>
                    </div>
                @empty
                    <div class="text-center py-6 text-sm text-gray-500">Belum ada evaluasi terbaru.</div>
                @endforelse
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