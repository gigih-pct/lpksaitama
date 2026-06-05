<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Kesiswaan LPK')</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/fav.png') }}">
    <!-- Google Fonts: Poppins (300, 400, 500) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8FAFC] text-gray-800 font-sans antialiased">

    @include('siswa.navbar')

    <div class="pt-16 pb-20 md:pb-0 flex">
        @include('siswa.sidebar')
        
        <main class="flex-1 md:ml-72 p-4 md:p-8 min-h-[calc(100vh-4rem)] w-full max-w-full overflow-hidden transition-all duration-300">
            @if(isset($dashboard_banners) && $dashboard_banners->count() > 0)
                <div x-data="{
                        init() {
                            let container = this.$refs.carousel;
                            let slideCount = {{ $dashboard_banners->count() }};
                            if(slideCount > 1) {
                                setInterval(() => {
                                    let maxScroll = container.scrollWidth - container.clientWidth;
                                    if (container.scrollLeft >= maxScroll - 10) {
                                        container.scrollTo({left: 0, behavior: 'smooth'});
                                    } else {
                                        container.scrollBy({left: container.clientWidth, behavior: 'smooth'});
                                    }
                                }, 7000);
                            }
                        }
                    }" 
                    x-ref="carousel"
                    class="mb-6 flex overflow-x-auto snap-x snap-mandatory scrollbar-hide scroll-smooth w-full rounded-md">
                    @foreach($dashboard_banners as $index => $banner)
                        <div class="bg-sekunder rounded-md p-4 text-white shadow-sm flex items-start gap-4 snap-start shrink-0 min-w-full border-r border-sekunder/50">
                            <div class="w-10 h-10 bg-white/20 rounded-md flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-sm">{{ $banner->judul }}</h4>
                                <p class="text-xs text-white/80 mt-1 leading-relaxed">{{ $banner->deskripsi }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>