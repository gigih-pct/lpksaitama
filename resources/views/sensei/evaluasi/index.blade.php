@extends('sensei.app')

@section('title', 'Evaluasi Siswa - LPK Saitama')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 animate-fade-in-up">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
        <div>
            <h1 class="text-2xl font-medium text-primer tracking-tight">Evaluasi Siswa</h1>
            <p class="text-sm text-gray-500 mt-1">Berikan catatan khusus, saran, dan evaluasi bulanan untuk siswa.</p>
        </div>
        <div>
            <button class="bg-primer hover:bg-primer/90 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors flex items-center shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Evaluasi Baru
            </button>
        </div>
    </div>

    <!-- Timeline Evaluasi -->
    <div class="bg-white rounded-md border border-gray-100 shadow-sm p-6">
        <h2 class="text-lg font-medium text-primer mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2 text-sekunder" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            Riwayat Evaluasi Terakhir
        </h2>

        <div class="space-y-6">
            @forelse($evaluations as $eval)
                <div class="relative pl-8 sm:pl-32 py-2 group">
                    <!-- Date (Left side on sm screens) -->
                    <div class="hidden sm:block absolute left-0 top-3 text-right w-24 text-sm font-medium text-gray-500">
                        {{ \Carbon\Carbon::parse($eval->tanggal_evaluasi)->format('d M Y') }}
                    </div>
                    
                    <!-- Line -->
                    <div class="absolute left-[11px] sm:left-[111px] top-4 bottom-0 w-px bg-gray-200 group-last:bg-transparent"></div>
                    
                    <!-- Dot -->
                    <div class="absolute left-0 sm:left-[100px] top-3 w-6 h-6 rounded-full bg-white border-2 border-primer z-10 flex items-center justify-center">
                        <div class="w-2 h-2 rounded-full bg-primer"></div>
                    </div>
                    
                    <!-- Content Card -->
                    <div class="bg-gray-50 rounded-md border border-gray-100 p-4 hover:shadow-sm transition-shadow">
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2 mb-3">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="text-base font-medium text-primer">{{ $eval->judul }}</h3>
                                    <span class="text-[10px] bg-green-100 text-green-800 px-2 py-0.5 rounded-sm font-medium">Terkirim</span>
                                </div>
                                <p class="text-xs font-medium text-gray-600 mb-2">Untuk: <span class="text-primer">{{ $eval->siswa->nama_lengkap ?? 'Seluruh Kelas' }}</span></p>
                                <div class="text-xs text-gray-500 sm:hidden mb-2 font-medium">
                                    {{ \Carbon\Carbon::parse($eval->tanggal_evaluasi)->format('d M Y') }}
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="text-sekunder hover:text-sekunder/80 text-xs font-medium px-3 py-1.5 border border-sekunder rounded-md hover:bg-sekunder/5 transition-colors">Edit</button>
                                <button class="text-red-500 hover:text-red-600 text-xs font-medium px-3 py-1.5 border border-red-200 hover:bg-red-50 rounded-md transition-colors">Hapus</button>
                            </div>
                        </div>
                        
                        <div class="text-sm text-gray-700 leading-relaxed bg-white p-3 rounded border border-gray-100">
                            {{ $eval->deskripsi }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-10">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">Belum Ada Evaluasi</h3>
                    <p class="text-sm text-gray-500">Anda belum memberikan evaluasi untuk siswa manapun.</p>
                </div>
            @endforelse
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
