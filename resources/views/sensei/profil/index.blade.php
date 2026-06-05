@extends('sensei.app')

@section('title', 'Profil Sensei - LPK Saitama')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 md:space-y-8 animate-fade-in-up">
    
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-medium text-primer tracking-tight mb-2">Profil Saya</h1>
        <p class="text-sm text-gray-500">Kelola informasi pribadi dan kredensial akun Anda.</p>
    </div>

    <!-- Profil Card -->
    <div class="bg-white rounded-md border border-gray-100 shadow-sm overflow-hidden">
        
        <!-- Cover Banner -->
        <div class="h-32 bg-primer relative">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 mix-blend-overlay"></div>
            <div class="absolute -bottom-16 left-8">
                <!-- Avatar -->
                <div class="w-32 h-32 bg-white rounded-md p-1.5 shadow-md">
                    <div class="w-full h-full bg-gray-100 rounded flex items-center justify-center border border-gray-200">
                        <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                </div>
            </div>
            
            <div class="absolute top-4 right-4 flex gap-2">
                <span class="bg-white/20 backdrop-blur-sm border border-white/30 text-white text-[10px] font-medium px-3 py-1 rounded-sm shadow-sm">ID: {{ $sensei->nomor_induk ?? '-' }}</span>
            </div>
        </div>

        <!-- Form Info -->
        <div class="pt-20 px-8 pb-8">
            <form action="#" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="col-span-full md:col-span-2 border-b border-gray-100 pb-2 mb-2">
                        <h3 class="text-sm font-medium text-primer uppercase tracking-wider">Informasi Pribadi</h3>
                    </div>

                    <div class="col-span-full md:col-span-1 space-y-1">
                        <label class="block text-xs font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" value="{{ $sensei->nama_lengkap }}" class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 outline-none focus:border-primer focus:ring-1 focus:ring-primer bg-gray-50 text-gray-700" readonly>
                        <p class="text-[10px] text-gray-400 mt-1">Nama lengkap hanya dapat diubah oleh Admin.</p>
                    </div>

                    <div class="col-span-full md:col-span-1 space-y-1">
                        <label class="block text-xs font-medium text-gray-700">Email Login</label>
                        <input type="email" value="{{ $sensei->email }}" class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 outline-none focus:border-primer focus:ring-1 focus:ring-primer bg-gray-50 text-gray-700" readonly>
                    </div>

                    <div class="col-span-full border-b border-gray-100 pb-2 mb-2 mt-4">
                        <h3 class="text-sm font-medium text-primer uppercase tracking-wider">Ubah Password</h3>
                    </div>

                    <div class="col-span-full md:col-span-1 space-y-1">
                        <label class="block text-xs font-medium text-gray-700">Password Lama</label>
                        <input type="password" name="old_password" class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 outline-none focus:border-primer focus:ring-1 focus:ring-primer" placeholder="Masukkan password lama">
                    </div>

                    <div class="col-span-full space-y-1 md:col-span-1"></div>

                    <div class="col-span-full md:col-span-1 space-y-1">
                        <label class="block text-xs font-medium text-gray-700">Password Baru</label>
                        <input type="password" name="password" class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 outline-none focus:border-primer focus:ring-1 focus:ring-primer" placeholder="Masukkan password baru">
                    </div>

                    <div class="col-span-full md:col-span-1 space-y-1">
                        <label class="block text-xs font-medium text-gray-700">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 outline-none focus:border-primer focus:ring-1 focus:ring-primer" placeholder="Ulangi password baru">
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-primer hover:bg-primer/90 text-white px-6 py-2 rounded-md text-sm font-medium transition-colors shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
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
