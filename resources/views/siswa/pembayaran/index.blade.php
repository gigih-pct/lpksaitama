@extends('siswa.app')

@section('title', 'Pembayaran - LPK Saitama')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 md:space-y-8 animate-fade-in-up">
    <!-- Header Summary -->
    <div class="bg-white rounded-md border border-gray-100 shadow-sm p-6 flex flex-col sm:flex-row gap-6 items-center sm:items-start">
        <div class="w-16 h-16 bg-primer/10 rounded-md flex-shrink-0 flex items-center justify-center border border-primer/20">
            <svg class="w-8 h-8 text-primer" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
        </div>
        <div class="flex-1 w-full text-center sm:text-left">
            <h1 class="text-xl sm:text-2xl font-medium text-primer tracking-tight mb-1">Status Pembayaran & Perlengkapan</h1>
            <p class="text-sm text-gray-500 mb-4">Nama: {{ $siswa->nama_lengkap }} | No Induk: {{ $siswa->nomor_induk ?? '-' }}</p>
            
            <div class="flex flex-wrap gap-4 mt-2 justify-center sm:justify-start">
                <div class="bg-gray-50 border border-gray-200 px-4 py-2 rounded-md">
                    <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wide">Total Terbayar</p>
                    <p class="text-sm font-medium text-primer mt-0.5">Rp {{ number_format($invoices->where('status', 'Lunas')->sum('jumlah'), 0, ',', '.') }}</p>
                </div>
                <div class="bg-red-50 border border-red-100 px-4 py-2 rounded-md">
                    <p class="text-[10px] text-red-400 font-medium uppercase tracking-wide">Sisa Tagihan</p>
                    <p class="text-sm font-medium text-red-600 mt-0.5">Rp {{ number_format($invoices->where('status', 'Belum Lunas')->sum('jumlah'), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
        
        <!-- Status Pembayaran List -->
        <div class="space-y-6">
            <div class="bg-white rounded-md border border-gray-100 shadow-sm p-6">
                <h2 class="text-lg font-medium text-primer mb-5 flex items-center border-b border-gray-100 pb-4">
                    <svg class="w-5 h-5 mr-2 text-sekunder" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Riwayat Tagihan
                </h2>
                
                <div class="space-y-4">
                    @forelse($invoices as $invoice)
                        @if($invoice->status == 'Belum Lunas')
                        <!-- Tagihan Belum Lunas -->
                        <div class="border border-red-200 bg-red-50 rounded-md p-4">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="font-medium text-red-900 text-sm">{{ $invoice->nama_tagihan }}</h3>
                                    <p class="text-xs text-red-600 mt-0.5">Jatuh Tempo: {{ \Carbon\Carbon::parse($invoice->jatuh_tempo)->translatedFormat('d M Y') }}</p>
                                </div>
                                <span class="text-[10px] font-medium bg-red-600 text-white px-2 py-0.5 rounded-sm">Belum Lunas</span>
                            </div>
                            <div class="flex justify-between items-center mt-4">
                                <span class="text-sm font-medium text-red-800">Rp {{ number_format($invoice->jumlah, 0, ',', '.') }}</span>
                                <button class="text-xs font-medium bg-white text-red-600 border border-red-200 hover:bg-red-100 px-3 py-1.5 rounded-md transition-colors">Bayar Sekarang</button>
                            </div>
                        </div>
                        @else
                        <!-- Tagihan Lunas -->
                        <div class="border border-gray-200 bg-white rounded-md p-4">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="font-medium text-gray-800 text-sm">{{ $invoice->nama_tagihan }}</h3>
                                    <p class="text-xs text-gray-500 mt-0.5">Dibayar pada: {{ \Carbon\Carbon::parse($invoice->tanggal_bayar ?? $invoice->updated_at)->translatedFormat('d M Y') }}</p>
                                </div>
                                <span class="text-[10px] font-medium bg-green-100 text-green-700 px-2 py-0.5 rounded-sm border border-green-200">Lunas</span>
                            </div>
                            <div class="flex justify-between items-center mt-4">
                                <span class="text-sm font-medium text-gray-600">Rp {{ number_format($invoice->jumlah, 0, ',', '.') }}</span>
                                <a href="#" class="text-xs font-medium text-primer hover:underline">Lihat Kuitansi</a>
                            </div>
                        </div>
                        @endif
                    @empty
                    <div class="text-center text-sm text-gray-500 py-4">Belum ada riwayat tagihan.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Milestone Perlengkapan -->
        <div class="space-y-6">
            <div class="bg-white rounded-md border border-gray-100 shadow-sm p-6">
                <h2 class="text-lg font-medium text-primer mb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-tersier" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    Distribusi Perlengkapan
                </h2>
                <p class="text-xs text-gray-500 mb-6">Perlengkapan dibagikan berdasarkan progress pembayaran dan kelengkapan dokumen siswa.</p>

                <div class="relative border-l-2 border-gray-200 ml-3 md:ml-4 space-y-8 pb-4">
                    
                    @forelse($equipments as $item)
                        @if($item->status == 'Sudah Diambil')
                        <!-- Milestone: Sudah Diambil -->
                        <div class="relative pl-6 sm:pl-8">
                            <div class="absolute -left-[9px] top-0.5 w-4 h-4 rounded-full bg-green-500 border-4 border-white shadow-sm"></div>
                            <div class="bg-gray-50 border border-gray-200 rounded-md p-4">
                                <div class="flex justify-between items-start mb-1">
                                    <h4 class="text-sm font-medium text-gray-800">{{ $item->equipment->nama_perlengkapan }}</h4>
                                    <span class="text-[10px] font-medium text-green-600 bg-green-100 px-2 py-0.5 rounded-sm">Sudah Diambil</span>
                                </div>
                                <p class="text-xs text-gray-500 mb-2">{{ $item->equipment->deskripsi }}</p>
                                <div class="text-[10px] text-gray-400">Diambil pada: {{ \Carbon\Carbon::parse($item->tanggal_diambil ?? $item->updated_at)->translatedFormat('d M Y') }}</div>
                            </div>
                        </div>
                        @elseif($item->status == 'Bisa Diambil')
                        <!-- Milestone: Bisa Diambil -->
                        <div class="relative pl-6 sm:pl-8">
                            <div class="absolute -left-[9px] top-0.5 w-4 h-4 rounded-full bg-primer border-4 border-white shadow-[0_0_0_2px_rgba(24,52,92,0.2)] animate-pulse"></div>
                            <div class="bg-white border border-primer/30 rounded-md p-4 shadow-sm relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-16 h-16 bg-primer/5 transform translate-x-4 -translate-y-4 rounded-full"></div>
                                <div class="flex justify-between items-start mb-1 relative z-10">
                                    <h4 class="text-sm font-medium text-primer">{{ $item->equipment->nama_perlengkapan }}</h4>
                                    <span class="text-[10px] font-medium text-white bg-primer px-2 py-0.5 rounded-sm">Bisa Diambil</span>
                                </div>
                                <p class="text-xs text-gray-500 mb-3 relative z-10">{{ $item->equipment->deskripsi }}</p>
                                <button class="text-[10px] font-medium border border-primer text-primer px-3 py-1.5 rounded-sm hover:bg-primer hover:text-white transition-colors relative z-10">Tunjukkan ke Admin</button>
                            </div>
                        </div>
                        @else
                        <!-- Milestone: Belum Memenuhi Syarat -->
                        <div class="relative pl-6 sm:pl-8">
                            <div class="absolute -left-[9px] top-0.5 w-4 h-4 rounded-full bg-gray-200 border-4 border-white"></div>
                            <div class="bg-white border border-gray-200 rounded-md p-4 opacity-70 grayscale">
                                <div class="flex justify-between items-start mb-1">
                                    <h4 class="text-sm font-medium text-gray-600">{{ $item->equipment->nama_perlengkapan }}</h4>
                                    <span class="text-[10px] font-medium text-gray-500 bg-gray-100 border border-gray-200 px-2 py-0.5 rounded-sm">Belum Memenuhi Syarat</span>
                                </div>
                                <p class="text-xs text-gray-500">Syarat: {{ $item->equipment->syarat_pengambilan }}</p>
                            </div>
                        </div>
                        @endif
                    @empty
                    <div class="text-center text-sm text-gray-500 py-4">Belum ada daftar perlengkapan.</div>
                    @endforelse

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
</style>
@endsection
