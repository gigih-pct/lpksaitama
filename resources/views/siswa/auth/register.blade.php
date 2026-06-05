<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Siswa - LPK Saitama</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-800 bg-white">

    <div class="flex min-h-screen">
        
        <!-- Kiri: Form Register -->
        <div class="w-full lg:w-[60%] flex items-center justify-center p-8 sm:p-12 lg:p-16 overflow-y-auto">
            <div class="w-full max-w-lg">
                
                <!-- Header Form -->
                <div class="mb-8 text-center lg:text-left">
                    <div class="inline-flex items-center justify-center lg:justify-start gap-3 mb-6">
                        <img src="{{ asset('logo/logo_berdiri.webp') }}" alt="LPK Saitama" class="h-20 lg:justify-start">
                    </div>
                    
                    <h1 class="text-2xl sm:text-3xl font-medium text-primer mb-2">Pendaftaran Siswa Baru</h1>
                    <p class="text-sm text-gray-500">Akun yang didaftarkan akan diverifikasi oleh Admin sebelum dapat digunakan.</p>
                </div>

                @if($errors->any())
                    <div class="bg-red-50 text-red-600 text-xs p-3 rounded-md mb-6 border border-red-100">
                        <ul class="list-disc pl-4">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form Register -->
                <form action="{{ route('siswa.register') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-2">
                            <label for="nama_lengkap" class="block text-xs font-medium text-gray-700 mb-1.5 uppercase tracking-wide">Nama Lengkap Sesuai KTP</label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-md focus:outline-none focus:border-primer focus:ring-1 focus:ring-primer focus:bg-white text-sm transition-colors" placeholder="Contoh: Budi Santoso" required autofocus>
                        </div>

                        <div>
                            <label for="nomor_induk" class="block text-xs font-medium text-gray-700 mb-1.5 uppercase tracking-wide">Nomor Induk Siswa</label>
                            <input type="text" id="nomor_induk" name="nomor_induk" value="{{ old('nomor_induk') }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-md focus:outline-none focus:border-primer focus:ring-1 focus:ring-primer focus:bg-white text-sm transition-colors" placeholder="Contoh: 20261001" required>
                            <p class="text-[10px] text-gray-400 mt-1">Dapatkan dari admin pendaftaran.</p>
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-medium text-gray-700 mb-1.5 uppercase tracking-wide">Email Aktif</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-md focus:outline-none focus:border-primer focus:ring-1 focus:ring-primer focus:bg-white text-sm transition-colors" placeholder="nama@email.com" required>
                        </div>

                        <div>
                            <label for="password" class="block text-xs font-medium text-gray-700 mb-1.5 uppercase tracking-wide">Password</label>
                            <input type="password" id="password" name="password" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-md focus:outline-none focus:border-primer focus:ring-1 focus:ring-primer focus:bg-white text-sm transition-colors" placeholder="Minimal 8 karakter" required>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-medium text-gray-700 mb-1.5 uppercase tracking-wide">Ulangi Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-md focus:outline-none focus:border-primer focus:ring-1 focus:ring-primer focus:bg-white text-sm transition-colors" placeholder="Ketik ulang password" required>
                        </div>
                    </div>

                    <div class="pt-2">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="terms" type="checkbox" class="w-4 h-4 text-primer border-gray-300 rounded focus:ring-primer" required>
                            </div>
                            <label for="terms" class="ml-2 text-xs text-gray-600">
                                Saya menyadari bahwa setelah mendaftar, akun ini <strong class="text-primer">membutuhkan persetujuan (ACC) dari Admin</strong> sebelum bisa digunakan untuk login.
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-primer hover:bg-primer/90 text-white font-medium py-3 rounded-md text-sm transition-colors shadow-sm mt-4">
                        Buat Akun Siswa
                    </button>
                </form>

                <div class="mt-8 text-center lg:text-left border-t border-gray-100 pt-6">
                    <p class="text-xs text-gray-500">
                        Sudah memiliki akun atau sudah di-ACC? 
                        <a href="{{ route('siswa.login') }}" class="text-sekunder font-medium hover:underline">Login di sini</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Kanan: Carousel Pengumuman -->
        <div class="hidden lg:flex lg:w-[40%] relative bg-sekunder overflow-hidden items-center justify-center p-12">
            <!-- Background Ornaments -->
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 mix-blend-overlay"></div>
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-primer/30 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-white/20 rounded-full blur-3xl"></div>
            
            <!-- Carousel AlpineJS -->
            <div x-data="{ 
                    activeSlide: 1, 
                    slides: {{ $banners->count() > 0 ? $banners->count() : 1 }},
                    init() {
                        if (this.slides > 1) {
                            setInterval(() => {
                                this.activeSlide = this.activeSlide === this.slides ? 1 : this.activeSlide + 1;
                            }, 5000);
                        }
                    }
                 }" 
                 class="relative z-10 w-full max-w-lg text-sekunder">
                 
                @forelse($banners as $index => $banner)
                <!-- Slide {{ $index + 1 }} -->
                <div x-show="activeSlide === {{ $index + 1 }}" x-transition.opacity.duration.500ms @if($index > 0) style="display: none;" @endif class="space-y-6">
                    <div class="w-16 h-16 bg-white/50 rounded-md flex items-center justify-center border border-white/60 mb-6 backdrop-blur-sm shadow-sm">
                        <svg class="w-8 h-8 text-sekunder" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h2 class="text-3xl font-medium leading-tight text-gray-900">{{ $banner->judul }}</h2>
                    <p class="text-gray-700 leading-relaxed text-sm">{{ $banner->deskripsi }}</p>
                </div>
                @empty
                <!-- Fallback Slide -->
                <div x-show="activeSlide === 1" x-transition.opacity.duration.500ms class="space-y-6">
                    <div class="w-16 h-16 bg-white/50 rounded-md flex items-center justify-center border border-white/60 mb-6 backdrop-blur-sm shadow-sm">
                        <svg class="w-8 h-8 text-sekunder" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h2 class="text-3xl font-medium leading-tight text-gray-900">Selamat Datang di LPK Saitama</h2>
                    <p class="text-gray-700 leading-relaxed text-sm">Persiapkan dirimu untuk ujian SSW dan JFT-Basic bersama kami.</p>
                </div>
                @endforelse

                <!-- Carousel Indicators -->
                <div class="flex gap-2 mt-10">
                    <template x-for="slide in Array.from({length: slides}, (_, i) => i + 1)" :key="slide">
                        <button @click="activeSlide = slide" 
                                :class="{'w-8 bg-primer': activeSlide === slide, 'w-2 bg-white/30 hover:bg-white/50': activeSlide !== slide}"
                                class="h-1.5 rounded-full transition-all duration-300"></button>
                    </template>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
