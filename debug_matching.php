<?php
require __DIR__ . '/vendor/autoload.php';

$terbitan = new App\Models\TerbitanTugas();
$terbitan->setRelation('tugas', new App\Models\Tugas(['jenis_tugas' => 'gabungan']));
$terbitan->tugas->setRelation('soals', new Illuminate\Support\Collection([
    new App\Models\Soal([
        'id' => 20,
        'tipe_soal_di_tugas' => 'menjodohkan',
        'kunci_jawaban' => json_encode(['A' => '1', 'B' => '2']),
    ]),
]));

$allSoals = $terbitan->tugas->soals;
$soalMenjodohkan = $allSoals->where('tipe_soal_di_tugas', 'menjodohkan');
var_dump('jenis_tugas', $terbitan->tugas->jenis_tugas);
var_dump('all types', $allSoals->pluck('tipe_soal_di_tugas')->all());
var_dump('count', $soalMenjodohkan->count());
$totalPG = 0;
$correctPG = 0;
foreach ($soalMenjodohkan as $soal) {
    $jawabanSiswa = ['A' => '1', 'B' => '2'];
    $kunci = json_decode($soal->kunci_jawaban, true);
    $pairCount = count($kunci);
    $totalPG += $pairCount;
    $matchedPairs = 0;
    foreach ($kunci as $left => $correctRight) {
        $submittedRight = $jawabanSiswa[$left] ?? null;
        if ((string) $submittedRight === (string) $correctRight) {
            $matchedPairs++;
        }
    }
    $correctPG += $matchedPairs;
    var_dump('pairCount', $pairCount, 'matchedPairs', $matchedPairs, 'correctPG', $correctPG, 'totalPG', $totalPG);
}
$skorPG = $totalPG > 0 ? ($correctPG / $totalPG) * 100.0 : 0.0;
var_dump('skorPG', $skorPG);
$result = (new App\Services\PenilaianService())->hitungNilaiOtomatis($terbitan, [20 => ['A' => '1', 'B' => '2']]);
var_dump('service', $result);
