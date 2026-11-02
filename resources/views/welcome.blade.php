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

    <style>
        @keyframes ambient-drift {
            0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
            50% { transform: translate3d(24px, -28px, 0) scale(1.08); }
        }

        @keyframes ambient-pulse {
            0%, 100% { opacity: 0.45; }
            50% { opacity: 0.85; }
        }

        @keyframes gradient-flow {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        @keyframes orbit-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes particle-float {
            0%, 100% { transform: translate3d(0, 0, 0); opacity: 0.35; }
            50% { transform: translate3d(12px, -20px, 0); opacity: 0.8; }
        }

        @keyframes float-gently {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }

        .ambient-orb {
            animation: ambient-drift 12s ease-in-out infinite;
        }

        .ambient-glow {
            animation: ambient-pulse 9s ease-in-out infinite;
        }

        .animated-gradient {
            background-size: 200% 200%;
            animation: gradient-flow 18s ease-in-out infinite;
        }

        .orbit-slow {
            animation: orbit-slow 36s linear infinite;
        }

        .particle-float {
            animation: particle-float 8s ease-in-out infinite;
        }

        .float-gently {
            animation: float-gently 5s ease-in-out infinite;
        }

        .float-gently-delayed {
            animation: float-gently 5s ease-in-out 1.5s infinite;
        }

        @media (prefers-reduced-motion: reduce) {
            .ambient-orb,
            .ambient-glow,
            .animated-gradient,
            .orbit-slow,
            .particle-float,
            .float-gently,
            .float-gently-delayed {
                animation: none;
            }
        }
    </style>
</head>

<body class="relative min-h-screen overflow-x-hidden font-sans text-slate-800 antialiased">
    <div class="pointer-events-none fixed inset-0 z-0 overflow-hidden bg-gradient-to-br from-slate-50 via-blue-50/70 to-indigo-50/70" aria-hidden="true">
        <div class="ambient-orb ambient-glow absolute -left-32 top-10 h-80 w-80 rounded-full bg-blue-300/55 blur-3xl"></div>
        <div class="ambient-orb ambient-glow absolute -right-32 top-1/3 h-96 w-96 rounded-full bg-violet-200/60 blur-3xl" style="animation-delay: -4s"></div>
        <div class="ambient-orb ambient-glow absolute -bottom-36 left-1/4 h-96 w-96 rounded-full bg-cyan-200/60 blur-3xl" style="animation-delay: -2s"></div>
        <div class="ambient-orb ambient-glow absolute -bottom-24 -right-24 h-80 w-80 rounded-full bg-indigo-200/55 blur-3xl" style="animation-delay: -6s"></div>
        <div class="orbit-slow absolute -left-40 top-[28%] h-96 w-96 rounded-full border border-blue-400/10"></div>
        <div class="orbit-slow absolute -right-48 bottom-[14%] h-[30rem] w-[30rem] rounded-full border border-indigo-400/10" style="animation-direction: reverse"></div>
        <div class="particle-float absolute left-[18%] top-[35%] h-2 w-2 rounded-full bg-blue-400/40"></div>
        <div class="particle-float absolute right-[22%] top-[62%] h-3 w-3 rounded-full bg-violet-400/35" style="animation-delay: -3s"></div>
        <div class="particle-float absolute left-[72%] top-[18%] h-2 w-2 rounded-full bg-cyan-500/40" style="animation-delay: -5s"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_1px_1px,rgba(100,116,139,0.10)_1px,transparent_0)] bg-[size:32px_32px]"></div>
    </div>

    <!-- Header Navbar -->
    <header class="fixed inset-x-0 top-0 z-50 border-b border-white/60 bg-white/90 shadow-sm backdrop-blur-xl transition-all" x-data="{ atTop: true }"
        @scroll.window="atTop = (window.scrollY > 10) ? false : true" :class="{ 'py-4': atTop, 'py-2': !atTop }">
        <div class="container mx-auto flex items-center justify-between px-6">
            <a href="#hero" class="flex items-center space-x-2 text-2xl font-extrabold text-blue-700 transition hover:text-blue-900">
                <!-- Logo Brand -->
                <img src="{{ asset('img/soalkita_logo.jpeg') }}" alt="Logo SoalKita" class="h-9 w-9 rounded-lg object-contain">
                <!-- Brand Name -->
                <span>Soal<span class="text-yellow-500">Kita</span></span>
            </a>

            <nav aria-label="Navigasi utama" class="flex items-center gap-3 sm:gap-6">
                <a href="#tentang" class="hidden text-sm font-medium text-slate-600 transition-colors hover:text-blue-600 sm:inline">
                    Tentang
                </a>
                <a href="#fitur" class="hidden text-sm font-medium text-slate-600 transition-colors hover:text-blue-600 md:inline">
                    Fitur
                </a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="rounded-full bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:-translate-y-0.5 hover:bg-blue-700">
                            Dashboard Guru
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 transition-colors hover:text-blue-600">
                            Masuk
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="rounded-full bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:-translate-y-0.5 hover:bg-blue-700">
                                Coba Gratis
                            </a>
                        @endif
                    @endauth
                @endif
            </nav>
        </div>
    </header>

    <main class="relative z-10">
        <!-- Hero Section -->
        <section id="hero" class="relative isolate overflow-hidden bg-gradient-to-br from-slate-950 via-blue-950 to-indigo-950 pt-32 pb-24 sm:pt-40 sm:pb-32">
            <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
                <div class="animated-gradient absolute inset-0 bg-gradient-to-br from-slate-950 via-blue-950 to-indigo-950"></div>
                <div class="ambient-orb ambient-glow absolute -left-24 -top-20 h-80 w-80 rounded-full bg-blue-500/35 blur-3xl"></div>
                <div class="ambient-orb ambient-glow absolute -right-20 top-16 h-96 w-96 rounded-full bg-indigo-400/25 blur-3xl" style="animation-delay: -5s"></div>
                <div class="orbit-slow absolute -right-16 top-10 h-80 w-80 rounded-full border border-white/10"></div>
                <div class="orbit-slow absolute -right-4 top-24 h-56 w-56 rounded-full border border-cyan-200/10" style="animation-direction: reverse"></div>
                <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.035)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.035)_1px,transparent_1px)] bg-[size:48px_48px]"></div>
                <div class="float-gently absolute left-[12%] top-1/3 hidden rounded-2xl border border-white/15 bg-white/10 px-4 py-3 text-sm font-medium text-white/80 shadow-xl backdrop-blur md:block">✦ Belajar lebih terarah</div>
                <div class="float-gently-delayed absolute right-[10%] top-1/2 hidden rounded-2xl border border-white/15 bg-white/10 px-4 py-3 text-sm font-medium text-white/80 shadow-xl backdrop-blur md:block">✓ Hasil lebih praktis</div>
            </div>

            <div class="container relative mx-auto px-6 text-center">
                <div x-data="{ shown: false }" x-init="setTimeout(() => { shown = true }, 150)"
                    :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                    class="transition-all duration-700 ease-out">
                    <span class="mb-6 inline-flex items-center gap-2 rounded-full border border-blue-200/20 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-blue-100 backdrop-blur">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        Platform asesmen digital untuk pendidikan
                    </span>
                    <h1 class="mx-auto mb-6 max-w-4xl text-4xl font-extrabold leading-tight tracking-tight text-white sm:text-5xl md:text-6xl">
                        Kelola soal dan evaluasi belajar
                        <span class="bg-gradient-to-r from-amber-200 to-yellow-400 bg-clip-text text-transparent">lebih mudah</span>
                    </h1>
                    <p class="mx-auto mb-10 max-w-2xl text-base leading-relaxed text-blue-100/85 sm:text-lg">
                        SoalKita membantu guru menyiapkan bank soal dan kuis digital dalam satu platform. Siswa bergabung menggunakan token, lalu guru dapat meninjau hasil pengerjaan dengan lebih praktis.
                    </p>

                    <!-- Form Akses Kuis Siswa -->
                    <div class="mx-auto max-w-xl rounded-3xl border border-white/70 bg-white p-6 text-left shadow-2xl shadow-slate-950/30 sm:p-8">
                        <div class="mb-5 flex items-start gap-4">
                            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-2xl" aria-hidden="true">📝</span>
                            <div>
                                <h2 class="text-xl font-bold text-slate-900">Siap mengerjakan kuis?</h2>
                                <p class="mt-1 text-sm leading-relaxed text-slate-500">Masukkan kode atau token yang dibagikan oleh gurumu.</p>
                            </div>
                        </div>
                        @error('token')
                            <p role="alert" class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-600">
                                {{ $message }}</p>
                        @enderror

                        <form action="{{ route('siswa.checkToken') }}" method="POST"
                            class="flex flex-col gap-3 sm:flex-row">
                            @csrf
                            <input type="text" name="token" id="token" placeholder="Masukkan Token Kuis"
                                aria-label="Token kuis" required value="{{ old('token') }}" autocomplete="off"
                                class="min-w-0 flex-1 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-base uppercase tracking-widest shadow-inner focus:border-blue-500 focus:ring-blue-500">

                            <button type="submit"
                                class="rounded-xl bg-emerald-600 px-6 py-3.5 font-semibold text-white shadow-lg shadow-emerald-600/20 transition hover:-translate-y-0.5 hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-200">
                                Kerjakan Soal
                            </button>
                        </form>
                        <p class="mt-4 flex items-center justify-center gap-2 text-xs text-slate-400">
                            <span aria-hidden="true">🔒</span> Pastikan token sesuai dengan yang diberikan guru.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Tentang SoalKita -->
        <section id="tentang" class="relative isolate scroll-mt-24 overflow-hidden py-20 sm:py-24">
            <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden" aria-hidden="true">
                <div class="ambient-orb ambient-glow absolute -left-24 top-12 h-72 w-72 rounded-full bg-sky-300/50 blur-3xl"></div>
                <div class="ambient-orb ambient-glow absolute -right-20 bottom-0 h-80 w-80 rounded-full bg-indigo-200/60 blur-3xl" style="animation-delay: -3s"></div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_1px_1px,rgba(59,130,246,0.10)_1px,transparent_0)] bg-[size:30px_30px]"></div>
            </div>
            <div class="container relative mx-auto px-6">
                <div class="mx-auto max-w-5xl">
                    <div class="mx-auto mb-12 max-w-3xl text-center">
                        <p class="mb-3 text-sm font-bold uppercase tracking-[0.2em] text-blue-600">Kenali SoalKita</p>
                        <h2 class="mb-5 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                            Satu ruang untuk proses evaluasi yang lebih praktis
                        </h2>
                        <p class="leading-relaxed text-slate-600 sm:text-lg">
                            SoalKita adalah platform pembelajaran berbasis web yang mendukung guru dalam menyiapkan
                            evaluasi dan membantu siswa mengerjakan kuis secara digital. Mulai dari mengelola kumpulan
                            soal, membagikan akses kuis, hingga meninjau hasil pengerjaan, prosesnya tersedia dalam
                            satu alur yang mudah diikuti.
                        </p>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <article class="rounded-3xl border border-white/80 bg-white/85 p-7 shadow-lg shadow-blue-950/5 backdrop-blur-sm sm:p-8">
                            <span class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-2xl" aria-hidden="true">👩‍🏫</span>
                            <h3 class="mb-3 text-xl font-bold text-slate-900">Membantu guru mengelola evaluasi</h3>
                            <p class="leading-relaxed text-slate-600">
                                Persiapan evaluasi sering kali melibatkan banyak hal, mulai dari menyusun soal hingga
                                mengumpulkan hasil pengerjaan. SoalKita menghadirkan alur digital yang membantu guru
                                menata proses tersebut agar kegiatan evaluasi lebih praktis dan mudah diakses.
                            </p>
                        </article>
                        <article class="rounded-3xl border border-white/80 bg-white/85 p-7 shadow-lg shadow-indigo-950/5 backdrop-blur-sm sm:p-8">
                            <span class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-2xl" aria-hidden="true">🎓</span>
                            <h3 class="mb-3 text-xl font-bold text-slate-900">Memudahkan siswa mulai belajar</h3>
                            <p class="leading-relaxed text-slate-600">
                                Siswa tidak perlu melalui proses yang rumit untuk memulai kuis. Cukup siapkan token
                                dari guru, masukkan token tersebut pada halaman ini, lalu ikuti instruksi pengerjaan
                                yang tersedia.
                            </p>
                        </article>
                    </div>

                    <div class="mt-6 rounded-3xl border border-blue-100/80 bg-blue-50/75 p-6 sm:p-8">
                        <h3 class="mb-3 text-lg font-bold text-slate-900">Dirancang untuk mendukung kegiatan belajar</h3>
                        <p class="max-w-4xl leading-relaxed text-slate-600">
                            Teknologi membantu proses evaluasi menjadi lebih tertata, sementara guru tetap memegang
                            peran penting dalam mendampingi siswa dan memahami kebutuhan belajar mereka. SoalKita
                            menjadi ruang bantu untuk menghubungkan persiapan kuis, pengerjaan, dan peninjauan hasil
                            dalam pengalaman yang lebih sederhana.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section id="fitur" class="relative isolate scroll-mt-24 overflow-hidden border-y border-blue-100/70 py-20 sm:py-24">
            <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden" aria-hidden="true">
                <div class="ambient-orb ambient-glow absolute -right-24 -top-20 h-80 w-80 rounded-full bg-amber-200/55 blur-3xl" style="animation-delay: -2s"></div>
                <div class="ambient-orb ambient-glow absolute -bottom-28 -left-16 h-96 w-96 rounded-full bg-emerald-200/50 blur-3xl" style="animation-delay: -5s"></div>
                <div class="absolute inset-0 bg-[linear-gradient(rgba(99,102,241,0.045)_1px,transparent_1px),linear-gradient(90deg,rgba(99,102,241,0.045)_1px,transparent_1px)] bg-[size:44px_44px]"></div>
            </div>
            <div class="container relative mx-auto px-6">
                <div class="mx-auto mb-12 max-w-3xl text-center">
                    <p class="mb-3 text-sm font-bold uppercase tracking-[0.2em] text-blue-600">Fitur SoalKita</p>
                    <h2 class="mb-5 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                        Dukungan praktis untuk setiap tahap evaluasi
                    </h2>
                    <p class="leading-relaxed text-slate-600 sm:text-lg">
                        Setiap fitur dirancang untuk membantu menyederhanakan proses, dari menyiapkan materi
                        pertanyaan hingga melihat kembali hasil kuis. Berikut cara SoalKita mendukung guru dan siswa.
                    </p>
                </div>

                <div class="mx-auto grid max-w-5xl gap-5 sm:grid-cols-2">
                    <article class="group rounded-2xl border border-blue-100 bg-white/90 p-6 shadow-sm backdrop-blur-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-900/10 sm:p-7">
                        <span class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-2xl transition group-hover:scale-110" aria-hidden="true">📚</span>
                        <h3 class="mb-3 text-lg font-bold text-slate-900">Bank soal terorganisir</h3>
                        <p class="text-sm leading-relaxed text-slate-600">
                            Guru dapat menyiapkan dan mengelola kumpulan soal sebagai bahan untuk kegiatan evaluasi.
                            Soal yang tersusun rapi membantu proses persiapan kuis terasa lebih terarah dan
                            memudahkan guru menyesuaikan materi dengan tujuan pembelajaran.
                        </p>
                    </article>
                    <article class="group rounded-2xl border border-amber-100 bg-white/90 p-6 shadow-sm backdrop-blur-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-amber-900/10 sm:p-7">
                        <span class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-2xl transition group-hover:scale-110" aria-hidden="true">🎯</span>
                        <h3 class="mb-3 text-lg font-bold text-slate-900">Kuis digital dengan token</h3>
                        <p class="text-sm leading-relaxed text-slate-600">
                            Setelah kuis disiapkan, guru dapat membagikan token akses kepada siswa. Siswa memasukkan
                            token pada halaman utama untuk menemukan kuis yang dituju, sehingga akses pengerjaan
                            terasa jelas dan mudah diikuti.
                        </p>
                    </article>
                    <article class="group rounded-2xl border border-emerald-100 bg-white/90 p-6 shadow-sm backdrop-blur-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-900/10 sm:p-7">
                        <span class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-2xl transition group-hover:scale-110" aria-hidden="true">⚡</span>
                        <h3 class="mb-3 text-lg font-bold text-slate-900">Pengerjaan lebih efisien</h3>
                        <p class="text-sm leading-relaxed text-slate-600">
                            Kuis dikerjakan secara digital tanpa perlu mengelola lembar jawaban secara terpisah.
                            Untuk jenis soal yang didukung, pemeriksaan nilai otomatis membantu menghemat waktu
                            sehingga guru dapat lebih fokus mendampingi kegiatan belajar.
                        </p>
                    </article>
                    <article class="group rounded-2xl border border-violet-100 bg-white/90 p-6 shadow-sm backdrop-blur-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-violet-900/10 sm:p-7">
                        <span class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-50 text-2xl transition group-hover:scale-110" aria-hidden="true">📊</span>
                        <h3 class="mb-3 text-lg font-bold text-slate-900">Hasil mudah ditinjau</h3>
                        <p class="text-sm leading-relaxed text-slate-600">
                            Hasil pengerjaan dapat digunakan guru untuk melihat kembali pelaksanaan evaluasi dan
                            memahami bagian yang perlu diperhatikan. Informasi tersebut dapat menjadi salah satu
                            bahan pertimbangan dalam merencanakan pendampingan atau pembelajaran berikutnya.
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <section id="alur" class="relative isolate scroll-mt-24 overflow-hidden border-y border-slate-200/70 py-20 sm:py-24">
            <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden" aria-hidden="true">
                <div class="ambient-orb ambient-glow absolute -left-24 top-0 h-72 w-72 rounded-full bg-cyan-200/55 blur-3xl"></div>
                <div class="ambient-orb ambient-glow absolute -right-20 bottom-0 h-80 w-80 rounded-full bg-blue-200/55 blur-3xl" style="animation-delay: -4s"></div>
                <div class="absolute inset-0 bg-white/35"></div>
            </div>
            <div class="container relative mx-auto px-6">
                <div class="mx-auto mb-12 max-w-3xl text-center">
                    <p class="mb-3 text-sm font-bold uppercase tracking-[0.2em] text-blue-600">Mulai dalam tiga langkah</p>
                    <h2 class="mb-4 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Alur yang sederhana, dari persiapan sampai hasil</h2>
                    <p class="leading-relaxed text-slate-600">Guru mengatur evaluasi, siswa mengerjakan kuis, lalu hasilnya dapat ditinjau kembali.</p>
                </div>

                <div class="mx-auto grid max-w-5xl gap-6 md:grid-cols-3">
                    <article class="relative rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">
                        <span class="mb-5 flex h-11 w-11 items-center justify-center rounded-full bg-blue-600 text-lg font-extrabold text-white shadow-lg shadow-blue-600/20">1</span>
                        <h3 class="mb-2 text-lg font-bold text-slate-900">Guru menyiapkan soal</h3>
                        <p class="text-sm leading-relaxed text-slate-600">Susun bank soal dan siapkan kuis yang sesuai dengan kebutuhan evaluasi kelas.</p>
                    </article>
                    <article class="relative rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">
                        <span class="mb-5 flex h-11 w-11 items-center justify-center rounded-full bg-amber-500 text-lg font-extrabold text-white shadow-lg shadow-amber-500/20">2</span>
                        <h3 class="mb-2 text-lg font-bold text-slate-900">Siswa masuk dengan token</h3>
                        <p class="text-sm leading-relaxed text-slate-600">Bagikan token kuis, kemudian siswa memasukkannya pada kolom di bagian atas halaman.</p>
                    </article>
                    <article class="relative rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">
                        <span class="mb-5 flex h-11 w-11 items-center justify-center rounded-full bg-emerald-600 text-lg font-extrabold text-white shadow-lg shadow-emerald-600/20">3</span>
                        <h3 class="mb-2 text-lg font-bold text-slate-900">Tinjau pengerjaan</h3>
                        <p class="text-sm leading-relaxed text-slate-600">Selesaikan evaluasi secara digital dan gunakan hasilnya untuk mendukung pembelajaran berikutnya.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="relative isolate overflow-hidden py-20 sm:py-24">
            <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden" aria-hidden="true">
                <div class="ambient-orb ambient-glow absolute -left-24 top-10 h-72 w-72 rounded-full bg-violet-200/55 blur-3xl"></div>
                <div class="ambient-orb ambient-glow absolute -right-24 bottom-0 h-80 w-80 rounded-full bg-blue-200/55 blur-3xl" style="animation-delay: -3s"></div>
            </div>
            <div class="container mx-auto px-6">
                <div class="relative mx-auto grid max-w-5xl items-center gap-10 overflow-hidden rounded-3xl bg-gradient-to-br from-blue-950 via-slate-900 to-indigo-950 p-8 text-white shadow-2xl shadow-blue-950/15 sm:p-12 md:grid-cols-[1.1fr_0.9fr]">
                    <div class="pointer-events-none absolute -right-24 -top-28 h-72 w-72 rounded-full border border-white/10" aria-hidden="true"></div>
                    <div class="ambient-orb pointer-events-none absolute -right-16 -top-20 h-64 w-64 rounded-full bg-blue-400/15 blur-3xl" aria-hidden="true"></div>
                    <div>
                        <p class="mb-3 text-sm font-bold uppercase tracking-[0.2em] text-blue-200">Belajar bersama SoalKita</p>
                        <h2 class="mb-5 text-3xl font-extrabold tracking-tight sm:text-4xl">Lebih banyak waktu untuk mendampingi belajar</h2>
                        <p class="leading-relaxed text-slate-300">
                            Dengan alur kuis digital, guru dapat mengurangi pekerjaan administratif saat evaluasi dan lebih fokus pada pemahaman siswa. Siswa pun mendapatkan pengalaman mengerjakan kuis yang jelas dan terarah.
                        </p>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-start gap-4 rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-400/15 text-xl" aria-hidden="true">👩‍🏫</span>
                            <div>
                                <h3 class="font-bold">Untuk guru</h3>
                                <p class="mt-1 text-sm leading-relaxed text-slate-300">Kelola persiapan soal, bagikan kuis, dan tinjau hasil dalam alur yang lebih tertata.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-300/15 text-xl" aria-hidden="true">🎓</span>
                            <div>
                                <h3 class="font-bold">Untuk siswa</h3>
                                <p class="mt-1 text-sm leading-relaxed text-slate-300">Akses kuis dengan token dari guru dan kerjakan soal secara digital.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="relative isolate overflow-hidden px-6 pb-20 sm:pb-24">
            <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden" aria-hidden="true">
                <div class="ambient-orb ambient-glow absolute -bottom-28 left-1/3 h-72 w-72 rounded-full bg-amber-200/55 blur-3xl"></div>
            </div>
            <div class="relative mx-auto max-w-4xl overflow-hidden rounded-3xl border border-blue-100 bg-white/75 px-6 py-10 text-center shadow-lg shadow-blue-950/5 backdrop-blur-sm sm:px-12 sm:py-14">
                <div class="ambient-orb pointer-events-none absolute -right-16 -top-20 h-48 w-48 rounded-full bg-blue-200/40 blur-3xl" aria-hidden="true"></div>
                <span class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-2xl shadow-sm" aria-hidden="true">✨</span>
                <h2 class="mb-4 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Siap memulai evaluasi yang lebih praktis?</h2>
                <p class="mx-auto mb-7 max-w-2xl leading-relaxed text-slate-600">
                    Guru dapat masuk untuk menyiapkan kegiatan belajar. Siswa yang sudah memiliki token bisa langsung bergabung melalui form kuis.
                </p>
                <div class="flex flex-col justify-center gap-3 sm:flex-row">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="rounded-full bg-blue-600 px-6 py-3 font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:-translate-y-0.5 hover:bg-blue-700">Buka Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="rounded-full bg-blue-600 px-6 py-3 font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:-translate-y-0.5 hover:bg-blue-700">Masuk sebagai Guru</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="rounded-full border border-slate-300 bg-white px-6 py-3 font-semibold text-slate-700 transition hover:border-blue-300 hover:bg-blue-50">Daftar Gratis</a>
                            @endif
                        @endauth
                    @endif
                    <a href="#token" onclick="document.getElementById('token').focus()" class="rounded-full border border-slate-300 bg-white px-6 py-3 font-semibold text-slate-700 transition hover:border-blue-300 hover:bg-blue-50">Saya punya token kuis</a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 bg-slate-950 py-10 text-slate-400">
        <div class="container mx-auto flex flex-col items-center justify-between gap-3 px-6 text-center sm:flex-row sm:text-left">
            <div>
                <p class="text-sm font-semibold text-white">&copy; {{ date('Y') }} SoalKita. Hak Cipta Dilindungi.</p>
                <p class="mt-1 text-xs text-slate-500">Dikembangkan oleh PTI FT-UNG (Kelompok MAFIS)</p>
            </div>
            <a href="#hero" class="text-sm font-medium transition hover:text-white">Kembali ke atas ↑</a>
        </div>
    </footer>

</body>

</html>