<x-guest-layout>
    <!-- Background Gambar Fullscreen dengan Dark Overlay (Meliputi Seluruh Layar) -->
   

    <!-- Pendaran Warna Elegan (Glow Effect) -->
    <div class="fixed -top-20 -left-20 w-80 h-80 bg-blue-600/30 rounded-full blur-3xl -z-10 pointer-events-none"></div>
    <div class="fixed -bottom-20 -right-20 w-80 h-80 bg-yellow-500/20 rounded-full blur-3xl -z-10 pointer-events-none"></div>

    <!-- Container Form Registrasi Glassmorphism -->
    <div class="w-full max-w-md mx-auto my-8 p-6 md:p-8 bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl border border-white/50">
        
        <!-- Header Branding SoalKita -->
        <div class="text-center mb-6">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-2xl font-extrabold text-blue-600 hover:text-blue-700 transition-colors mb-1">
                <span>Soal<span class="text-yellow-500">Kita</span></span>
            </a>
            <h2 class="text-xl font-bold text-gray-800">Pendaftaran Akun Guru</h2>
            <p class="text-xs text-gray-500 mt-1">Buat bank soal dan asesmen digital secara instan.</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Nama Lengkap & Gelar')" class="font-semibold text-gray-700 text-xs" />
                <x-text-input id="name" class="block mt-1 w-full text-sm rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                              type="text" name="name" :value="old('name')" required autofocus autocomplete="name" 
                              placeholder="Contoh: Budi Santoso, S.Pd." />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Alamat Email')" class="font-semibold text-gray-700 text-xs" />
                <x-text-input id="email" class="block mt-1 w-full text-sm rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                              type="email" name="email" :value="old('email')" required autocomplete="username" 
                              placeholder="guru@sekolah.sch.id" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Kata Sandi')" class="font-semibold text-gray-700 text-xs" />
                <x-text-input id="password" class="block mt-1 w-full text-sm rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                type="password"
                                name="password"
                                required autocomplete="new-password" 
                                placeholder="Minimal 8 karakter" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" class="font-semibold text-gray-700 text-xs" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full text-sm rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" 
                                placeholder="Ulangi kata sandi" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Tombol Aksi -->
            <div class="flex flex-col gap-3 mt-6">
                <button type="submit" 
                        class="w-full justify-center py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all text-sm">
                    Daftar Akun Guru Sekarang
                </button>

                <div class="text-center mt-2">
                    <a class="underline text-xs text-gray-600 hover:text-blue-600 transition-colors" href="{{ route('login') }}">
                        Sudah punya akun? Masuk di sini
                    </a>
                </div>
            </div>
        </form>

    </div>
</x-guest-layout>