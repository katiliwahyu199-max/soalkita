<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $terbitanTugas->tugas->nama_tugas }} - Soal {{ $soalIndex }}</title>
    <link rel="icon" href="{{ asset('img/tutwuri.png') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans min-h-screen">

    <div class="container mx-auto px-4 py-8 md:py-12">

        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            {{ $terbitanTugas->tugas->nama_tugas }} - Kelas {{ $terbitanTugas->kelas->nama_kelas }}
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

            <div class="md:col-span-1">
                <div class="md:sticky md:top-8">
                    <div class="bg-white p-6 rounded-xl shadow-lg mb-6">
                        <h3 class="text-lg font-semibold mb-3 border-b pb-2 text-blue-600">Navigasi Soal</h3>

                        <div class="flex flex-wrap gap-2">
                            @for ($i = 1; $i <= $totalSoal; $i++)
                                <a href="{{ route('siswa.task', [$terbitanTugas->id, $i]) }}"
                                    class="w-10 h-10 flex items-center justify-center rounded-lg font-semibold transition-colors
                                @if ($i == $soalIndex) bg-blue-600 text-white shadow-md border-2 border-blue-800
                                @elseif ($session['jawaban'][$session['soal_ids'][$i - 1]])
                                    bg-green-500 text-white hover:bg-green-600
                                @else
                                    bg-gray-200 text-gray-700 hover:bg-gray-300 @endif">
                                    {{ $i }}
                                </a>
                            @endfor
                        </div>
                    </div>

                    @if ($terbitanTugas->durasi_menit > 0)
                        <div class="bg-red-50 p-6 rounded-xl shadow-lg border border-red-300 text-center">
                            <h3 class="text-lg font-semibold text-red-700">Waktu Tersisa:</h3>
                            <div id="countdown-timer" class="text-3xl font-bold text-red-900 mt-2">
                                {{ floor($remainingTime / 60) }}:{{ str_pad($remainingTime % 60, 2, '0', STR_PAD_LEFT) }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="md:col-span-3">
                <div class="bg-white p-6 md:p-8 rounded-xl shadow-lg">

                    <h2 class="text-xl font-bold mb-4 text-indigo-700">Soal Nomor {{ $soalIndex }} dari
                        {{ $totalSoal }}</h2>
                    <hr class="mb-6">



                    <div class="mb-16 prose max-w-none">
                        @if ($soal->keterangan_soal)
                            <p class="mt-8 mb-8 p-3 bg-gray-100 rounded-md text-sm italic">{!! $soal->keterangan_soal !!}</p>
                        @endif

                        @if ($soal->gambar_soal)
                            <img src="{{ Storage::url($soal->gambar_soal) }}" alt="Gambar Soal"
                                class="mt-2 rounded-lg w-full max-w-md mx-auto">
                        @endif

                        <div class="mt-6 mb-4">{!! $soal->pertanyaan !!}</div>

                    </div>


                    <form id="submit-answer-form" action="{{ route('siswa.submitAnswer', $terbitanTugas->id) }}"
                        method="POST">
                        @csrf
                        <input type="hidden" name="soal_id" value="{{ $soal->id }}">

                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Pilihan Jawaban:</h3>

                        @if ($soal->tipe_soal_di_tugas == 'pilihan_ganda')
                            <div class="space-y-3">
                                @foreach ($soal->opsiJawabans as $index => $opsi)
                                    @php
                                        // Konversi index 0, 1, 2 menjadi A, B, C
                                        $label = chr(65 + $index);
                                    @endphp
                                    <label
                                        class="flex items-start p-4 rounded-lg border cursor-pointer 
                            
                            {{ $jawabanSiswa == $index ? 'bg-indigo-50 border-indigo-500 ring-2 ring-indigo-500' : 'bg-white border-gray-200 hover:border-indigo-400' }}">
                                        <input type="radio" name="jawaban" value="{{ $index }}"
                                            class="mt-1 mr-3 text-indigo-600 focus:ring-indigo-500">

                                        <div class="flex-grow">
                                            <span class="font-bold text-indigo-700 mr-2">{{ $label }}.</span>
                                            <span class="text-gray-900">{!! $opsi->opsi_teks !!}</span>
                                            @if ($opsi->opsi_gambar)
                                                <img src="{{ Storage::url($opsi->opsi_gambar) }}" alt="Opsi Gambar"
                                                    class="mt-2 w-32 rounded">
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @elseif ($soal->tipe_soal_di_tugas == 'uraian')
                            <textarea name="jawaban" id="uraian_jawaban" rows="6" placeholder="Tulis jawaban Anda di sini..."
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">{{ $jawabanSiswa }}</textarea>
                        @elseif ($soal->tipe_soal_di_tugas == 'menjodohkan')
                            @php
                                $matchingPairs = is_array($jawabanSiswa) ? $jawabanSiswa : [];
                                $matchingOptions = json_decode($soal->kunci_jawaban ?? '{}', true);
                                $matchingLeft = is_array($matchingOptions) ? array_keys($matchingOptions) : [];
                                $matchingRight = is_array($matchingOptions) ? array_values(array_unique($matchingOptions)) : [];
                                $savedMatchingRight = [];
                                foreach ($matchingLeft as $leftKey) {
                                    $savedValue = $matchingPairs[$leftKey] ?? null;
                                    if ($savedValue !== null && in_array($savedValue, $matchingRight, true)) {
                                        $savedMatchingRight[] = $savedValue;
                                    }
                                }
                                $matchingRightOrder = array_values(array_unique(array_merge($savedMatchingRight, $matchingRight)));
                            @endphp
                            <div x-data='{
                                leftItems: @json($matchingLeft),
                                rightItems: @json($matchingRightOrder).map((value, id) => ({ id, value })),
                                move(index, step) {
                                    const target = index + step;
                                    if (target < 0 || target >= this.rightItems.length || index === target) return;

                                    const positions = {};
                                    this.$root.querySelectorAll("[data-matching-card]").forEach((card) => {
                                        positions[card.dataset.matchingCard] = card.getBoundingClientRect().top;
                                    });

                                    [this.rightItems[index], this.rightItems[target]] = [this.rightItems[target], this.rightItems[index]];

                                    this.$nextTick(() => {
                                        this.$root.querySelectorAll("[data-matching-card]").forEach((card) => {
                                            const previousTop = positions[card.dataset.matchingCard];
                                            if (previousTop === undefined) return;

                                            const offset = previousTop - card.getBoundingClientRect().top;
                                            card.style.transition = "none";
                                            card.style.transform = `translateY(${offset}px)`;

                                            requestAnimationFrame(() => {
                                                card.style.transition = "transform 350ms cubic-bezier(.22, .61, .36, 1)";
                                                card.style.transform = "translateY(0)";
                                            });
                                        });
                                    });
                                }
                            }' class="space-y-4">
                                <p class="text-sm text-gray-600">Sejajarkan setiap jawaban di sebelah item yang sesuai. Gunakan tombol naik/turun pada kolom kanan untuk mengatur pasangan.</p>

                                <div class="overflow-x-auto pb-2">
                                    <div class="grid min-w-[640px] grid-cols-2 gap-5">
                                        <div class="space-y-3">
                                            <div class="px-1 text-xs font-bold uppercase tracking-wide text-gray-500">Item kiri</div>
                                            <template x-for="leftKey in leftItems" :key="leftKey">
                                                <div class="flex h-20 items-center rounded-xl border-2 border-indigo-200 bg-white px-4 py-3 shadow-sm">
                                                    <span class="text-base font-semibold text-gray-800" x-text="leftKey"></span>
                                                </div>
                                            </template>
                                        </div>

                                        <div class="space-y-3">
                                            <div class="px-1 text-xs font-bold uppercase tracking-wide text-gray-500">Pasangan kanan</div>
                                            <template x-for="(item, index) in rightItems" :key="item.id">
                                                <div data-matching-card x-bind:data-matching-card="item.id" class="flex h-20 items-center gap-2 rounded-xl border-2 border-indigo-300 bg-indigo-50 px-3 py-3 shadow-sm will-change-transform">
                                                    <div class="flex h-11 flex-1 items-center rounded-lg border border-indigo-200 bg-white px-3 py-2 text-sm font-medium text-indigo-700" x-text="item.value"></div>
                                                    <div class="flex flex-col gap-1">
                                                        <button type="button" @click="move(index, -1)" class="rounded border border-gray-300 bg-white px-2 py-1 text-xs font-bold text-gray-700 hover:bg-gray-100" aria-label="Naik">&#9650;</button>
                                                        <button type="button" @click="move(index, 1)" class="rounded border border-gray-300 bg-white px-2 py-1 text-xs font-bold text-gray-700 hover:bg-gray-100" aria-label="Turun">&#9660;</button>
                                                    </div>
                                                    <input type="hidden" :name="`jawaban[${leftItems[index]}]`" :value="item.value">
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="text-red-500">Tipe soal tidak dikenali.</p>
                        @endif

                        <div class="mt-8 pt-4 border-t flex justify-between items-center">

                            @if ($soalIndex > 1)
                                <a href="{{ route('siswa.task', [$terbitanTugas->id, $soalIndex - 1]) }}"
                                    class="px-5 py-2 bg-gray-500 text-white rounded-lg font-semibold hover:bg-gray-600 transition-colors">
                                    &larr; Soal Sebelumnya
                                </a>
                            @else
                                <span></span>
                            @endif

                            <button type="submit" name="action"
                                value="{{ $soalIndex < $totalSoal ? 'next' : 'finish' }}"
                                class="px-8 py-3 
                                {{ $soalIndex < $totalSoal ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-green-600 hover:bg-green-700' }} 
                                text-white rounded-lg font-semibold transition-colors">
                                {{ $soalIndex < $totalSoal ? 'Selanjutnya' : 'Selesai & Kumpulkan Jawaban' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="task-focus-warning" class="fixed inset-x-3 top-3 z-[60] hidden rounded-xl border border-amber-300 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-900 shadow-xl sm:left-auto sm:right-6 sm:max-w-md" role="alert">
        <div class="flex items-start gap-3">
            <span class="text-lg" aria-hidden="true">!</span>
            <p id="task-focus-warning-text" class="flex-1"></p>
            <button type="button" id="dismiss-focus-warning" class="text-amber-700 hover:text-amber-950" aria-label="Tutup peringatan">&times;</button>
        </div>
    </div>

    <script>
        window.taskPageAllowExit = false;

        (() => {
            const warning = document.getElementById('task-focus-warning');
            const warningText = document.getElementById('task-focus-warning-text');
            const dismissWarning = document.getElementById('dismiss-focus-warning');
            let wasHidden = false;
            let warningTimeout;
            let titleInterval;
            const originalTitle = document.title;
            const alarmAudio = new Audio(@json(asset('audio/alarm.mpeg')));
            alarmAudio.loop = true;
            alarmAudio.volume = 0.8;

            const startAlarm = () => {
                if (!titleInterval) {
                    let titleAlertVisible = false;
                    titleInterval = setInterval(() => {
                        titleAlertVisible = !titleAlertVisible;
                        document.title = titleAlertVisible ? '!! Kembali ke tugas' : originalTitle;
                    }, 800);
                }

                if (alarmAudio.paused) {
                    alarmAudio.currentTime = 0;
                    alarmAudio.play().catch(() => {
                        warningText.textContent = 'Alarm tidak dapat diputar. Klik halaman terlebih dahulu untuk mengizinkan suara.';
                    });
                }
            };

            const stopAlarm = () => {
                clearInterval(titleInterval);
                titleInterval = null;
                document.title = originalTitle;
                alarmAudio.pause();
                alarmAudio.currentTime = 0;
            };

            const showWarning = (message) => {
                warningText.textContent = message;
                warning.classList.remove('hidden');
                clearTimeout(warningTimeout);
                warningTimeout = setTimeout(() => warning.classList.add('hidden'), 6000);
            };

            dismissWarning.addEventListener('click', () => warning.classList.add('hidden'));

            document.addEventListener('visibilitychange', () => {
                if (document.visibilityState === 'hidden') {
                    if (window.taskPageAllowExit) return;
                    wasHidden = true;
                    fetch(@json(route('siswa.focusLoss', $terbitanTugas->id)), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': @json(csrf_token()),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ reason: 'visibilitychange' }),
                        keepalive: true
                    }).catch(() => {});
                    startAlarm();
                    return;
                }

                stopAlarm();
                if (wasHidden && !window.taskPageAllowExit) {
                    wasHidden = false;
                    showWarning('Anda kembali ke halaman tugas. Jangan membuka tab atau aplikasi lain selama mengerjakan.');
                }
            });

            window.addEventListener('blur', () => {
                if (!window.taskPageAllowExit) startAlarm();
            });

            window.addEventListener('focus', () => {
                if (document.visibilityState === 'visible') stopAlarm();
            });

            window.addEventListener('beforeunload', (event) => {
                if (window.taskPageAllowExit) return;
                event.preventDefault();
                event.returnValue = '';
            });

            history.pushState({ taskPage: true }, '', window.location.href);
            window.addEventListener('popstate', () => {
                if (window.taskPageAllowExit || window.confirm('Anda akan meninggalkan halaman tugas. Jawaban yang belum tersimpan dapat hilang. Lanjutkan?')) {
                    window.taskPageAllowExit = true;
                    history.back();
                    return;
                }

                history.pushState({ taskPage: true }, '', window.location.href);
            });

            document.querySelectorAll('a[href]').forEach((link) => {
                link.addEventListener('click', () => {
                    window.taskPageAllowExit = true;
                });
            });
        })();
    </script>



    <script>
        document.getElementById('submit-answer-form').addEventListener('submit', function(event) {
            const form = event.currentTarget;

            const soalType = "{{ $soal->tipe_soal_di_tugas }}";
            let isAnswered = false;

            if (soalType === 'pilihan_ganda') {
                const radios = form.elements['jawaban'];
                for (let i = 0; i < radios.length; i++) {
                    if (radios[i].checked) {
                        isAnswered = true;
                        break;
                    }
                }
            } else if (soalType === 'uraian') {
                const textarea = document.getElementById('uraian_jawaban');
                if (textarea && textarea.value.trim() !== '') {
                    isAnswered = true;
                }
            } else if (soalType === 'menjodohkan') {
                const hiddenInputs = form.querySelectorAll('input[name^="jawaban["]');
                for (const hiddenInput of hiddenInputs) {
                    if (hiddenInput.value && hiddenInput.value.trim() !== '') {
                        isAnswered = true;
                        break;
                    }
                }
            }

            const action = form.elements['action'].value;


            if (!isAnswered && (action === 'next' || action === 'finish')) {
                event.preventDefault();
                alert("Anda harus memilih/mengisi jawaban sebelum Simpan & Lanjut atau Selesai.");
            }

            if (!event.defaultPrevented) {
                window.taskPageAllowExit = true;
            }
        });


        const radioInputs = document.querySelectorAll('input[name="jawaban"]');

        const savedAnswer = "{{ $jawabanSiswa }}";

        radioInputs.forEach(input => {

            if (input.value == savedAnswer && savedAnswer !== '') {
                input.checked = true;
            }


            input.addEventListener('change', function() {

                document.querySelectorAll('.space-y-3 > label').forEach(label => {
                    label.classList.remove('bg-indigo-50', 'border-indigo-500', 'ring-2',
                        'ring-indigo-500');
                    label.classList.add('bg-white', 'border-gray-200', 'hover:border-indigo-400');
                });


                if (this.checked) {
                    const currentLabel = this.closest('label');
                    currentLabel.classList.add('bg-indigo-50', 'border-indigo-500', 'ring-2',
                        'ring-indigo-500');
                    currentLabel.classList.remove('bg-white', 'border-gray-200', 'hover:border-indigo-400');
                }
            });


            if (input.checked) {

                setTimeout(() => input.dispatchEvent(new Event('change')), 0);
            }
        });
    </script>

    @if ($terbitanTugas->durasi_menit > 0)
        <script>
            // Ambil sisa waktu dari Laravel (dalam detik)
            let timeRemaining = {{ $remainingTime }};
            const timerElement = document.getElementById('countdown-timer');
            const submitForm = document.getElementById('submit-answer-form');

            function updateTimer() {
                if (timeRemaining <= 0) {
                    timerElement.textContent = "Waktu Habis!";

                    clearInterval(timerInterval);

                    // Otomatis kumpulkan jawaban (submit form)
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'action';
                    hiddenInput.value = 'finish';
                    submitForm.appendChild(hiddenInput);

                    // Kirim form
                    submitForm.submit();

                    return;
                }

                // Hitungan Mundur
                const hours = Math.floor(timeRemaining / 3600);
                const minutes = Math.floor((timeRemaining % 3600) / 60);
                const seconds = timeRemaining % 60;

                const displayHours = hours > 0 ? hours.toString().padStart(2, '0') + ':' : '';
                const displayMinutes = minutes.toString().padStart(2, '0');
                const displaySeconds = seconds.toString().padStart(2, '0');

                timerElement.textContent = displayHours + displayMinutes + ':' + displaySeconds;

                // Peringatan waktu
                if (timeRemaining <= 300 && timeRemaining > 0) {
                    timerElement.classList.add('animate-pulse');
                } else {
                    timerElement.classList.remove('animate-pulse');
                }

                timeRemaining--;
            }

            updateTimer();
            const timerInterval = setInterval(updateTimer, 1000);
        </script>
    @endif


</body>

</html>
