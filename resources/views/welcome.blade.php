<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SoalKita - Platform Bank Soal & Asesmen Otomatis</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link rel="icon" href="{{ asset('img/soalkita_logo.jpeg') }}">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-800 relative bg-slate-900 min-h-screen">

    <!-- Layer Background Gambar 'bg' dari public/img/ -->
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <img src="{{ asset('img/bg.jpg') }}" 
             onerror="this.onerror=null; this.src='{{ asset('img/bg.png') }}';"
             alt="Background SoalKita" 
             class="w-full h-full object-cover brightness-50">
    </div>

    <!-- Header Navbar -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur shadow-md transition-all" x-data="{ atTop: true }"
        @scroll.window="atTop = (window.scrollY > 10) ? false : true" :class="{ 'py-4': atTop, 'py-2': !atTop }">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <a href="#" class="flex items-center space-x-2 text-2xl font-extrabold text-blue-600 hover:text-blue-800">
                <!-- Logo Brand -->
                <img src="{{ asset('img/soalkita_logo.jpeg') }}" alt="Logo SoalKita" class="h-9 w-9 object-contain">
                <!-- Brand Name -->
                <span>Soal<span class="text-yellow-500">Kita</span></span>
            </a>

            <nav class="flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="px-5 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 transition-colors">
                            Dashboard Guru
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-gray-600 hover:text-blue-600 text-sm font-medium transition-colors">
                            Masuk
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="px-5 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 transition-colors">
                                Regist
                            </a>
                        @endif
                    @endauth
                @endif
            </nav>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section id="hero" class="pt-40 pb-24">
            <div class="container mx-auto px-6 text-center">

                <div x-data="{ shown: false }" x-init="setTimeout(() => { shown = true }, 300)"
                    :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
                    class="transition-all ease-out duration-1000">
                    
                    <!-- Tagline Utama -->
                    <h1 class="text-3xl md:text-5xl font-extrabold text-yellow-300 mb-6 leading-tight drop-shadow-md">
                        Kelola Bank Soal & Asesmen Ujian Secara Instan
                    </h1>
                    
                    <!-- Deskripsi SoalKita -->
                    <p class="text-lg md:text-xl text-slate-100 mb-10 max-w-2xl mx-auto drop-shadow">
                        Platform berbasis web untuk guru dalam menyusun soal dan melaksanakan kuis digital. Siswa langsung mengerjakan dan nilai terkoreksi otomatis secara real-time.
                    </p>

                    <!-- Form Akses Kuis Siswa -->
                    <div class="max-w-lg mx-auto bg-white/95 backdrop-blur p-6 md:p-8 rounded-xl shadow-2xl border border-gray-100">
                        <h3 class="text-xl font-bold mb-2 text-blue-600">Siswa: Mulai Pengerjaan Kuis</h3>
                        <p class="text-sm text-gray-500 mb-4">Masukkan kode/token akses kuis yang diberikan oleh gurumu.</p>

                        @error('token')
                            <p class="text-sm text-red-600 p-3 mb-4 bg-red-50 border border-red-200 rounded-md">
                                {{ $message }}</p>
                        @enderror

                        <form action="{{ route('siswa.checkToken') }}" method="POST"
                            class="flex flex-col md:flex-row gap-3">
                            @csrf
                            <input type="text" name="token" id="token" placeholder="Masukkan Token Kuis"
                                required value="{{ old('token') }}"
                                class="flex-grow px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 shadow-sm text-base tracking-widest uppercase">

                            <button type="submit"
                                class="px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors shadow">
                                Kerjakan Soal
                            </button>
                        </form>
                    </div>

                </div>

            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="py-10 bg-gray-900/90 text-gray-400">
        <div class="container mx-auto px-6 text-center">
            <p class="text-sm">&copy; {{ date('Y') }} SoalKita. Hak Cipta Dilindungi.</p>
            <p class="text-sm text-gray-500 mt-1">Dikembangkan oleh PTI FT-UNG (Kelompok MAFIS)</p>
        </div>
    </footer>

</body>

</html>