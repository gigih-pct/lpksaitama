@extends('sensei.app')

@section('title', 'Pembelajaran & Kurikulum - LPK Saitama')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 animate-fade-in-up">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
        <div>
            <h1 class="text-2xl font-medium text-primer tracking-tight">Pembelajaran</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola bagan kurikulum dan materi pengajaran untuk kelas Anda.</p>
        </div>
        <div>
            <button class="bg-sekunder hover:bg-sekunder/90 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors flex items-center shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Modul/Materi
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
        </div>
    </div>

    <div class="space-y-4">
        @forelse($curriculums as $index => $curriculum)
            <div class="bg-white rounded-md border border-gray-100 shadow-sm p-5 hover:shadow transition-shadow group flex items-start gap-4">
                
                <!-- Urutan/Icon -->
                <div class="shrink-0 pt-1">
                    <div class="w-10 h-10 rounded-full bg-primer/10 text-primer flex items-center justify-center font-bold text-sm border border-primer/20">
                        {{ $curriculum->urutan ?: $index + 1 }}
                    </div>
                </div>

                <!-- Konten -->
                <div class="flex-1">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2 mb-2">
                        <div>
                            <h3 class="text-lg font-medium text-primer">{{ $curriculum->judul }}</h3>
                            <div class="text-xs text-gray-500 flex items-center gap-2 mt-1">
                                <span class="bg-gray-100 px-2 py-0.5 rounded text-gray-600">
                                    Kelas: {{ $curriculum->kelas->nama_kelas ?? '-' }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button class="text-xs bg-white border border-gray-200 text-gray-600 hover:text-primer hover:border-primer px-3 py-1.5 rounded transition-colors">Edit</button>
                            <button class="text-xs bg-white border border-red-200 text-red-500 hover:bg-red-50 px-3 py-1.5 rounded transition-colors">Hapus</button>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        {{ $curriculum->deskripsi ?: 'Tidak ada deskripsi.' }}
                    </p>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-md border border-gray-100 shadow-sm p-12 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477-4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">Belum Ada Bagan Kurikulum</h3>
                <p class="text-sm text-gray-500 mb-4">Anda belum membuat modul atau urutan materi pembelajaran untuk kelas Anda.</p>
                <button class="text-sekunder hover:text-sekunder/80 text-sm font-medium">
                    + Buat Materi Pertama
                </button>
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
