<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <!-- Judul & Ikon Dashboard -->
            <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-3">
                <span class="bg-blue-600 text-white p-2 rounded-xl text-sm shadow-md flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </span>
                <span>{{ __('Dashboard Guru') }}</span>
            </h2>

            <!-- Badge Platform -->
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold px-3.5 py-1.5 bg-blue-600 text-white rounded-full shadow-sm tracking-wide">
                    SoalKita SaaS Platform
                </span>
            </div>
        </div>
    </x-slot>

    <!-- Container Utama dengan Background & Overlay yang Dioptimalkan -->
    <div class="relative min-h-screen py-8 bg-cover bg-center bg-no-repeat bg-fixed" 
         style="background-image: url('https://images.unsplash.com/photo-1580582932707-520aed937b7b?q=80&w=1920&auto=format&fit=crop');">
        
        <!-- Overlay Ditingkatkan dari bg-slate-900/40 ke bg-slate-900/65 & backdrop-blur dipertegas -->
        <div class="absolute inset-0 bg-slate-900/65 backdrop-blur-sm"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Banner Sapaan -->
            <div class="bg-white/95 backdrop-blur-md rounded-2xl p-6 md:p-8 shadow-xl border border-white/80">
                <p class="text-blue-600 text-xs font-extrabold tracking-wider uppercase">Selamat Datang Kembali</p>
                <h3 class="text-3xl font-extrabold text-gray-900 mt-1">
                    {{ $userName }}
                </h3>
                <p class="text-gray-600 text-sm mt-2 max-w-2xl leading-relaxed">
                    Kelola kelas, buat bank soal, dan pantau hasil evaluasi pengerjaan kuis siswa Anda secara <span class="font-semibold text-blue-600">real-time</span> di sini.
                </p>
            </div>

            <!-- Ringkasan Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Card 1: Total Kelas -->
                <div class="bg-white/95 backdrop-blur-md p-6 rounded-2xl shadow-lg border border-white/80 hover:shadow-2xl hover:-translate-y-1 transition-all duration-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Kelas Anda</p>
                        <p class="text-4xl font-extrabold text-gray-900 mt-2">{{ $jumlahKelas }}</p>
                        <span class="inline-block text-xs font-semibold text-blue-700 bg-blue-100 px-2.5 py-1 rounded-full mt-3">Aktif Terdaftar</span>
                    </div>
                    <div class="p-4 bg-blue-100 text-blue-600 rounded-2xl shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                </div>

                <!-- Card 2: Kuis Aktif -->
                <div class="bg-white/95 backdrop-blur-md p-6 rounded-2xl shadow-lg border border-white/80 hover:shadow-2xl hover:-translate-y-1 transition-all duration-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Kuis Aktif</p>
                        <p class="text-4xl font-extrabold text-emerald-600 mt-2">{{ $terbitanAktif }}</p>
                        <span class="inline-block text-xs font-semibold text-emerald-700 bg-emerald-100 px-2.5 py-1 rounded-full mt-3">Dapat Dikerjakan</span>
                    </div>
                    <div class="p-4 bg-emerald-100 text-emerald-600 rounded-2xl shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>

                <!-- Card 3: Kuis Ditutup -->
                <div class="bg-white/95 backdrop-blur-md p-6 rounded-2xl shadow-lg border border-white/80 hover:shadow-2xl hover:-translate-y-1 transition-all duration-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Kuis Selesai / Expired</p>
                        <p class="text-4xl font-extrabold text-rose-600 mt-2">{{ $terbitanDitutup }}</p>
                        <span class="inline-block text-xs font-semibold text-rose-700 bg-rose-100 px-2.5 py-1 rounded-full mt-3">Selesai/Tutup</span>
                    </div>
                    <div class="p-4 bg-rose-100 text-rose-600 rounded-2xl shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>