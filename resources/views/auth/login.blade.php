<x-guest-layout>
    <div class="-m-6 p-6 min-h-screen bg-cover bg-center flex flex-col justify-center items-center relative" 
         style="background-image: url('https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1920&q=80');">
        
        <!-- Overlay Transparan Gelap untuk meningkatkan kejelasan teks -->
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

        <!-- Kartu Form Login -->
        <div class="relative z-10 w-full max-w-md bg-white/95 backdrop-blur-md p-8 rounded-2xl shadow-2xl border border-white/20">
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <div class="text-center mb-6">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-2xl font-extrabold text-blue-600 hover:text-blue-700 transition-colors mb-1">
                    <span>Soal<span class="text-yellow-500">Kita</span></span>
                </a>
                <h2 class="text-xl font-bold text-gray-800">Masuk ke Akun Anda</h2>
                <p class="text-xs text-gray-500 mt-1">Lanjutkan membuat bank soal dan asesmen digital.</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div>
                    <x-input-label for="email" :value="__('Alamat Email')" class="font-semibold text-gray-700 text-xs" />
                    <x-text-input id="email" class="block mt-1 w-full text-sm rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                  type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                                  placeholder="guru@sekolah.sch.id" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password" :value="__('Kata Sandi')" class="font-semibold text-gray-700 text-xs" />
                    <x-text-input id="password" class="block mt-1 w-full text-sm rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                  type="password" name="password" required autocomplete="current-password"
                                  placeholder="Masukkan kata sandi" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                        <span class="ms-2 text-xs text-gray-600">{{ __('Ingat saya') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-xs text-gray-600 hover:text-blue-600 transition-colors rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           href="{{ route('password.request') }}">
                            {{ __('Lupa kata sandi?') }}
                        </a>
                    @endif
                </div>

                <div class="flex flex-col gap-3 mt-6">
                    <button type="submit"
                            class="w-full justify-center py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all text-sm">
                        {{ __('Masuk') }}
                    </button>

                    <div class="text-center mt-2">
                        <a class="underline text-xs text-gray-600 hover:text-blue-600 transition-colors" href="{{ route('register') }}">
                            Belum punya akun? Daftar di sini
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>