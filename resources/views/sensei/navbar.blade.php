<nav class="fixed top-0 w-full h-16 bg-primer border-b border-white/10 flex items-center justify-between px-4 md:px-6 z-40 shadow-sm" 
     x-data="{ open: false }">
    
    <div class="flex items-center gap-3">
        <img src="{{ asset('logo/logo.png') }}" alt="Logo" class="h-8 w-auto">
    </div>
    
    <div class="relative">
        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 focus:outline-none hover:bg-white/10 p-1.5 pr-2 rounded-md transition-colors">
            <div class="w-8 h-8 bg-white/20 border border-white/30 flex items-center justify-center overflow-hidden rounded-md">
                <svg class="w-5 h-5 text-white/80" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            </div>
            <span class="text-sm font-medium text-white hidden md:block">
                {{ Auth::guard('sensei')->check() ? explode(' ', Auth::guard('sensei')->user()->nama_lengkap)[0] : 'Sensei' }}
            </span>
            <svg class="w-4 h-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </button>

        <div x-cloak x-show="open" 
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute right-0 mt-2 w-56 bg-white border border-gray-100 shadow-lg py-2 z-50 rounded-md">
            
            <div class="px-4 py-3 border-b border-gray-50 mb-1">
                <p class="text-sm font-medium text-primer">{{ Auth::guard('sensei')->check() ? Auth::guard('sensei')->user()->nama_lengkap : 'Sensei' }}</p>
                <p class="text-xs text-gray-500 truncate mt-0.5">{{ Auth::guard('sensei')->check() ? Auth::guard('sensei')->user()->email : 'sensei@lpk-saitama.com' }}</p>
            </div>
            
            <a href="{{ route('sensei.profil') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-primer/5 hover:text-primer transition-colors">
                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Profil Saya
            </a>
            <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-primer/5 hover:text-primer transition-colors">
                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Pengaturan
            </a>
            
            <div class="h-px bg-gray-50 my-1"></div>
            
            <form method="POST" action="{{ route('sensei.logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-sekunder hover:bg-sekunder/5 transition-colors">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar
                </button>
            </form>
        </div>
    </div>
</nav>