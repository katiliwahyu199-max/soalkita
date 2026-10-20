<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg leading-tight"> Buat Tugas Baru untuk {{ $kelas->nama_kelas }}</h2>
    </x-slot>

    <div class="py-0">
        <div class="max-w-full mx-auto">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-4 sm:p-4 text-gray-900">
                    <div class="mb-3">
                        <a href="{{ route('kelas.detail', $kelas->id) }}"
                            class="inline-flex items-center px-3 py-1 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            &larr; Batal dan Kembali
                        </a>
                    </div>

                    {{-- Tampilkan error jika ada --}}
                    @if (session('error'))
                        <div class="mb-3 p-3 bg-red-100 text-red-700 rounded-md text-sm">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="mb-3 p-3 bg-red-100 text-red-700 rounded-md text-sm">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('tugas.store') }}" method="POST" x-data="tugasForm()" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">

                        <div class="mb-3">
                            <label for="nama_tugas" class="block font-medium text-sm text-gray-700">Nama Tugas</label>
                            <input type="text" name="nama_tugas" id="nama_tugas" value="{{ old('nama_tugas') }}" required
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                        </div>

                        <div class="mb-4">
                            <label for="jenis_tugas" class="block font-medium text-sm text-gray-700">Jenis Tugas</label>
                            <select name="jenis_tugas" id="jenis_tugas" x-model="jenisTugas" required
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                <option value="pilihan_ganda">Pilihan Ganda</option>
                                <option value="uraian">Uraian</option>
                                <option value="menjodohkan">Menjodohkan</option>
                                <option value="gabungan">Gabungan (Pilihan Ganda, Uraian & Menjodohkan)</option>
                            </select>
                        </div>

                        <hr class="my-4">
                        <h3 class="text-lg font-bold mb-3">Daftar Soal</h3>

                        <!-- Loop Soal -->
                        <template x-for="(soal, index) in soals" :key="index">
                            <div class="border border-gray-300 rounded-lg p-3 mb-3 bg-white">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2">
                                    <h4 class="text-base font-semibold" x-text="'Soal ' + (index + 1)"></h4>
                                    <button type="button" @click="hapusSoal(index)"
                                        class="mt-1 sm:mt-0 px-3 py-1 bg-red-500 text-white text-xs rounded-md hover:bg-red-600">
                                        Hapus Soal
                                    </button>
                                </div>

                                <template x-if="jenisTugas === 'gabungan'">
                                    <div class="mb-3">
                                        <label class="block font-medium text-sm text-gray-700">Tipe Soal Ini</label>
                                        <select :name="'soal[' + index + '][tipe_soal]'" x-model="soal.tipe_soal"
                                            class="block mt-1 w-full rounded-md shadow-sm border-gray-300 text-sm">
                                            <option value="pilihan_ganda">Pilihan Ganda</option>
                                            <option value="uraian">Uraian</option>
                                            <option value="menjodohkan">Menjodohkan</option>
                                        </select>
                                    </div>
                                </template>

                                <template x-if="jenisTugas !== 'gabungan'">
                                    <input type="hidden" :name="'soal[' + index + '][tipe_soal]'" :value="soal.tipe_soal" />
                                </template>

                                <!-- Keterangan & Gambar Opsional -->
                                <div class="border-t border-gray-200 mt-3 pt-3">
                                    <button type="button" @click="soal.showOptionalFields = !soal.showOptionalFields"
                                        class="text-xs text-indigo-600 hover:text-indigo-800 font-medium mb-2">
                                        <span x-show="!soal.showOptionalFields">+ Tambah Keterangan / Gambar (Opsional)</span>
                                        <span x-show="soal.showOptionalFields" style="display: none;">- Sembunyikan Keterangan / Gambar</span>
                                    </button>

                                    <div x-show="soal.showOptionalFields" style="display: none;"
                                        class="space-y-3 p-3 bg-gray-50 rounded-md border border-gray-200 mb-3">
                                        <div>
                                            <label class="block font-medium text-sm text-gray-700">Keterangan Soal</label>
                                            <textarea :name="'soal[' + index + '][keterangan_soal]'" x-model="soal.keterangan_soal" rows="2"
                                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 text-sm"
                                                placeholder="Misal: Perhatikan gambar berikut untuk menjawab..."></textarea>
                                        </div>
                                        <div>
                                            <label class="block font-medium text-sm text-gray-700">Gambar Soal</label>
                                            <input type="file" :name="'soal[' + index + '][gambar_soal]'"
                                                class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 mt-3">
                                    <label class="block font-medium text-sm text-gray-700">Pertanyaan</label>
                                    <textarea :name="'soal[' + index + '][pertanyaan]'" x-model="soal.pertanyaan" rows="2" required
                                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300 text-sm"></textarea>
                                </div>

                                <!-- Opsi Pilihan Ganda -->
                                <template x-if="soal.tipe_soal === 'pilihan_ganda'">
                                    <div class="pl-3 border-l-4 border-gray-200 mb-3">
                                        <h5 class="font-semibold text-sm mb-2">Opsi Jawaban</h5>
                                        <template x-for="(opsi, opsiIndex) in soal.opsi" :key="opsiIndex">
                                            <div class="flex items-start mb-2 p-2 border rounded-md bg-gray-50">
                                                <input :name="'soal[' + index + '][kunci_jawaban_pg]'" :value="opsiIndex" type="radio"
                                                    x-model="soal.kunci_jawaban_pg" class="mr-2 mt-2 focus:ring-blue-500 text-sm">

                                                <span class="mr-2 font-medium w-4 mt-1.5 text-sm"
                                                    x-text="String.fromCharCode(65 + opsiIndex) + '.'"></span>

                                                <div class="flex-1">
                                                    <div class="mb-1">
                                                        <select :name="'soal[' + index + '][opsi][' + opsiIndex + '][tipe]'" x-model="opsi.tipe"
                                                            class="text-xs rounded-md border-gray-300 shadow-sm py-1">
                                                            <option value="teks">Teks</option>
                                                            <option value="gambar">Gambar</option>
                                                        </select>
                                                    </div>

                                                    <div x-show="opsi.tipe === 'teks'">
                                                        <input type="text"
                                                            :name="'soal[' + index + '][opsi][' + opsiIndex + '][konten_teks]'"
                                                            x-model="opsi.konten_teks" placeholder="Teks Opsi Jawaban"
                                                            class="block w-full rounded-md shadow-sm border-gray-300 text-sm py-1">
                                                    </div>

                                                    <div x-show="opsi.tipe === 'gambar'" style="display: none;">
                                                        <input type="file"
                                                            :name="'soal[' + index + '][opsi][' + opsiIndex + '][konten_gambar]'"
                                                            class="block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                                                        <span class="text-xs text-gray-500 italic">Upload gambar untuk opsi ini.</span>
                                                    </div>
                                                </div>

                                                <button type="button" @click="hapusOpsi(index, opsiIndex)"
                                                    class="ml-2 text-red-500 hover:text-red-700 text-xs mt-1.5">
                                                    Hapus
                                                </button>
                                            </div>
                                        </template>

                                        <button type="button" @click="tambahOpsi(index)"
                                            class="mt-1 px-3 py-1 bg-gray-200 text-gray-700 text-xs rounded-md hover:bg-gray-300">
                                            + Tambah Opsi
                                        </button>
                                    </div>
                                </template>

                                <!-- Kunci Jawaban Uraian -->
                                <template x-if="soal.tipe_soal === 'uraian'">
                                    <div class="pl-3 border-l-4 border-blue-200 mb-3">
                                        <label class="block font-medium text-sm text-gray-700">Kunci Jawaban (Uraian)</label>
                                        <textarea :name="'soal[' + index + '][kunci_jawaban_uraian]'" x-model="soal.kunci_jawaban_uraian" rows="2"
                                            class="block mt-1 w-full rounded-md shadow-sm border-gray-300 text-sm"></textarea>
                                    </div>
                                </template>

                                <template x-if="soal.tipe_soal === 'menjodohkan'">
                                    <div class="pl-3 border-l-4 border-purple-200 mb-3">
                                        <label class="block font-medium text-sm text-gray-700">Kunci Jawaban Menjodohkan</label>

                                        <div class="mt-2 space-y-2">
                                            <template x-for="(pair, pairIndex) in (soal.matching_pairs || [])" :key="pairIndex">
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 p-2 border border-purple-200 rounded-md bg-purple-50">
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-600 mb-1">Item kiri</label>
                                                        <input type="text" x-model="pair.left" @input="sinkronkanPasangan(soal)"
                                                            class="w-full rounded-md border-gray-300 text-sm"
                                                            placeholder="contoh: A">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-600 mb-1">Pasangan yang benar</label>
                                                        <input type="text" x-model="pair.right" @input="sinkronkanPasangan(soal)"
                                                            class="w-full rounded-md border-gray-300 text-sm"
                                                            placeholder="contoh: 1">
                                                    </div>
                                                </div>
                                            </template>
                                        </div>

                                        <div class="flex gap-2 mt-3">
                                            <button type="button" @click="tambahPasangan(soal)"
                                                class="px-3 py-1.5 bg-purple-600 text-white text-xs rounded-md hover:bg-purple-500">
                                                + Tambah Pasangan
                                            </button>
                                            <button type="button" @click="hapusPasangan(soal)"
                                                class="px-3 py-1.5 bg-gray-200 text-gray-700 text-xs rounded-md hover:bg-gray-300">
                                                Hapus Pasangan
                                            </button>
                                        </div>

                                        <textarea :name="'soal[' + index + '][kunci_jawaban_menjodohkan]'" x-model="soal.kunci_jawaban_menjodohkan" rows="2"
                                            class="hidden"></textarea>

                                        <p class="text-xs text-gray-500 mt-2">Contoh: A → 1, B → 2. Tulis pasangan yang benar satu per baris.</p>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <!-- Tombol Tambah Soal Utama -->
                        <div class="my-4">
                            <button type="button" @click="tambahSoal()"
                                class="px-4 py-2 bg-gray-800 text-white font-medium rounded-md hover:bg-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-800">
                                + Tambah Soal Baru
                            </button>
                        </div>

                        <hr class="my-4">
                        <div>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700">
                                Simpan Tugas
                            </button>
                        </div>
                    </form>

                    @php
                        $default_opsi = [
                            ['tipe' => 'teks', 'konten_teks' => ''],
                            ['tipe' => 'teks', 'konten_teks' => ''],
                        ];

                        $default_soal = [
                            [
                                'pertanyaan' => '',
                                'keterangan_soal' => '',
                                'showOptionalFields' => false,
                                'tipe_soal' => 'pilihan_ganda',
                                'opsi' => $default_opsi,
                                'kunci_jawaban_pg' => null,
                                'kunci_jawaban_uraian' => '',
                                'kunci_jawaban_menjodohkan' => '{"A":"1","B":"2"}',
                                'matching_pairs' => [
                                    ['left' => 'A', 'right' => '1'],
                                    ['left' => 'B', 'right' => '2'],
                                ],
                            ],
                        ];

                        $soals_data = old('soal') ? array_values(old('soal')) : $default_soal;

                        foreach ($soals_data as $key => $soal) {
                            if (empty($soal['opsi'])) {
                                $soals_data[$key]['opsi'] = $default_opsi;
                            }
                            if (!isset($soals_data[$key]['showOptionalFields'])) {
                                $soals_data[$key]['showOptionalFields'] = false;
                            }
                        }

                        $soals_json = json_encode($soals_data);
                    @endphp

                    <script>
                        function tugasForm() {
                            return {
                                jenisTugas: '{{ old('jenis_tugas', 'pilihan_ganda') }}',
                                soals: {!! $soals_json !!},

                                init() {
                                    this.$watch('jenisTugas', (newJenis) => {
                                        this.soals.forEach(soal => {
                                            if (newJenis !== 'gabungan') {
                                                soal.tipe_soal = newJenis;
                                            }
                                        });
                                    });

                                    let initialJenis = this.jenisTugas;
                                    this.soals.forEach(soal => {
                                        if (initialJenis !== 'gabungan') {
                                            soal.tipe_soal = initialJenis;
                                        }
                                        if (!soal.opsi) soal.opsi = [{ tipe: 'teks', konten_teks: '' }, { tipe: 'teks', konten_teks: '' }];
                                        if (soal.kunci_jawaban_pg === undefined) soal.kunci_jawaban_pg = null;
                                        if (soal.kunci_jawaban_uraian === undefined) soal.kunci_jawaban_uraian = '';
                                        if (soal.kunci_jawaban_menjodohkan === undefined) soal.kunci_jawaban_menjodohkan = '{"A":"1","B":"2"}';
                                        if (soal.showOptionalFields === undefined) soal.showOptionalFields = false;
                                        if (!Array.isArray(soal.matching_pairs)) {
                                            this.parseMatchingPairs(soal);
                                        }

                                        soal.opsi.forEach(op => {
                                            if (op.tipe === 'teks' && op.konten_teks === undefined) {
                                                op.konten_teks = '';
                                            }
                                        });
                                    });
                                },

                                parseMatchingPairs(soal) {
                                    try {
                                        const parsed = JSON.parse(soal.kunci_jawaban_menjodohkan || '{}');
                                        if (parsed && typeof parsed === 'object' && !Array.isArray(parsed)) {
                                            soal.matching_pairs = Object.entries(parsed).map(([left, right]) => ({ left, right }));
                                            return;
                                        }
                                    } catch (error) {}

                                    soal.matching_pairs = [
                                        { left: 'A', right: '1' },
                                        { left: 'B', right: '2' }
                                    ];
                                    this.sinkronkanPasangan(soal);
                                },

                                sinkronkanPasangan(soal) {
                                    if (!Array.isArray(soal.matching_pairs)) {
                                        soal.matching_pairs = [];
                                    }

                                    const normalized = [];
                                    soal.matching_pairs.forEach((pair) => {
                                        const left = String(pair.left ?? '').trim();
                                        const right = String(pair.right ?? '').trim();
                                        if (left !== '' || right !== '') {
                                            normalized.push({ left, right });
                                        }
                                    });

                                    if (normalized.length === 0) {
                                        normalized.push({ left: 'A', right: '1' });
                                    }

                                    soal.matching_pairs = normalized;

                                    const objectPairs = {};
                                    normalized.forEach((pair) => {
                                        if (pair.left && pair.right) {
                                            objectPairs[String(pair.left)] = String(pair.right);
                                        }
                                    });

                                    soal.kunci_jawaban_menjodohkan = JSON.stringify(objectPairs);
                                },

                                tambahPasangan(soal) {
                                    if (!Array.isArray(soal.matching_pairs) || soal.matching_pairs.length === 0) {
                                        soal.matching_pairs = [];
                                    }

                                    const nextIndex = soal.matching_pairs.length + 1;
                                    soal.matching_pairs.push({
                                        left: String.fromCharCode(65 + (soal.matching_pairs.length % 26)),
                                        right: String(nextIndex)
                                    });
                                    this.sinkronkanPasangan(soal);
                                },

                                hapusPasangan(soal) {
                                    if (!Array.isArray(soal.matching_pairs) || soal.matching_pairs.length <= 1) {
                                        soal.matching_pairs = [{ left: 'A', right: '1' }];
                                        this.sinkronkanPasangan(soal);
                                        return;
                                    }

                                    soal.matching_pairs.pop();
                                    this.sinkronkanPasangan(soal);
                                },

                                tambahSoal() {
                                    let tipeSoalBaru = this.jenisTugas === 'gabungan' ? 'pilihan_ganda' : this.jenisTugas;
                                    this.soals.push({
                                        pertanyaan: '',
                                        keterangan_soal: '',
                                        showOptionalFields: false,
                                        tipe_soal: tipeSoalBaru,
                                        opsi: [
                                            { tipe: 'teks', konten_teks: '' },
                                            { tipe: 'teks', konten_teks: '' }
                                        ],
                                        kunci_jawaban_pg: null,
                                        kunci_jawaban_uraian: '',
                                        kunci_jawaban_menjodohkan: '{"A":"1","B":"2"}',
                                        matching_pairs: [
                                            { left: 'A', right: '1' },
                                            { left: 'B', right: '2' }
                                        ]
                                    });
                                },
                                hapusSoal(index) {
                                    this.soals.splice(index, 1);
                                },
                                tambahOpsi(soalIndex) {
                                    this.soals[soalIndex].opsi.push({ tipe: 'teks', konten_teks: '' });
                                },
                                hapusOpsi(soalIndex, opsiIndex) {
                                    this.soals[soalIndex].opsi.splice(opsiIndex, 1);
                                }
                            }
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>