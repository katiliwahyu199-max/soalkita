<!-- Overlay Mobile Sidebar -->
<div x-show="mobileSidebarOpen" @click="mobileSidebarOpen = false"
    class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm sm:hidden"
    x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>
</div>

<!-- Mobile Sidebar -->
<div x-show="mobileSidebarOpen" class="fixed inset-y-0 left-0 z-50 flex flex-col w-[min(18rem,calc(100vw-2rem))] bg-slate-50 sm:hidden text-slate-800 shadow-2xl border-r border-slate-200/80"
    x-transition:enter="transition-transform ease-out duration-300" x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0" x-transition:leave="transition-transform ease-in duration-200"
    x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" x-cloak>
    
    <!-- Header Sidebar Mobile dengan Logo SoalKita -->
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200/80 bg-white">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <img src="{{ asset('img/soalkita_logo.jpeg') }}" alt="Logo SoalKita" class="h-9 w-9 rounded-lg object-cover shadow-sm">
            <span class="text-slate-900 font-extrabold text-xl tracking-wide">Soal<span class="text-blue-600">Kita</span></span>
        </a>
        <button @click="mobileSidebarOpen = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Navigasi Mobile -->
    <nav class="app-scrollbar flex-1 overflow-y-auto p-4 space-y-1.5">
        @unless (Auth::user()->isAdmin())
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all"
            :class="route().current('dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' : 'text-slate-600 hover:bg-slate-200/60 hover:text-slate-900'">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            <span>Dashboard</span>
        </a>
        @endunless

        @if (Auth::user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all"
                :class="route().current('admin.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' : 'text-slate-600 hover:bg-slate-200/60 hover:text-slate-900'">
                <span class="w-5 text-center font-bold">A</span>
                <span>Dashboard Admin</span>
            </a>
            <a href="{{ route('admin.subscriptions.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all"
                :class="route().current('admin.subscriptions.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' : 'text-slate-600 hover:bg-slate-200/60 hover:text-slate-900'">
                <span class="w-5 text-center font-bold">Rp</span>
                <span>Kelola Langganan</span>
            </a>
        @else
            <a href="{{ route('subscription.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all"
                :class="route().current('subscription.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' : 'text-slate-600 hover:bg-slate-200/60 hover:text-slate-900'">
                <span class="w-5 text-center font-bold">Rp</span>
                <span>Langganan</span>
            </a>
        @endif

        @unless (Auth::user()->isAdmin())
        <!-- Kelas & Tugas -->
        <a href="{{ route('kelas.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all"
            :class="route().current('kelas.index') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' : 'text-slate-600 hover:bg-slate-200/60 hover:text-slate-900'">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
            </svg>
            <span>Kelas & Tugas</span>
        </a>

        <!-- Terbitkan Tugas -->
        <a href="{{ route('terbitkantugas.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all"
            :class="route().current('terbitkantugas.index') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' : 'text-slate-600 hover:bg-slate-200/60 hover:text-slate-900'">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>Terbitkan Tugas</span>
        </a>
        @endunless
    </nav>
</div>


<!-- Desktop Sidebar -->
<div class="hidden sm:flex sm:flex-col bg-slate-50 border-r border-slate-200/80 transition-all duration-300 ease-in-out text-slate-800"
    :class="desktopSidebarCollapsed ? 'w-20' : 'w-64'">
    
    <!-- Header Desktop dengan Logo SoalKita -->
    <div class="flex items-center justify-center h-[65px] border-b border-slate-200/80 px-4 bg-white">
        <a href="{{ route('dashboard') }}" class="flex items-center justify-center">
            <!-- Tampilan Saat Sidebar Collapsed (Hanya Logo) -->
            <div :class="desktopSidebarCollapsed ? 'block' : 'hidden'">
                <img src="{{ asset('img/soalkita_logo.jpeg') }}" alt="Logo SoalKita" class="h-9 w-9 rounded-lg object-cover shadow-sm">
            </div>
            <!-- Tampilan Saat Sidebar Extended (Logo + Nama Brand) -->
            <div class="flex items-center gap-3 transition-all" :class="desktopSidebarCollapsed ? 'hidden' : 'flex'">
                <img src="{{ asset('img/soalkita_logo.jpeg') }}" alt="Logo SoalKita" class="h-9 w-9 rounded-lg object-cover shadow-sm">
                <span class="text-slate-900 font-extrabold text-xl tracking-wide">Soal<span class="text-blue-600">Kita</span></span>
            </div>
        </a>
    </div>

    <!-- Navigasi Desktop -->
    <nav class="app-scrollbar flex-1 overflow-y-auto overflow-x-hidden p-4 space-y-2">
        @unless (Auth::user()->isAdmin())
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all"
            :class="[
                desktopSidebarCollapsed ? 'justify-center' : '',
                route().current('dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' :
                'text-slate-600 hover:bg-slate-200/60 hover:text-slate-900'
            ]">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            <span :class="desktopSidebarCollapsed ? 'hidden' : 'block'">Dashboard</span>
        </a>
        @endunless

        @if (Auth::user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all"
                :class="[
                    desktopSidebarCollapsed ? 'justify-center' : '',
                    route().current('admin.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' :
                    'text-slate-600 hover:bg-slate-200/60 hover:text-slate-900'
                ]">
                <span class="w-5 text-center font-bold">A</span>
                <span :class="desktopSidebarCollapsed ? 'hidden' : 'block'">Dashboard Admin</span>
            </a>
            <a href="{{ route('admin.subscriptions.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all"
                :class="[
                    desktopSidebarCollapsed ? 'justify-center' : '',
                    route().current('admin.subscriptions.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' :
                    'text-slate-600 hover:bg-slate-200/60 hover:text-slate-900'
                ]">
                <span class="w-5 text-center font-bold">Rp</span>
                <span :class="desktopSidebarCollapsed ? 'hidden' : 'block'">Kelola Langganan</span>
            </a>
        @else
            <a href="{{ route('subscription.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all"
                :class="[
                    desktopSidebarCollapsed ? 'justify-center' : '',
                    route().current('subscription.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' :
                    'text-slate-600 hover:bg-slate-200/60 hover:text-slate-900'
                ]">
                <span class="w-5 text-center font-bold">Rp</span>
                <span :class="desktopSidebarCollapsed ? 'hidden' : 'block'">Langganan</span>
            </a>
        @endif

        @unless (Auth::user()->isAdmin())
        <!-- Kelas & Tugas -->
        <a href="{{ route('kelas.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all"
            :class="[
                desktopSidebarCollapsed ? 'justify-center' : '',
                route().current('kelas.index') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' :
                'text-slate-600 hover:bg-slate-200/60 hover:text-slate-900'
            ]">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
            </svg>
            <span :class="desktopSidebarCollapsed ? 'hidden' : 'block'">Kelas & Tugas</span>
        </a>

        <!-- Terbitkan Tugas -->
        <a href="{{ route('terbitkantugas.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all"
            :class="[
                desktopSidebarCollapsed ? 'justify-center' : '',
                route().current('terbitkantugas.index') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' :
                'text-slate-600 hover:bg-slate-200/60 hover:text-slate-900'
            ]">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span :class="desktopSidebarCollapsed ? 'hidden' : 'block'">Terbitkan Tugas</span>
        </a>
        @endunless
    </nav>

    <!-- Footer Sidebar Toggle -->
    <div class="p-4 border-t border-slate-200/80 bg-white/50">
        <button @click="desktopSidebarCollapsed = !desktopSidebarCollapsed"
            class="flex items-center justify-center w-full px-3 py-2.5 rounded-xl text-slate-500 hover:bg-slate-200/60 hover:text-slate-800 transition-colors">
            <svg :class="desktopSidebarCollapsed ? 'hidden' : 'block'" class="w-5 h-5" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
            </svg>
            <svg :class="desktopSidebarCollapsed ? 'block' : 'hidden'" class="w-5 h-5" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
            </svg>
            <span class="sr-only">Toggle Sidebar</span>
        </button>
    </div>
</div>