<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-3">
                <span class="bg-blue-600 text-white p-2 rounded-xl text-sm shadow-md flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </span>
                <span>{{ __('Daftar Users') }}</span>
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
            
            <div class="bg-white/95 backdrop-blur-md rounded-2xl p-6 md:p-8 shadow-xl border border-white/60">
                
                <div class="mb-6">
                    <h3 class="text-xl font-extrabold text-gray-900">Manajemen Pengguna</h3>
                    <p class="text-xs text-gray-500 mt-1">Kelola hak akses, reset password, dan daftar seluruh pengguna terdaftar.</p>
                </div>

                @if (session('success'))
                    <div id="success-message" class="mb-6 p-4 bg-emerald-50/90 text-emerald-800 border border-emerald-200/80 rounded-xl flex items-center gap-3 shadow-sm">
                        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="font-bold text-sm">Berhasil!</p>
                            <p class="text-xs">{!! session('success') !!}</p>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div id="error-message" class="mb-6 p-4 bg-rose-50/90 text-rose-800 border border-rose-200/80 rounded-xl flex items-center gap-3 shadow-sm">
                        <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="font-bold text-sm">Gagal!</p>
                            <p class="text-xs">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <div class="border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm bg-white">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50/80">
                            <tr>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Nama
                                </th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Email
                                </th>
                                <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @foreach ($users as $user)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-blue-50 text-blue-600 rounded-xl">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                            <div class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                                <span>{{ $user->name }}</span>
                                                @if ($user->id === $currentUserId)
                                                    <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full bg-blue-100 text-blue-700 tracking-wide border border-blue-200">
                                                        Saya
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-slate-600 font-medium">{{ $user->email }}</span>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button" onclick="openResetModal({{ $user->id }}, '{{ $user->name }}')"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-lg text-xs font-semibold transition-all shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                                </svg>
                                                <span>Reset Password</span>
                                            </button>

                                            @if ($user->id !== $currentUserId)
                                                <button type="button" onclick="openDeleteModal({{ $user->id }}, '{{ $user->name }}')"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white rounded-lg text-xs font-semibold transition-all shadow-sm">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    <span>Delete</span>
                                                </button>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 text-slate-400 rounded-lg text-xs font-semibold cursor-not-allowed" title="Anda tidak bisa menghapus diri sendiri">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                    </svg>
                                                    <span>Delete</span>
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>

    {{-- MODAL KONFIRMASI (Reset Password) --}}
    <div id="reset-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl overflow-hidden shadow-2xl transform transition-all sm:max-w-lg sm:w-full border border-slate-100">
            <form id="reset-form" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="bg-white p-6">
                    <div class="sm:flex sm:items-start gap-4">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-xl bg-amber-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.398 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-bold text-slate-900" id="modal-reset-title">
                                Konfirmasi Reset Password
                            </h3>
                            <div class="mt-2 space-y-2">
                                <p class="text-sm text-slate-500">
                                    Anda yakin ingin mereset password untuk pengguna <span id="reset-user-name" class="font-bold text-slate-800"></span>?
                                </p>
                                <p class="text-xs text-rose-600 font-bold bg-rose-50 p-2.5 rounded-xl border border-rose-100">
                                    Password baru akan diset menjadi: <code class="bg-white px-1.5 py-0.5 rounded border border-rose-200">password123</code>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-slate-100">
                    <button type="submit" class="inline-flex justify-center rounded-xl border border-transparent px-4 py-2 bg-amber-500 text-sm font-semibold text-white hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 shadow-md transition-all">
                        Reset Password
                    </button>
                    <button type="button" onclick="closeResetModal()" class="inline-flex justify-center rounded-xl border border-slate-300 px-4 py-2 bg-white text-sm font-semibold text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-all">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL KONFIRMASI (Delete User) --}}
    <div id="delete-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl overflow-hidden shadow-2xl transform transition-all sm:max-w-lg sm:w-full border border-slate-100">
            <form id="delete-form" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="bg-white p-6">
                    <div class="sm:flex sm:items-start gap-4">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-xl bg-rose-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-bold text-slate-900" id="modal-delete-title">
                                Konfirmasi Hapus Pengguna
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-slate-500">
                                    Anda yakin ingin menghapus pengguna <span id="delete-user-name" class="font-bold text-slate-800"></span>? Tindakan ini tidak dapat dibatalkan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-slate-100">
                    <button type="submit" class="inline-flex justify-center rounded-xl border border-transparent px-4 py-2 bg-rose-600 text-sm font-semibold text-white hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 shadow-md transition-all">
                        Hapus Pengguna
                    </button>
                    <button type="button" onclick="closeDeleteModal()" class="inline-flex justify-center rounded-xl border border-slate-300 px-4 py-2 bg-white text-sm font-semibold text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-all">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openResetModal(userId, userName) {
            const modal = document.getElementById('reset-modal');
            const form = document.getElementById('reset-form');
            const userNameSpan = document.getElementById('reset-user-name');

            form.action = `/users/${userId}/reset-password`;
            userNameSpan.textContent = userName;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeResetModal() {
            const modal = document.getElementById('reset-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openDeleteModal(userId, userName) {
            const modal = document.getElementById('delete-modal');
            const form = document.getElementById('delete-form');
            const userNameSpan = document.getElementById('delete-user-name');

            form.action = `/users/${userId}`;
            userNameSpan.textContent = userName;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('delete-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const successMessage = document.getElementById('success-message');
            const errorMessage = document.getElementById('error-message');

            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.transition = 'opacity 1s';
                    successMessage.style.opacity = '0';
                    setTimeout(() => successMessage.remove(), 1000);
                }, 5000);
            }

            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.transition = 'opacity 1s';
                    errorMessage.style.opacity = '0';
                    setTimeout(() => errorMessage.remove(), 1000);
                }, 5000);
            }
        });
    </script>
</x-app-layout>