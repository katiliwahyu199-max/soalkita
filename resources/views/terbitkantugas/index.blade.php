<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-3">
                <span class="bg-blue-600 text-white p-2 rounded-xl text-sm shadow-md flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </span>
                <span>{{ __('Daftar Tugas Terbit') }}</span>
            </h2>

            <div class="flex items-center gap-3">
                <span class="text-xs font-bold px-3.5 py-1.5 bg-blue-600 text-white rounded-full shadow-sm tracking-wide mr-2">
                    SoalKita SaaS Platform
                </span>
            </div>
        </div>
    </x-slot>

    <div class="relative min-h-screen py-8 bg-cover bg-center bg-no-repeat" 
         style="background-image: url('https://images.unsplash.com/photo-1580582932707-520aed937b7b?q=80&w=1920&auto=format&fit=crop');">
        
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-[2px]"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            @if (session('success'))
                <div id="success-message" class="p-4 bg-emerald-50/90 text-emerald-800 border border-emerald-200/80 rounded-2xl flex items-center gap-3 shadow-md backdrop-blur-md">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="font-bold text-sm">Berhasil!</p>
                        <p class="text-xs">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div class="bg-white/95 backdrop-blur-md rounded-2xl p-6 md:p-8 shadow-xl border border-white/60">
                
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-6 border-b border-slate-100">
                    <a href="{{ route('terbitkantugas.create') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Terbitkan Tugas Baru
                    </a>

                    @if ($terbitanDitutup->isNotEmpty())
                        <button id="toggleHistoryBtn" type="button"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all shadow-sm">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Lihat Riwayat Tugas (Expired)
                        </button>
                    @endif
                </div>

                <div class="mb-4">
                    <h3 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse"></span>
                        Tugas yang Sedang Aktif
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">Daftar tugas yang saat ini dapat diakses oleh siswa.</p>
                </div>

                @if ($terbitanAktif->isEmpty())
                    <div class="p-8 text-center bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                        <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-slate-500 text-sm font-medium">Belum ada tugas yang diterbitkan dan berstatus <strong class="text-slate-700">Aktif</strong>.</p>
                    </div>
                @else
                    <div class="border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm bg-white">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                @include('terbitkantugas._table_header')
                                <tbody class="bg-white divide-y divide-slate-100">
                                    @foreach ($terbitanAktif as $terbitan)
                                        @include('terbitkantugas._table_row', ['terbitan' => $terbitan])
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-4">
                        {{ $terbitanAktif->links() }}
                    </div>
                @endif

                @if ($terbitanDitutup->isNotEmpty())
                    <div id="historyTableContainer" class="mt-10 pt-8 border-t border-slate-200/80 hidden">
                        <div class="mb-4">
                            <h3 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                                <span class="w-2.5 h-2.5 bg-rose-500 rounded-full"></span>
                                Riwayat Tugas (Tidak Aktif)
                            </h3>
                            <p class="text-xs text-slate-500 mt-1">Daftar tugas yang telah kedaluwarsa atau dinonaktifkan.</p>
                        </div>
                        <div class="border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm bg-white">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-200">
                                    @include('terbitkantugas._table_header')
                                    <tbody class="bg-white divide-y divide-slate-100">
                                        @foreach ($terbitanDitutup as $terbitan)
                                            @include('terbitkantugas._table_row', [
                                                'terbitan' => $terbitan,
                                            ])
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <!-- Modal Action -->
    <div id="actionModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl overflow-hidden shadow-2xl transform transition-all sm:max-w-md sm:w-full border border-slate-100">
            <div class="p-6">
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900" id="modalTitle">
                            Aksi Tugas: <span id="modalTaskName" class="text-blue-600"></span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-1">
                            Status saat ini: <span id="modalTaskStatus" class="font-bold text-slate-700"></span>
                        </p>
                    </div>
                    <button type="button" id="closeModalCross" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="space-y-3 pt-2">
                    <!-- Lihat Detail Tugas -->
                    <a id="lihatDetailBtn" href="#"
                        style="background-color: #2563eb; color: #ffffff;"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 font-semibold rounded-xl hover:bg-blue-700 text-xs transition-all shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Lihat Detail Tugas
                    </a>

                    <!-- Form Buat Token Baru (Biru) -->
                    <form id="reUseForm" method="POST" class="hidden w-full">
                        @csrf
                        <button type="submit" id="reUseBtn"
                            style="background-color: #2563eb; color: #ffffff;"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 font-semibold rounded-xl hover:bg-blue-700 text-xs transition-all shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Buat Token Baru
                        </button>
                    </form>

                    <!-- Form Nonaktifkan Tugas (Biru) -->
                    <form id="deactivateForm" method="POST" class="hidden w-full">
                        @csrf
                        <button type="submit" id="deactivateBtn"
                            style="background-color: #2563eb; color: #ffffff;"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 font-semibold rounded-xl hover:bg-blue-700 text-xs transition-all shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                            Nonaktifkan Tugas
                        </button>
                    </form>

                    <!-- Tombol Tutup Modal -->
                    <button type="button" id="closeModalBtn"
                        style="background-color: #f1f5f9; color: #334155;"
                        class="w-full px-4 py-2.5 font-semibold rounded-xl hover:bg-slate-200 text-xs transition-all">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle Riwayat
            const toggleBtn = document.getElementById('toggleHistoryBtn');
            const historyContainer = document.getElementById('historyTableContainer');

            if (toggleBtn && historyContainer) {
                toggleBtn.addEventListener('click', function() {
                    if (historyContainer.classList.contains('hidden')) {
                        historyContainer.classList.remove('hidden');
                        toggleBtn.textContent = 'Sembunyikan Riwayat Tugas (Expired)';
                    } else {
                        historyContainer.classList.add('hidden');
                        toggleBtn.textContent = 'Lihat Riwayat Tugas (Expired)'; 
                    }
                });
            }

            // Logic Modal
            const modal = document.getElementById('actionModal');
            const closeModalBtn = document.getElementById('closeModalBtn');
            const closeModalCross = document.getElementById('closeModalCross');
            const modalTaskName = document.getElementById('modalTaskName');
            const modalTaskStatus = document.getElementById('modalTaskStatus');
            const lihatDetailBtn = document.getElementById('lihatDetailBtn');

            const reUseForm = document.getElementById('reUseForm');
            const deactivateForm = document.getElementById('deactivateForm'); 

            const reUseRouteTemplate = "{{ route('terbitkantugas.reUse', 'TASK_ID') }}";
            const deactivateRouteTemplate = "{{ route('terbitkantugas.deactivate', 'TASK_ID') }}";
            const showRouteTemplate = "{{ route('terbitkantugas.show', 'TASK_ID') }}";

            document.querySelectorAll('.open-detail-modal').forEach(button => {
                button.addEventListener('click', function() {
                    const taskId = this.dataset.id;
                    const taskStatus = this.dataset.status;
                    const taskName = this.dataset.tugasNama;

                    modalTaskName.textContent = taskName;
                    modalTaskStatus.textContent = taskStatus === 'aktif' ? 'Active' : 'Expired';

                    lihatDetailBtn.href = showRouteTemplate.replace('TASK_ID', taskId);

                    reUseForm.classList.add('hidden');
                    deactivateForm.classList.add('hidden'); 

                    if (taskStatus === 'aktif') {
                        reUseForm.classList.remove('hidden');
                        reUseForm.action = reUseRouteTemplate.replace('TASK_ID', taskId);
                        reUseForm.onsubmit = () => confirm(
                            'Apakah Anda yakin ingin menonaktifkan tugas ini dan menerbitkannya kembali dengan Token Akses baru?'
                        );

                        deactivateForm.classList.remove('hidden');
                        deactivateForm.action = deactivateRouteTemplate.replace('TASK_ID', taskId);
                        deactivateForm.onsubmit = () => confirm(
                            'Apakah Anda yakin ingin menonaktifkan tugas me-deaktivasi ini? Status akan menjadi Expired.'
                        );
                    }

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
            });

            const hideModal = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            };

            closeModalBtn.addEventListener('click', hideModal);
            if(closeModalCross) closeModalCross.addEventListener('click', hideModal);
            
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    hideModal();
                }
            });

            // Auto Hide Success Alert
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.transition = 'opacity 1s';
                    successMessage.style.opacity = '0';
                    setTimeout(() => successMessage.remove(), 1000);
                }, 5000);
            }
        });
    </script>
</x-app-layout>