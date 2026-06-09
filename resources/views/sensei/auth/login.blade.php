<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sensei - LPK Saitama</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/fav.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-800 bg-white">

    <div class="flex min-h-screen">
        
        <!-- Kiri: Form Login -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 lg:p-16">
            <div class="w-full max-w-md">
                
                <!-- Header Form -->
                <div class="mb-10 text-center lg:text-left">
                    <div class="inline-flex items-center justify-center md:justify-center gap-3 mb-6">
                        <img src="{{ asset('logo/logo_berdiri.webp') }}" alt="LPK Saitama" class="h-20 lg:justify-start">
                    </div>
                    
                    <h1 class="text-3xl font-medium text-primer mb-2">Selamat Datang</h1>
                    <p class="text-sm text-gray-500">Silakan masuk menggunakan email dan password akun pengajar Anda.</p>
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

                <!-- Form Login -->
                <form action="{{ route('sensei.login') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label for="email" class="block text-xs font-medium text-gray-700 mb-1.5 uppercase tracking-wide">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full pl-10 pr-3 py-2.5 bg-gray-50 border border-gray-200 rounded-md focus:outline-none focus:border-primer focus:ring-1 focus:ring-primer focus:bg-white text-sm transition-colors" placeholder="nama@email.com" required autofocus>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <label for="password" class="block text-xs font-medium text-gray-700 uppercase tracking-wide">Password</label>
                            <a href="#" class="text-[10px] text-sekunder hover:underline font-medium">Lupa Password?</a>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <input type="password" id="password" name="password" class="w-full pl-10 pr-3 py-2.5 bg-gray-50 border border-gray-200 rounded-md focus:outline-none focus:border-primer focus:ring-1 focus:ring-primer focus:bg-white text-sm transition-colors" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="w-4 h-4 text-primer border-gray-300 rounded focus:ring-primer">
                        <label for="remember" class="ml-2 text-xs text-gray-600">Ingat Saya</label>
                    </div>

                    <button type="submit" class="w-full bg-primer hover:bg-primer/90 text-white font-medium py-3 rounded-md text-sm transition-colors shadow-sm mt-2">
                        Masuk
                    </button>
                </form>

                <div class="mt-8 text-center lg:text-left border-t border-gray-100 pt-6">
                    <p class="text-xs text-gray-500 mb-2">
                        Belum memiliki akun pengajar? 
                        <a href="{{ route('sensei.register') }}" class="text-sekunder font-medium hover:underline">Daftar di sini</a>
                    </p>
                    <p class="text-xs text-gray-500">
                        Mengalami kendala login? Silakan hubungi 
                        <a href="#" class="text-primer font-medium hover:underline">Admin Akademik</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Kanan: Carousel Pengumuman -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-primer overflow-hidden items-center justify-center p-12">
            <!-- Background Ornaments -->
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 mix-blend-overlay"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-sekunder/20 rounded-full blur-3xl"></div>
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
            
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
                 class="relative z-10 w-full max-w-lg text-white">
                 
                @forelse($banners as $index => $banner)
                <!-- Slide {{ $index + 1 }} -->
                <div x-show="activeSlide === {{ $index + 1 }}" x-transition.opacity.duration.500ms @if($index > 0) style="display: none;" @endif class="space-y-6">
                    <div class="w-16 h-16 bg-white/10 rounded-md flex items-center justify-center border border-white/20 mb-6 backdrop-blur-sm">
                        <!-- Default icon -->
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h2 class="text-3xl font-medium leading-tight">{{ $banner->judul }}</h2>
                    <p class="text-white/80 leading-relaxed text-sm">{{ $banner->deskripsi }}</p>
                </div>
                @empty
                <!-- Fallback Slide -->
                <div x-show="activeSlide === 1" x-transition.opacity.duration.500ms class="space-y-6">
                    <div class="w-16 h-16 bg-white/10 rounded-md flex items-center justify-center border border-white/20 mb-6 backdrop-blur-sm">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h2 class="text-3xl font-medium leading-tight">Selamat Datang di LPK Saitama</h2>
                    <p class="text-white/80 leading-relaxed text-sm">Portal pengajar resmi LPK Saitama.</p>
                </div>
                @endforelse

                <!-- Carousel Indicators -->
                <div class="flex gap-2 mt-10">
                    <template x-for="slide in Array.from({length: slides}, (_, i) => i + 1)" :key="slide">
                        <button @click="activeSlide = slide" 
                                :class="{'w-8 bg-sekunder': activeSlide === slide, 'w-2 bg-white/30 hover:bg-white/50': activeSlide !== slide}"
                                class="h-1.5 rounded-full transition-all duration-300"></button>
                    </template>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
