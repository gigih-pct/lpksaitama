@php
    $menuItems = [
        [
            'label' => 'Beranda', 
            'link' => route('siswa.beranda'), 
            'icon' => '<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>'
        ],
        [
            'label' => 'Pembelajaran', 
            'link' => route('siswa.pembelajaran'), 
            'icon' => '<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477-4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>'
        ],
        [
            'label' => 'Nilai', 
            'link' => route('siswa.nilai'), 
            'icon' => '<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>'
        ],
        [
            'label' => 'Evaluasi', 
            'link' => route('siswa.evaluasi'), 
            'icon' => '<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>'
        ],
        [
            'label' => 'Pembayaran', 
            'link' => route('siswa.pembayaran'), 
            'icon' => '<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>'
        ],
        [
            'label' => 'Berkas', 
            'link' => route('siswa.berkas'), 
            'icon' => '<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>'
        ],
        [
            'label' => 'Informasi', 
            'link' => route('siswa.informasi'), 
            'icon' => '<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
        ],
    ];
@endphp

<!-- Desktop Sidebar -->
<aside class="hidden md:flex flex-col w-72 h-[calc(100vh-4rem)] fixed left-0 top-16 bg-white border-r border-gray-100 z-30 shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
    <div class="flex-1 overflow-y-auto p-6 scrollbar-hide">
        <div class="flex flex-col items-center mb-8">
            <div class="relative">
                <div class="w-24 h-24 bg-primer p-[2px] mb-3 shadow-sm rounded-md">
                    <div class="w-full h-full bg-white flex items-center justify-center overflow-hidden border-2 border-white rounded-md">
                        <svg class="w-12 h-12 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                </div>
                <div class="absolute bottom-1 right-1 w-4 h-4 bg-tersier border-2 border-white rounded-md"></div>
            </div>
            <h3 class="font-medium text-lg text-primer tracking-tight">Nama Siswa</h3>
            <span class="text-xs font-medium bg-primer/10 text-primer px-3 py-1 mt-1 border border-primer/20 rounded-md">ID: 12345678</span>
        </div>

        <nav class="space-y-1.5">
            @foreach($menuItems as $index => $item)
                @php
                    $isActive = request()->url() == $item['link'];
                @endphp
                <a href="{{ $item['link'] }}" class="flex items-center px-4 py-3 text-sm font-medium transition-all duration-200 group rounded-md {{ $isActive ? 'bg-primer text-white shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-primer' }}">
                    <div class="{{ $isActive ? 'text-white' : 'text-gray-400 group-hover:text-primer' }} transition-colors">
                        {!! $item['icon'] !!}
                    </div>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>
    </div>
</aside>

<!-- Mobile Bottom Nav & Menu Wrapper -->
<div x-data="{ mobileMenuOpen: false }" class="md:hidden">
    <!-- Mobile Bottom Nav -->
    <nav class="fixed bottom-0 w-full bg-white/95 backdrop-blur-lg border-t border-gray-200 z-40 flex justify-between px-2 pt-1 pb-safe shadow-[0_-4px_20px_-10px_rgba(0,0,0,0.1)]">
        @foreach(array_slice($menuItems, 0, 4) as $index => $item)
            @php
                $isActive = request()->url() == $item['link'];
            @endphp
            <a href="{{ $item['link'] }}" class="flex flex-col items-center justify-center w-full py-2 relative group">
                <div class="{{ $isActive ? 'text-primer' : 'text-gray-400 group-hover:text-primer' }} transition-colors mb-1">
                    {!! str_replace('mr-3', 'mb-0.5', $item['icon']) !!}
                </div>
                <span class="text-[10px] font-medium {{ $isActive ? 'text-primer' : 'text-gray-500 group-hover:text-primer' }} transition-colors">{{ $item['label'] }}</span>
                @if($isActive)
                    <span class="absolute top-0 w-8 h-1 bg-primer rounded-b-md"></span>
                @endif
            </a>
        @endforeach
        
        <!-- Menu Lainnya Button -->
        <button @click="mobileMenuOpen = true" class="flex flex-col items-center justify-center w-full py-2 text-gray-400 hover:text-primer group relative">
            <svg class="w-5 h-5 mb-1.5 group-hover:text-primer transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            <span class="text-[10px] font-medium text-gray-500 group-hover:text-primer transition-colors">Lainnya</span>
        </button>
    </nav>

    <!-- Backdrop -->
    <div x-cloak x-show="mobileMenuOpen" 
         x-transition.opacity.duration.300ms
         @click="mobileMenuOpen = false"
         class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-[50]"></div>
         
    <!-- Bottom Sheet -->
    <div x-cloak x-show="mobileMenuOpen"
         x-transition:enter="transform transition ease-out duration-300"
         x-transition:enter-start="translate-y-full"
         x-transition:enter-end="translate-y-0"
         x-transition:leave="transform transition ease-in duration-200"
         x-transition:leave-start="translate-y-0"
         x-transition:leave-end="translate-y-full"
         class="fixed inset-x-0 bottom-0 bg-white rounded-t-md z-[60] p-6 shadow-2xl pb-safe">
         
        <div class="w-12 h-1.5 bg-gray-200 rounded-md mx-auto mb-6"></div>
        
        <div class="flex justify-between items-center mb-6 px-2">
            <h4 class="font-medium text-primer text-lg">Menu Lainnya</h4>
            <button @click="mobileMenuOpen = false" class="p-2 bg-gray-50 text-gray-500 hover:bg-gray-100 rounded-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <div class="grid grid-cols-3 gap-4">
            @foreach(array_slice($menuItems, 4) as $item)
                @php
                    $isActive = request()->url() == $item['link'];
                @endphp
                <a href="{{ $item['link'] }}" class="flex flex-col items-center justify-start gap-2 p-3 hover:bg-gray-50 active:bg-gray-100 transition-colors border {{ $isActive ? 'border-primer/20 bg-primer/5' : 'border-transparent hover:border-gray-100' }} rounded-md">
                    <div class="w-12 h-12 bg-primer/5 text-primer flex items-center justify-center rounded-md">
                        {!! str_replace('mr-3', '', $item['icon']) !!}
                    </div>
                    <span class="text-xs font-medium {{ $isActive ? 'text-primer' : 'text-gray-700' }} text-center leading-tight">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>
        
        <div class="mt-8 px-2">
            <form method="POST" action="">
                @csrf
                <button type="submit" class="flex items-center justify-center w-full py-3 px-4 bg-sekunder text-white font-medium hover:bg-sekunder/90 transition-colors rounded-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar dari Akun
                </button>
            </form>
        </div>
    </div>
</div>