@extends('siswa.app')

@section('title', 'Pusat Informasi - LPK Saitama')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 md:space-y-8 animate-fade-in-up">
    <!-- Header Page -->
    <div class="bg-primer rounded-md border border-primer/20 shadow-sm p-6 relative overflow-hidden group">
        <div class="absolute right-0 top-0 w-64 h-64 bg-white/5 transform translate-x-10 -translate-y-10 rounded-full group-hover:scale-110 transition-transform duration-700"></div>
        <div class="relative z-10">
            <h1 class="text-2xl font-medium text-white tracking-tight mb-2 flex items-center">
                <svg class="w-6 h-6 mr-2 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Pusat Informasi
            </h1>
            <p class="text-white/80 text-sm max-w-2xl">Dapatkan pemberitahuan terbaru, pengumuman akademik, serta informasi kontak pengajar dan staff administrasi LPK Saitama.</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
        
        <!-- Papan Pengumuman -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-md border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-5 border-b border-gray-100 pb-4">
                    <h2 class="text-lg font-medium text-primer flex items-center">
                        <svg class="w-5 h-5 mr-2 text-sekunder" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                        Papan Pengumuman
                    </h2>
                    <div class="flex gap-2">
                        <select class="text-xs font-medium border border-gray-200 text-gray-600 rounded-md py-1.5 px-2 bg-gray-50 outline-none focus:border-primer">
                            <option>Semua Kategori</option>
                            <option>Akademik</option>
                            <option>Keuangan</option>
                            <option>Pemberangkatan</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($announcements as $announcement)
                    <!-- Pengumuman -->
                    <div class="border {{ $loop->first ? 'border-primer/20 bg-primer/5 hover:border-primer/40' : 'border-gray-200 bg-white hover:border-primer/30' }} rounded-md p-5 transition-colors">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-[10px] font-medium {{ $announcement->kategori == 'Akademik' ? 'bg-gray-100 border border-gray-200 text-gray-600' : ($announcement->kategori == 'Keuangan' ? 'bg-green-50 border border-green-200 text-green-700' : 'bg-sekunder text-white border-transparent') }} px-2 py-0.5 rounded-sm">{{ $announcement->kategori ?? 'Umum' }}</span>
                            <span class="text-[10px] text-gray-500 font-medium">{{ \Carbon\Carbon::parse($announcement->tanggal)->translatedFormat('d M Y') }}</span>
                        </div>
                        <h3 class="font-medium {{ $loop->first ? 'text-primer' : 'text-gray-800' }} mb-2">{{ $announcement->judul }}</h3>
                        <p class="text-sm text-gray-600 mb-3 leading-relaxed">{{ $announcement->isi }}</p>
                    </div>
                    @empty
                    <div class="text-center text-sm text-gray-500 py-4">Belum ada pengumuman terbaru.</div>
                    @endforelse
                </div>
                
                <div class="mt-6 flex justify-center border-t border-gray-100 pt-5">
                    <button class="text-sm font-medium text-primer border border-primer hover:bg-primer hover:text-white px-4 py-2 rounded-md transition-colors">Muat Lebih Banyak</button>
                </div>
            </div>
        </div>

        <!-- Kontak Pengurus -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-md border border-gray-100 shadow-sm p-6">
                <h2 class="text-lg font-medium text-primer mb-5 flex items-center border-b border-gray-100 pb-4">
                    <svg class="w-5 h-5 mr-2 text-tersier" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Direktori Kontak
                </h2>

                <div class="space-y-4">
                    <!-- Sensei -->
                    <div class="border border-gray-100 bg-gray-50 rounded-md p-3">
                        <span class="text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-2 block">Pengajar (Wali Kelas)</span>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-primer/10 rounded-md flex items-center justify-center text-primer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-sm text-gray-800">Budi Santoso</h4>
                                <p class="text-[10px] text-gray-500">Sensei / Wali N4 Sore</p>
                            </div>
                            <a href="#" class="w-8 h-8 bg-green-100 text-green-600 rounded-md flex items-center justify-center hover:bg-green-200 transition-colors" title="Hubungi via WhatsApp">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Admin Akademik -->
                    <div class="border border-gray-100 bg-gray-50 rounded-md p-3">
                        <span class="text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-2 block">Administrasi & Akademik</span>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-sekunder/10 rounded-md flex items-center justify-center text-sekunder">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-sm text-gray-800">Dini Fitriani</h4>
                                <p class="text-[10px] text-gray-500">Admin Akademik</p>
                            </div>
                            <a href="#" class="w-8 h-8 bg-green-100 text-green-600 rounded-md flex items-center justify-center hover:bg-green-200 transition-colors" title="Hubungi via WhatsApp">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Admin Keuangan -->
                    <div class="border border-gray-100 bg-gray-50 rounded-md p-3">
                        <span class="text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-2 block">Keuangan & Pembayaran</span>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-tersier/10 rounded-md flex items-center justify-center text-tersier">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-sm text-gray-800">Siti Rahma</h4>
                                <p class="text-[10px] text-gray-500">Finance LPK</p>
                            </div>
                            <a href="#" class="w-8 h-8 bg-green-100 text-green-600 rounded-md flex items-center justify-center hover:bg-green-200 transition-colors" title="Hubungi via WhatsApp">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 rounded-md border border-gray-200 p-5 text-center">
                <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-xs text-gray-500 font-medium">Butuh Bantuan Lainnya?</p>
                <p class="text-[10px] text-gray-400 mt-1">Silakan kunjungi langsung ruang administrasi LPK Saitama pada jam kerja (Senin-Jumat, 08:00 - 16:00).</p>
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
