<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal PT Saitama</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/fav.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-800 bg-white">

    <div class="flex min-h-screen">
        
        <!-- Kiri: Pilihan Portal -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 lg:p-16">
            <div class="w-full max-w-md">
                
                <!-- Header -->
                <div class="mb-10 text-center lg:text-left">
                    <div class="inline-flex items-center justify-center md:justify-center gap-3 mb-6">
                        <img src="{{ asset('logo/logo_berdiri.webp') }}" alt="PT Saitama" class="h-20 lg:justify-start">
                    </div>
                    
                    <h1 class="text-3xl font-medium text-primer mb-2">PT Saitama</h1>
                    <p class="text-sm text-primer">Silakan pilih portal untuk masuk ke dalam sistem.</p>
                </div>

                <!-- Portal Buttons -->
                <div class="space-y-4">
                    <!-- Portal Siswa -->
                    <a href="{{ url('/siswa') }}" class="group flex items-center p-5 border-2 border-primer hover:bg-primer transition-colors duration-300">
                        <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center bg-primer text-white group-hover:bg-white group-hover:text-primer transition-colors duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-6 h-6">
                                <path stroke-linecap="square" stroke-linejoin="miter" d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="square" stroke-linejoin="miter" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                <path stroke-linecap="square" stroke-linejoin="miter" d="M12 14v6" />
                            </svg>
                        </div>
                        <div class="ml-4 text-left">
                            <h2 class="text-lg font-bold text-primer group-hover:text-white uppercase tracking-wide">Portal Siswa</h2>
                            <p class="text-xs text-primer group-hover:text-white mt-1">Akses materi, tugas, dan nilai akademik.</p>
                        </div>
                    </a>

                    <!-- Portal Sensei -->
                    <a href="{{ url('/sensei') }}" class="group flex items-center p-5 border-2 border-sekunder hover:bg-sekunder transition-colors duration-300">
                        <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center bg-sekunder text-white group-hover:bg-white group-hover:text-sekunder transition-colors duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-6 h-6">
                                <path stroke-linecap="square" stroke-linejoin="miter" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-4 text-left">
                            <h2 class="text-lg font-bold text-sekunder group-hover:text-white uppercase tracking-wide">Portal Sensei</h2>
                            <p class="text-xs text-sekunder group-hover:text-white mt-1">Kelola kelas, jadwal, evaluasi, dan absensi.</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Kanan: Banner Statis (Meniru Login) -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-primer overflow-hidden items-center justify-center p-12">
            <!-- Background Ornaments -->
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 mix-blend-overlay"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-sekunder/20 blur-3xl"></div>
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 blur-3xl"></div>
            
            <div class="relative z-10 w-full max-w-lg text-white">
                <div class="space-y-6">
                    <div class="w-16 h-16 bg-white/10 flex items-center justify-center border border-white/20 mb-6 backdrop-blur-sm">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h2 class="text-3xl font-medium leading-tight">Selamat Datang di PT Saitama</h2>
                    <p class="text-white/80 leading-relaxed text-sm">Pilih portal yang sesuai dengan peran Anda untuk masuk ke sistem dashboard terintegrasi.</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
