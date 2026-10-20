<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-3">
                <span class="bg-blue-600 text-white p-2 rounded-xl text-sm shadow-md flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    </svg>
                </span>
                <span>{{ __('Daftar Kelas') }}</span>
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

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6"
             x-data="{ addModal: false, editModal: false, deleteModal: false, editData: { id: '', nama_kelas: '' }, deleteId: null }">
            
            <div class="bg-white/95 backdrop-blur-md rounded-2xl p-6 md:p-8 shadow-xl border border-white/60">
                
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-xl font-extrabold text-gray-900">Manajemen Kelas</h3>
                        <p class="text-xs text-gray-500 mt-1">Kelola seluruh grup dan daftar kelas yang terdaftar di platform Anda.</p>
                    </div>
                    <button @click="addModal = true"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-wider hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-md transition duration-150">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Buat Kelas Baru
                    </button>
                </div>

                @if (session('success'))
                    <div class="mb-6 p-4 bg-emerald-50/90 text-emerald-800 border border-emerald-200/80 rounded-xl flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-rose-50/90 text-rose-800 border border-rose-200/80 rounded-xl">
                        <div class="flex items-center gap-2 font-bold text-sm mb-1">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Terjadi Kesalahan:
                        </div>
                        <ul class="list-disc list-inside text-xs space-y-1 text-rose-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm bg-white">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50/80">
                            <tr>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-16">
                                    No
                                </th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Nama Kelas
                                </th>
                                <th scope="col" class="px-6 py-3.5 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @forelse ($kelas as $item)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-600">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2.5">
                                            <span class="p-1.5 bg-blue-50 text-blue-600 rounded-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                            </span>
                                            <span class="text-sm font-bold text-slate-800">{{ $item->nama_kelas }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('kelas.detail', $item->id) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg text-xs font-semibold transition-all">
                                            <span>Detail</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-10 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <svg class="w-12 h-12 mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                            </svg>
                                            <p class="text-sm font-medium">Belum ada data kelas.</p>
                                            <p class="text-xs text-slate-400 mt-0.5">Silakan tambahkan kelas baru untuk memulai.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

            <div x-show="addModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4"
                @click.away="addModal = false" x-cloak>
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden w-full max-w-md border border-slate-100" @click.stop>
                    <form action="{{ route('kelas.store') }}" method="POST">
                        @csrf
                        <div class="p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-2.5 bg-blue-100 text-blue-600 rounded-xl">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900">Tambah Kelas Baru</h3>
                                    <p class="text-xs text-slate-500">Masukkan nama grup kelas pembelajaran.</p>
                                </div>
                            </div>

                            <div>
                                <label for="nama_kelas_add" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">
                                    Nama Kelas
                                </label>
                                <input type="text" name="nama_kelas" id="nama_kelas_add"
                                    class="w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600 text-sm px-4 py-2.5"
                                    placeholder="Contoh: Kelas Satu A" required>
                            </div>
                        </div>

                        <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-slate-100">
                            <button type="submit"
                                class="inline-flex justify-center rounded-xl border border-transparent px-4 py-2 bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-md">
                                Simpan
                            </button>
                            <button @click="addModal = false" type="button"
                                class="inline-flex justify-center rounded-xl border border-slate-300 px-4 py-2 bg-white text-sm font-semibold text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>