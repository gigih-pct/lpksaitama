@extends('siswa.app')

@section('title', 'Kelengkapan Berkas - LPK Saitama')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 md:space-y-8 animate-fade-in-up">
    <!-- Header: Bio Siswa -->
    <div class="bg-white rounded-md border border-gray-100 shadow-sm p-6 flex flex-col md:flex-row gap-6 items-center md:items-start relative overflow-hidden">
        <div class="absolute right-0 top-0 w-32 h-32 bg-primer/5 transform translate-x-10 -translate-y-10 rotate-45"></div>
        
        <div class="relative shrink-0">
            <div class="w-20 h-20 bg-primer p-1 shadow-sm rounded-md">
                <div class="w-full h-full bg-white flex items-center justify-center overflow-hidden rounded-md border-2 border-white">
                    <svg class="w-10 h-10 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
            </div>
        </div>
        
        <div class="flex-1 text-center md:text-left z-10 w-full">
            <h1 class="text-xl font-medium text-primer tracking-tight mb-1">{{ $siswa->nama_lengkap }}</h1>
            <p class="text-gray-500 font-medium text-sm mb-3">Kelas {{ $siswa->kelas?->nama_kelas ?? 'Belum Ditentukan' }}</p>
            
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 border-t border-gray-100 pt-3 text-left">
                <div>
                    <p class="text-[10px] text-gray-400 font-medium mb-0.5 uppercase tracking-wide">No Induk</p>
                    <p class="text-xs text-gray-800 font-medium">{{ $siswa->nomor_induk ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 font-medium mb-0.5 uppercase tracking-wide">Progress Berkas</p>
                    <div class="flex items-center gap-2">
                        @php
                            $total_docs = $documents->count();
                            $valid_docs = $documents->where('status', 'Tervalidasi')->count();
                            $progress = $total_docs > 0 ? round(($valid_docs / $total_docs) * 100) : 0;
                        @endphp
                        <div class="w-16 h-1.5 bg-gray-200 rounded-sm overflow-hidden">
                            <div class="h-full bg-primer" style="width: {{ $progress }}%"></div>
                        </div>
                        <span class="text-xs text-primer font-medium">{{ $progress }}%</span>
                    </div>
                </div>
                <div class="col-span-2">
                    <p class="text-[10px] text-gray-400 font-medium mb-0.5 uppercase tracking-wide">Status Validasi Akhir</p>
                    <span class="text-[10px] font-medium bg-sekunder/10 text-sekunder border border-sekunder/20 px-2 py-0.5 rounded-sm">
                        {{ $total_docs - $valid_docs > 0 ? 'Menunggu '.($total_docs - $valid_docs).' Dokumen' : 'Semua Dokumen Lengkap' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content: Daftar Berkas -->
    <div class="bg-white rounded-md border border-gray-100 shadow-sm p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 border-b border-gray-100 pb-4">
            <div>
                <h2 class="text-lg font-medium text-primer flex items-center">
                    <svg class="w-5 h-5 mr-2 text-sekunder" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Daftar Persyaratan Dokumen
                </h2>
                <p class="text-xs text-gray-500 mt-1">Lengkapi dokumen di bawah ini sebagai syarat pengajuan Visa dan keberangkatan.</p>
            </div>
            <button class="bg-primer hover:bg-primer/90 text-white text-xs font-medium px-4 py-2 rounded-md transition-colors shadow-sm">
                Cetak Checklist
            </button>
        </div>

        <div class="space-y-4">
            
            @forelse($documents as $doc)
                @if($doc->status == 'Tervalidasi')
                <!-- Berkas Sudah Terkumpul -->
                <div class="border border-green-200 bg-green-50/30 rounded-md p-4">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-green-100 text-green-600 rounded-md flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <h3 class="font-medium text-gray-800 text-sm">{{ $doc->documentType->nama_dokumen }}</h3>
                        </div>
                        <span class="text-[10px] font-medium bg-green-100 text-green-700 px-2 py-0.5 rounded-sm border border-green-200">Tervalidasi</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-3 ml-8 text-xs">
                        <div class="text-gray-500">Tgl Kumpul: <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($doc->tanggal_kumpul ?? $doc->updated_at)->translatedFormat('d M Y') }}</span></div>
                        <div class="text-gray-500">Tgl Pengambilan: <span class="font-medium text-gray-800">{{ $doc->tanggal_pengambilan ? \Carbon\Carbon::parse($doc->tanggal_pengambilan)->translatedFormat('d M Y') : '-' }}</span></div>
                        <div class="col-span-1 sm:col-span-2 text-gray-500 mt-1">Keterangan: {{ $doc->keterangan ?? 'Dokumen telah divalidasi oleh admin.' }}</div>
                    </div>
                </div>
                @elseif($doc->status == 'Diproses' || $doc->status == 'Sedang Direview')
                <!-- Berkas Proses -->
                <div class="border border-primer/30 bg-white rounded-md p-4 relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-primer"></div>
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-primer/10 text-primer rounded-md flex items-center justify-center">
                                <svg class="w-4 h-4 animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            </div>
                            <h3 class="font-medium text-gray-800 text-sm">{{ $doc->documentType->nama_dokumen }}</h3>
                        </div>
                        <span class="text-[10px] font-medium bg-primer/10 text-primer px-2 py-0.5 rounded-sm border border-primer/20">{{ $doc->status }}</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-3 ml-8 text-xs">
                        <div class="text-gray-500">Tgl Kumpul: <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($doc->tanggal_kumpul ?? $doc->updated_at)->translatedFormat('d M Y') }}</span></div>
                        <div class="col-span-1 sm:col-span-2 text-gray-500 mt-1 text-primer">Catatan Admin: {{ $doc->keterangan ?? 'Menunggu proses validasi lebih lanjut.' }}</div>
                    </div>
                </div>
                @else
                <!-- Berkas Belum Terkumpul -->
                <div class="border border-gray-200 bg-gray-50 rounded-md p-4 opacity-80">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-gray-200 text-gray-500 rounded-md flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </div>
                            <h3 class="font-medium text-gray-600 text-sm">{{ $doc->documentType->nama_dokumen }}</h3>
                        </div>
                        <span class="text-[10px] font-medium bg-white text-gray-500 px-2 py-0.5 rounded-sm border border-gray-300">{{ $doc->status }}</span>
                    </div>
                    <div class="grid grid-cols-1 gap-2 mt-2 ml-8 text-xs">
                        <div class="text-gray-500">Keterangan: {{ $doc->keterangan ?? 'Silakan segera mengumpulkan dokumen ini.' }}</div>
                        <div class="mt-2">
                            <button class="bg-white border border-gray-300 text-gray-600 hover:bg-gray-100 hover:text-primer text-[10px] font-medium px-3 py-1.5 rounded-sm transition-colors shadow-sm">Upload/Konfirmasi Kumpul Dokumen</button>
                        </div>
                    </div>
                </div>
                @endif
            @empty
            <div class="text-center text-sm text-gray-500 py-4">Belum ada persyaratan dokumen.</div>
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
