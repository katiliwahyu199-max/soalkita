<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pengumpulan Tugas</title>
    <link rel="icon" href="{{ asset('img/tutwuri.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 font-sans min-h-screen flex items-center justify-center p-3 sm:p-6">

    <div class="w-full max-w-lg mx-auto p-5 sm:p-8 md:p-10 bg-white rounded-2xl shadow-xl border border-slate-200 text-center">
        <svg class="w-16 h-16 text-yellow-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.398 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6">Konfirmasi Pengumpulan Tugas</h1>
        
        <p class="text-lg text-gray-700 mb-2">
            Halo, <strong class="text-indigo-600">{{ $session['student_name'] ?? 'Siswa' }}</strong>.
        </p>
        <p class="text-gray-600 mb-8">
            Anda akan mengumpulkan tugas {{ $terbitanTugas->tugas->nama_tugas }} untuk  {{ $terbitanTugas->kelas->nama_kelas }}. Sebelum Menyimpan Jawaban, Silakan Periksa Kembali dan pastikan jawaban anda sudah benar
        </p>

        <div class="grid grid-cols-2 gap-4 text-left mb-10 p-5 bg-gray-50 rounded-lg border">
            <div class="col-span-2 text-sm font-semibold text-gray-600 border-b pb-2 mb-2">Ringkasan Pengerjaan</div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Soal</p>
                <p class="text-xl font-bold text-gray-900">{{ $totalSoal }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Waktu Digunakan</p>
                <p class="text-xl font-bold text-gray-900">{{ round($timeTaken, 1) }} Menit</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Jawaban Terisi</p>
                <p class="text-xl font-bold text-green-600">{{ $answeredCount }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Belum Dijawab</p>
                <p class="text-xl font-bold text-red-600">{{ $unansweredCount }}</p>
            </div>
        </div>

        <form id="submit-task-form" action="{{ route('siswa.submit', $terbitanTugas->id) }}" method="POST">
            @csrf
            
            <button type="button" id="open-submit-confirmation"
                    class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg font-semibold text-base sm:text-lg hover:bg-blue-700 active:bg-blue-800 transition-colors mb-3 shadow-sm"
                    aria-haspopup="dialog" aria-controls="submit-confirmation">
                YA, Kumpulkan Jawaban Sekarang
            </button>

            <dialog id="submit-confirmation" aria-labelledby="submit-confirmation-title" class="m-auto w-[calc(100%-2rem)] max-w-md rounded-2xl border-0 bg-transparent p-0 shadow-2xl backdrop:bg-slate-950/60">
                <div class="rounded-2xl bg-white p-6 text-left">
                    <h2 id="submit-confirmation-title" class="text-xl font-bold text-gray-900">Kirim jawaban sekarang?</h2>
                    <p class="mt-3 text-gray-600">
                        Setelah dikumpulkan, jawaban tidak dapat diubah lagi. Pastikan Anda sudah memeriksa semua jawaban.
                    </p>
                    <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <button type="button" id="cancel-submit-confirmation"
                                class="rounded-lg border border-gray-300 px-4 py-2 font-semibold text-gray-700 hover:bg-gray-50">
                            Periksa Kembali
                        </button>
                        <button type="submit" id="confirm-submit-task"
                                class="rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">
                            Ya, Kirim Jawaban
                        </button>
                    </div>
                </div>
            </dialog>
        </form>
        
        <a href="{{ route('siswa.task', [$terbitanTugas->id, 1]) }}" class="text-sm text-indigo-600 hover:text-indigo-800">
            &larr; Kembali ke Soal (Cek Ulang)
        </a>
    </div>

    <script>
        const submitDialog = document.getElementById('submit-confirmation');
        const openSubmitConfirmation = document.getElementById('open-submit-confirmation');
        const cancelSubmitConfirmation = document.getElementById('cancel-submit-confirmation');

        openSubmitConfirmation.addEventListener('click', () => submitDialog.showModal());
        cancelSubmitConfirmation.addEventListener('click', () => submitDialog.close());
        submitDialog.addEventListener('cancel', (event) => event.preventDefault());
    </script>
</body>
</html>