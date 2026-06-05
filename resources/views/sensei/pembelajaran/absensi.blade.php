@extends('sensei.app')

@section('title', 'Daftar Hadir - LPK Saitama')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 animate-fade-in-up">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('sensei.pembelajaran') }}" class="hover:text-primer transition-colors">Pembelajaran</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-gray-700">Daftar Hadir</span>
            </div>
            <h1 class="text-2xl font-medium text-primer tracking-tight">{{ $curriculum->judul }}</h1>
            <p class="text-sm text-gray-500 mt-1">Kelas: {{ $curriculum->kelas->nama_kelas }}</p>
        </div>
        
        <div class="flex gap-2">
            <form action="{{ route('sensei.pembelajaran.absensi.generate') }}" method="POST">
                @csrf
                <input type="hidden" name="curriculum_id" value="{{ $curriculum->id }}">
                <button type="submit" class="bg-sekunder hover:bg-sekunder/90 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors flex items-center shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buka Sesi Hari Ini
                </button>
            </form>
        </div>
    </div>

    @if(session('absensi_code'))
        <div class="bg-blue-50 border border-blue-200 rounded-md p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm text-blue-800 font-medium">Sesi Absensi Aktif!</p>
                    <p class="text-xs text-blue-600 mt-0.5">Berikan kode ini kepada siswa: <span class="font-bold font-mono text-base ml-1 bg-white px-2 py-0.5 rounded border border-blue-200">{{ session('absensi_code') }}</span></p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-md border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
            <h3 class="font-medium text-gray-800">Riwayat Sesi Absensi</h3>
        </div>
        
        @forelse($sessions as $session)
            <div class="border-b border-gray-100 p-4" x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }">
                <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center" :class="open ? 'bg-primer text-white' : 'bg-gray-100 text-gray-500'">
                            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                        <div>
                            <p class="font-medium text-sm text-gray-800">Tanggal: {{ $session->tanggal->format('d M Y') }}</p>
                            <p class="text-xs text-gray-500 font-mono mt-0.5">Kode: {{ $session->kode_absen }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex gap-3 text-xs">
                            @php
                                $hadir = $session->attendances->where('status', 'Hadir')->count();
                                $izin = $session->attendances->where('status', 'Izin')->count();
                                $sakit = $session->attendances->where('status', 'Sakit')->count();
                            @endphp
                            <span class="text-green-600 font-medium bg-green-50 px-2 py-1 rounded">Hadir: {{ $hadir }}</span>
                            <span class="text-blue-600 font-medium bg-blue-50 px-2 py-1 rounded">Izin: {{ $izin }}</span>
                            <span class="text-yellow-600 font-medium bg-yellow-50 px-2 py-1 rounded">Sakit: {{ $sakit }}</span>
                        </div>
                        <span class="text-xs {{ $session->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }} px-2 py-1 rounded-sm border {{ $session->is_active ? 'border-green-200' : 'border-gray-200' }}">
                            {{ $session->is_active ? 'Aktif' : 'Ditutup' }}
                        </span>
                    </div>
                </div>

                <div x-show="open" x-collapse class="mt-4 pt-4 border-t border-gray-100">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-gray-50 text-gray-600 font-medium">
                            <tr>
                                <th class="px-4 py-3 rounded-l-md">Nama Siswa</th>
                                <th class="px-4 py-3">Waktu</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 rounded-r-md">Keterangan / Bukti</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @php
                                // Ambil semua siswa di kelas ini, lalu gabungkan dengan attendance (jika ada)
                                $siswas = $curriculum->kelas->siswas;
                            @endphp
                            
                            @foreach($siswas as $siswa)
                                @php
                                    $absen = $session->attendances->where('siswa_id', $siswa->id)->first();
                                    $status = $absen ? $absen->status : 'Alpa';
                                @endphp
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-4 py-3 font-medium text-gray-800">{{ $siswa->nama_lengkap }}</td>
                                    <td class="px-4 py-3 text-gray-500">
                                        {{ $absen ? $absen->created_at->format('H:i') : '-' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($status === 'Hadir')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Hadir</span>
                                        @elseif($status === 'Izin')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Izin</span>
                                        @elseif($status === 'Sakit')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Sakit</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Alpa</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-500">
                                        @if($absen && $absen->keterangan)
                                            <div class="flex items-center gap-2">
                                                <span class="truncate max-w-[150px] inline-block" title="{{ $absen->keterangan }}">{{ $absen->keterangan }}</span>
                                                @if($absen->bukti_foto)
                                                    <a href="{{ Storage::url($absen->bukti_foto) }}" target="_blank" class="text-primer hover:underline flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                        Bukti
                                                    </a>
                                                @endif
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="p-12 text-center">
                <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 border border-gray-100">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-sm text-gray-500">Belum ada sesi absensi untuk materi ini.</p>
                <form action="{{ route('sensei.pembelajaran.absensi.generate') }}" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="curriculum_id" value="{{ $curriculum->id }}">
                    <button type="submit" class="text-primer hover:text-primer/80 text-sm font-medium">Buka Sesi Pertama</button>
                </form>
            </div>
        @endforelse
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
