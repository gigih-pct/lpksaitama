@extends('sensei.app')

@section('title', 'Penilaian Siswa - LPK Saitama')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 animate-fade-in-up">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
        <div>
            <h1 class="text-2xl font-medium text-primer tracking-tight">Penilaian Siswa</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola dan input nilai untuk siswa di kelas Anda.</p>
        </div>
        <div>
            <button class="bg-sekunder hover:bg-sekunder/90 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors flex items-center shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Input Nilai Baru
            </button>
        </div>
    </div>

    <!-- Filter/Search -->
    <div class="bg-white p-4 rounded-md border border-gray-100 shadow-sm flex flex-col sm:flex-row gap-4 items-center justify-between">
        <div class="flex gap-2 w-full sm:w-auto">
            <select class="text-sm border border-gray-300 rounded-md px-3 py-2 outline-none focus:border-primer focus:ring-1 focus:ring-primer bg-white min-w-[150px]">
                <option value="">Semua Kelas</option>
                @foreach($sensei->classes as $kelas)
                    <option value="{{ $kelas->id }}">Kelas {{ $kelas->nama_kelas }}</option>
                @endforeach
            </select>
            <select class="text-sm border border-gray-300 rounded-md px-3 py-2 outline-none focus:border-primer focus:ring-1 focus:ring-primer bg-white min-w-[150px]">
                <option value="">Semua Materi Pelajaran</option>
                @foreach($curriculums as $curriculum)
                    <option value="{{ $curriculum->id }}">{{ $curriculum->judul }}</option>
                @endforeach
            </select>
        </div>
        <div class="relative w-full sm:w-64">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" class="w-full text-sm border border-gray-300 rounded-md pl-10 pr-3 py-2 outline-none focus:border-primer focus:ring-1 focus:ring-primer" placeholder="Cari nama siswa...">
        </div>
    </div>

    <div class="bg-white rounded-md border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50 border-b border-gray-100 text-gray-600 font-medium">
                    <tr>
                        <th class="px-6 py-4">Nama Siswa</th>
                        <th class="px-6 py-4">No. Induk</th>
                        <th class="px-6 py-4">Kelas</th>
                        <th class="px-6 py-4">Rata-rata Nilai</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($siswas as $siswa)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-primer">{{ $siswa->nama_lengkap }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $siswa->nomor_induk }}</td>
                            <td class="px-6 py-4">
                                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded-sm text-xs border border-gray-200">
                                    {{ $siswa->kelas->nama_kelas ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $avg = $siswa->grades->avg('nilai');
                                @endphp
                                @if($avg)
                                    <span class="{{ $avg >= 80 ? 'text-green-600' : ($avg >= 60 ? 'text-yellow-600' : 'text-red-600') }} font-medium">
                                        {{ number_format($avg, 1) }}
                                    </span>
                                @else
                                    <span class="text-gray-400 italic">Belum ada nilai</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button class="text-sekunder hover:text-sekunder/80 text-xs font-medium px-3 py-1.5 border border-sekunder rounded-md hover:bg-sekunder/5 transition-colors">
                                    Detail Nilai
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p>Tidak ada siswa ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Placeholder -->
        <div class="bg-gray-50 px-6 py-3 border-t border-gray-100 flex items-center justify-between">
            <span class="text-xs text-gray-500">Menampilkan 1 hingga {{ count($siswas) }} dari {{ count($siswas) }} siswa</span>
            <div class="flex gap-1">
                <button class="px-3 py-1 bg-white border border-gray-200 text-gray-400 rounded-sm text-xs cursor-not-allowed">Sebelumnya</button>
                <button class="px-3 py-1 bg-primer border border-primer text-white rounded-sm text-xs font-medium">1</button>
                <button class="px-3 py-1 bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 rounded-sm text-xs">Selanjutnya</button>
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
