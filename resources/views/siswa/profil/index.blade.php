@extends('siswa.app')

@section('title', 'Profil - LPK Saitama')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 md:space-y-8 animate-fade-in-up">
    <!-- Profil Header -->
    <div class="bg-white rounded-md border border-gray-100 shadow-sm p-6 md:p-8 flex flex-col md:flex-row items-center gap-6">
        <div class="w-32 h-32 bg-primer p-1 shadow-sm rounded-md shrink-0">
            <div class="w-full h-full bg-white flex items-center justify-center overflow-hidden rounded-md border-2 border-white">
                <svg class="w-16 h-16 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            </div>
        </div>
        
        <div class="flex-1 text-center md:text-left">
            <h1 class="text-3xl font-medium text-primer tracking-tight mb-2">{{ $siswa->nama_lengkap }}</h1>
            <p class="text-gray-500 font-medium mb-4">Program Bahasa Jepang - {{ $siswa->kelas?->nama_kelas ?? 'Belum Ditentukan' }}</p>
            <div class="flex flex-wrap justify-center md:justify-start gap-3">
                <span class="inline-flex items-center text-xs font-medium bg-primer/10 text-primer px-3 py-1 rounded-md border border-primer/20">
                    ID: {{ $siswa->nomor_induk ?? '-' }}
                </span>
                <span class="inline-flex items-center text-xs font-medium bg-sekunder/10 text-sekunder px-3 py-1 rounded-md border border-sekunder/20">
                    Status: {{ $siswa->status ?? 'Aktif' }}
                </span>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <button class="bg-primer text-white px-5 py-2.5 rounded-md text-sm font-medium hover:bg-primer/90 transition-colors shadow-sm inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Simpan Perubahan
            </button>
        </div>
    </div>

    <!-- Form Biodata -->
    <div class="bg-white rounded-md border border-gray-100 shadow-sm p-6 md:p-8">
        <h2 class="text-lg font-medium text-primer border-b border-gray-100 pb-4 mb-6">Informasi Biodata Lengkap</h2>
        <form action="#" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Data Diri -->
                <div class="space-y-4">
                    <h3 class="text-sm font-medium text-gray-800 border-b border-gray-50 pb-2">Data Diri</h3>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ $siswa->nama_lengkap }}" class="w-full border border-gray-200 rounded-md px-3 py-2 text-sm text-gray-800 bg-gray-50 focus:bg-white focus:border-primer focus:ring-1 focus:ring-primer outline-none transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Nomor Induk Siswa</label>
                        <input type="text" name="nomor_induk" value="{{ $siswa->nomor_induk }}" readonly class="w-full border border-gray-200 rounded-md px-3 py-2 text-sm text-gray-500 bg-gray-100 outline-none cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Kelas</label>
                        <input type="text" value="{{ $siswa->kelas?->nama_kelas ?? '-' }}" readonly class="w-full border border-gray-200 rounded-md px-3 py-2 text-sm text-gray-500 bg-gray-100 outline-none cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ $siswa->tanggal_lahir }}" class="w-full border border-gray-200 rounded-md px-3 py-2 text-sm text-gray-800 bg-gray-50 focus:bg-white focus:border-primer focus:ring-1 focus:ring-primer outline-none transition-colors">
                    </div>
                </div>

                <!-- Kontak & Alamat -->
                <div class="space-y-4">
                    <h3 class="text-sm font-medium text-gray-800 border-b border-gray-50 pb-2">Kontak & Keluarga</h3>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Nomor HP / WhatsApp</label>
                        <input type="text" name="no_hp_siswa" value="{{ $siswa->no_hp_siswa }}" class="w-full border border-gray-200 rounded-md px-3 py-2 text-sm text-gray-800 bg-gray-50 focus:bg-white focus:border-primer focus:ring-1 focus:ring-primer outline-none transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Alamat Lengkap</label>
                        <textarea name="alamat" rows="3" class="w-full border border-gray-200 rounded-md px-3 py-2 text-sm text-gray-800 bg-gray-50 focus:bg-white focus:border-primer focus:ring-1 focus:ring-primer outline-none transition-colors">{{ $siswa->alamat }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Nomor HP Orang Tua/Wali</label>
                        <input type="text" name="no_hp_orangtua" value="{{ $siswa->no_hp_orangtua }}" class="w-full border border-gray-200 rounded-md px-3 py-2 text-sm text-gray-800 bg-gray-50 focus:bg-white focus:border-primer focus:ring-1 focus:ring-primer outline-none transition-colors">
                    </div>
                </div>
            </div>
        </form>
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
