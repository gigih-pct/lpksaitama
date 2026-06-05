@extends('sensei.app')

@section('title', 'Kelas & Siswa - LPK Saitama')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 animate-fade-in-up">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
        <div>
            <h1 class="text-2xl font-medium text-primer tracking-tight">Kelas & Siswa</h1>
            <p class="text-sm text-gray-500 mt-1">Daftar kelas dan siswa yang berada di bawah bimbingan Anda.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($kelases as $kelas)
            <div class="bg-white rounded-md border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow group">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 rounded-md bg-primer/10 flex items-center justify-center text-primer group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <span class="text-xs font-medium px-2 py-1 rounded-sm bg-green-50 text-green-700 border border-green-100">Aktif</span>
                    </div>
                    
                    <h3 class="text-lg font-medium text-primer mb-1">Kelas {{ $kelas->nama_kelas }}</h3>
                    <p class="text-sm text-gray-500 mb-4">{{ $kelas->deskripsi ?? 'Program persiapan kerja ke Jepang' }}</p>
                    
                    <div class="flex items-center gap-4 text-sm text-gray-600 mb-6">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <span class="font-medium text-primer">{{ $kelas->siswas_count }}</span>&nbsp;Siswa
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                    <button type="button" class="w-full text-center text-sm font-medium text-sekunder hover:text-sekunder/80 transition-colors">
                        Lihat Daftar Siswa &rarr;
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white p-8 rounded-md border border-gray-100 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">Belum Ada Kelas</h3>
                <p class="text-sm text-gray-500">Anda belum ditugaskan ke kelas manapun.</p>
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
