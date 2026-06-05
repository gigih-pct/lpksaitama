@extends('siswa.app')

@section('title', 'Evaluasi - LPK Saitama')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 md:space-y-8 animate-fade-in-up">
    <!-- Header: Data Diri Siswa -->
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
            <p class="text-gray-500 font-medium text-sm mb-3">Kelas {{ $siswa->kelas->nama_kelas ?? 'Belum Ditentukan' }}</p>
            
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 border-t border-gray-100 pt-3 text-left">
                <div>
                    <p class="text-[10px] text-gray-400 font-medium mb-0.5 uppercase tracking-wide">No Induk</p>
                    <p class="text-xs text-gray-800 font-medium">{{ $siswa->nomor_induk ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 font-medium mb-0.5 uppercase tracking-wide">No HP</p>
                    <p class="text-xs text-gray-800 font-medium">{{ $siswa->no_hp_siswa ?? '-' }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-[10px] text-gray-400 font-medium mb-0.5 uppercase tracking-wide">Alamat</p>
                    <p class="text-xs text-gray-800 font-medium truncate">{{ $siswa->alamat ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
        
        <!-- Jadwal Evaluasi Rekomendasi LPK -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-md border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-lg font-medium text-primer flex items-center">
                        <svg class="w-5 h-5 mr-2 text-sekunder" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Jadwal Evaluasi (Rekomendasi LPK)
                    </h2>
                </div>

                <p class="text-sm text-gray-500 mb-6">Berikut adalah daftar ujian/evaluasi resmi yang direkomendasikan LPK Saitama untuk mengukur kemampuanmu sebelum wawancara dengan perusahaan Jepang.</p>

                <div class="space-y-4">
                    @forelse($evaluations as $eval)
                    <div class="border {{ \Carbon\Carbon::parse($eval->tanggal_evaluasi)->isPast() ? 'border-gray-200 bg-white' : 'border-primer/20 bg-primer/5' }} rounded-md p-4 relative overflow-hidden">
                        @if(!\Carbon\Carbon::parse($eval->tanggal_evaluasi)->isPast())
                            <div class="absolute right-0 top-0 bg-sekunder text-white text-[10px] font-medium px-2 py-1 rounded-bl-md">Segera Dibuka</div>
                        @else
                            <div class="absolute right-0 top-0 bg-gray-500 text-white text-[10px] font-medium px-2 py-1 rounded-bl-md">Selesai</div>
                        @endif
                        <h3 class="font-medium {{ \Carbon\Carbon::parse($eval->tanggal_evaluasi)->isPast() ? 'text-gray-800' : 'text-primer' }} mb-1">{{ $eval->judul }}</h3>
                        <p class="text-xs text-gray-500 mb-2">{{ $eval->deskripsi }}</p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-3">
                            <div class="flex items-center text-xs text-gray-600">
                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="font-medium">Tanggal:</span> &nbsp;{{ \Carbon\Carbon::parse($eval->tanggal_evaluasi)->translatedFormat('d M Y') }}
                            </div>
                            <div class="flex items-center text-xs text-gray-600">
                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="font-medium">Sensei:</span> &nbsp;{{ $eval->sensei->nama_lengkap ?? '-' }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-sm text-gray-500 py-4">Belum ada jadwal evaluasi rekomendasi LPK.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Kontak Admin LPK -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-sekunder rounded-md p-6 text-white shadow-sm relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                <h3 class="text-lg font-medium mb-4 flex items-center relative z-10">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    Bantuan Pendaftaran
                </h3>
                
                <p class="text-xs text-white/80 mb-6 relative z-10 leading-relaxed">Jika kamu mengalami kesulitan dalam mendaftar ujian JFT atau JLPT, silakan hubungi admin akademik LPK Saitama untuk panduan pendaftaran kolektif.</p>
                
                <div class="bg-white rounded-md p-4 text-gray-800 relative z-10">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-sekunder/10 rounded-md flex items-center justify-center text-sekunder">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-sm">Admin Akademik</h4>
                            <p class="text-xs text-gray-500">Mbak Dini</p>
                        </div>
                    </div>
                    
                    <a href="https://wa.me/628123456789" target="_blank" class="w-full flex items-center justify-center gap-2 bg-[#25D366] hover:bg-[#20bd5a] text-white px-4 py-2.5 rounded-md text-sm font-medium transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        Chat WhatsApp
                    </a>
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
