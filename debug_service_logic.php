<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Soal;
use App\Models\TerbitanTugas;
use App\Models\Tugas;
use Illuminate\Support\Collection;

$terbitan = new TerbitanTugas();
$terbitan->setRelation('tugas', new Tugas(['jenis_tugas' => 'gabungan']));
$terbitan->tugas->setRelation('soals', new Collection([
    new Soal([
        'id' => 20,
        'tipe_soal_di_tugas' => 'menjodohkan',
        'kunci_jawaban' => json_encode(['A' => '1', 'B' => '2']),
    ]),
]));

$allSoals = $terbitan->tugas->soals;
$soalPG = $allSoals->where('tipe_soal_di_tugas', 'pilihan_ganda');
$soalUraian = $allSoals->where('tipe_soal_di_tugas', 'uraian');
$soalMenjodohkan = $allSoals->where('tipe_soal_di_tugas', 'menjodohkan');

$totalPG = $soalPG->count();
$totalUraian = $soalUraian->count();
$correctPG = 0;
$achievedUraianPoints = 0.0;

foreach ($soalPG as $soal) {
    $jawaban = ['20' => ['A' => '1', 'B' => '2']][$soal->id] ?? null;
    if (!is_null($jawaban) && (string) $jawaban === (string) $soal->kunci_jawaban) {
        $correctPG++;
    }
}

foreach ($soalMenjodohkan as $soal) {
    $kunci = json_decode($soal->kunci_jawaban ?? null, true);
    $jawaban = ['20' => ['A' => '1', 'B' => '2']][$soal->id] ?? null;
    $jawaban = is_array($jawaban) ? $jawaban : [];
    $pairCount = count($kunci);
    var_dump('pairCount', $pairCount, 'kunci', $kunci, 'jawaban', $jawaban);

    if ($pairCount > 0) {
        $totalPG += $pairCount;
        $matchedPairs = 0;

        foreach ($kunci as $leftKey => $correctRight) {
            $submitted = $jawaban[$leftKey] ?? null;
            var_dump('compare', $leftKey, $submitted, $correctRight, (string)$submitted === (string)$correctRight);
            if ((string) $submitted === (string) $correctRight) {
                $matchedPairs++;
            }
        }

        $correctPG += $matchedPairs;
    }
}

var_dump('totals', $totalPG, $correctPG);
$skorPG = $totalPG > 0 ? ($correctPG / $totalPG) * 100.0 : 0.0;
var_dump('skorPG', $skorPG);
