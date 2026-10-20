<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <!-- Judul & Ikon Header -->
            <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-3">
                <span class="bg-blue-600 text-white p-2.5 rounded-xl shadow-md flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </span>
                <span>{{ __('Form Terbitkan Tugas') }}</span>
            </h2>

            <!-- Badge Platform -->
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold px-3.5 py-1.5 bg-blue-600 text-white rounded-full shadow-sm tracking-wide">
                    SoalKita SaaS Platform
                </span>
            </div>
        </div>
    </x-slot>

    <!-- Container Utama dengan Background Gambar & Overlay -->
    <div class="relative min-h-[calc(100vh-4rem)] py-10 bg-cover bg-center bg-no-repeat" 
         style="background-image: url('https://images.unsplash.com/photo-1580582932707-520aed937b7b?q=80&w=1920&auto=format&fit=crop');">
        
        <!-- Overlay dengan pointer-events-none agar klik menembus ke form -->
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-[2px] pointer-events-none"></div>

        <div class="relative z-20 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Card Utama (Glassmorphism) -->
            <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-white/60 overflow-hidden">
                
                <!-- Card Header / Title Section -->
                <div class="p-6 md:p-8 bg-slate-50/50 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900">Konfigurasi Penerbitan</h3>
                        <p class="text-xs text-slate-500 mt-1">Lengkapi formulir di bawah ini untuk menerbitkan tugas baru ke siswa.</p>
                    </div>
                    <a href="{{ route('terbitkantugas.index') }}" 
                       class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 border border-slate-200/80 rounded-xl text-xs font-bold transition-all shadow-sm shrink-0">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>

                <!-- Form Content -->
                <form method="POST" action="{{ route('terbitkantugas.store') }}" class="p-6 md:p-8 space-y-6">
                    @csrf

                    <!-- Field 1: Pilih Kelas -->
                    <div class="space-y-1.5">
                        <label for="kelas_id" class="block font-bold text-xs uppercase tracking-wider text-slate-700">
                            Pilih Kelas <span class="text-rose-500">*</span>
                        </label>
                        <select name="kelas_id" id="kelas_id" required
                            class="w-full px-4 py-3 rounded-xl border-slate-200 text-slate-800 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all shadow-sm relative z-30 cursor-pointer">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelas as $k)
                                <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelas_id')
                            <p class="text-xs font-medium text-rose-600 flex items-center gap-1.5 pt-1">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Field 2: Pilih Tugas -->
                    <div class="space-y-1.5">
                        <label for="tugas_id" class="block font-bold text-xs uppercase tracking-wider text-slate-700">
                            Pilih Tugas <span class="text-rose-500">*</span>
                        </label>
                        <select name="tugas_id" id="tugas_id" required disabled
                            class="w-full px-4 py-3 rounded-xl border-slate-200 text-slate-800 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all shadow-sm bg-slate-100/80 cursor-not-allowed relative z-30">
                            <option value="">-- Pilih Tugas (Pilih Kelas Dulu) --</option>
                        </select>
                        
                        <!-- Sumber Data Tersembunyi untuk JS -->
                        <div id="tugas-data-source" class="hidden">
                            @foreach ($tugas as $t)
                                <div class="tugas-item" 
                                     data-id="{{ $t->id }}" 
                                     data-kelas-id="{{ $t->kelas_id }}"
                                     data-label="[{{ $t->jenis_tugas }}] - {{ $t->nama_tugas }}">
                                </div>
                            @endforeach
                        </div>

                        <p class="text-xs text-slate-500 flex items-center gap-1.5 pt-1">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Pilih kelas terlebih dahulu untuk melihat daftar tugas yang sesuai.</span>
                        </p>
                        @error('tugas_id')
                            <p class="text-xs font-medium text-rose-600 flex items-center gap-1.5 pt-1">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Field 3: Durasi Pengerjaan -->
                    <div class="space-y-1.5">
                        <label for="durasi_menit" class="block font-bold text-xs uppercase tracking-wider text-slate-700">
                            Durasi Pengerjaan (Menit, Opsional)
                        </label>
                        <input type="number" name="durasi_menit" id="durasi_menit"
                            value="{{ old('durasi_menit') }}" min="1" placeholder="Cth: 60"
                            class="w-full px-4 py-3 rounded-xl border-slate-200 text-slate-800 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all shadow-sm relative z-30">
                        <p class="text-xs text-slate-500 flex items-center gap-1.5 pt-1">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Masukkan durasi dalam menit. Kosongkan jika waktu pengerjaan tidak dibatasi.</span>
                        </p>
                        @error('durasi_menit')
                            <p class="text-xs font-medium text-rose-600 flex items-center gap-1.5 pt-1">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Action Button -->
                    <div class="pt-6 border-t border-slate-100 flex items-center justify-end">
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 relative z-30 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            <span>Terbitkan Tugas Sekarang</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Script Filter Tugas Berdasarkan Kelas -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const kelasSelect = document.getElementById('kelas_id');
            const tugasSelect = document.getElementById('tugas_id');
            const tugasItems = document.querySelectorAll('#tugas-data-source .tugas-item');

            function filterTugas() {
                const selectedKelasId = kelasSelect.value;

                tugasSelect.innerHTML = '<option value="">-- Pilih Tugas --</option>';
                
                if (selectedKelasId) {
                    let tasksFound = false;

                    tugasItems.forEach(item => {
                        if (item.dataset.kelasId === selectedKelasId) {
                            const opt = document.createElement('option');
                            opt.value = item.dataset.id;
                            
                            let labelText = item.dataset.label;
                            labelText = labelText.replace(/\s*\(Kelas ID: \d+\)$/, '');
                            opt.textContent = labelText;

                            tugasSelect.appendChild(opt);
                            tasksFound = true;
                        }
                    });

                    if (tasksFound) {
                        tugasSelect.disabled = false;
                        tugasSelect.classList.remove('bg-slate-100/80', 'cursor-not-allowed');
                        tugasSelect.classList.add('bg-white', 'cursor-pointer');
                    } else {
                        tugasSelect.innerHTML = '<option value="">-- Tidak ada tugas baru untuk kelas ini --</option>';
                        tugasSelect.disabled = true;
                        tugasSelect.classList.add('bg-slate-100/80', 'cursor-not-allowed');
                        tugasSelect.classList.remove('bg-white', 'cursor-pointer');
                    }
                } else {
                    tugasSelect.innerHTML = '<option value="">-- Pilih Tugas (Pilih Kelas Dulu) --</option>';
                    tugasSelect.disabled = true;
                    tugasSelect.classList.add('bg-slate-100/80', 'cursor-not-allowed');
                    tugasSelect.classList.remove('bg-white', 'cursor-pointer');
                }
            }

            kelasSelect.addEventListener('change', filterTugas);

            // Jika ada data awal saat reload/validasi gagal
            if (kelasSelect.value) {
                filterTugas();
                @if (old('tugas_id'))
                    tugasSelect.value = "{{ old('tugas_id') }}";
                @endif
            }
        });
    </script>
</x-app-layout>