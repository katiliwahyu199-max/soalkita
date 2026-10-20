<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Soal;
use App\Models\TerbitanTugas;
use App\Models\Tugas;
use App\Services\PenilaianService;
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

$ref = new ReflectionClass(PenilaianService::class);
var_dump($ref->getFileName());
$method = $ref->getMethod('hitungNilaiOtomatis');
var_dump($method->getFileName(), $method->getStartLine(), $method->getEndLine());
var_dump($terbitan->tugas->soals->where('tipe_soal_di_tugas', 'menjodohkan')->count());
$result = $method->invoke(new PenilaianService(), $terbitan, [20 => ['A' => '1', 'B' => '2']]);
var_dump('invoke', $result);
var_dump('direct', (new PenilaianService())->hitungNilaiOtomatis($terbitan, [20 => ['A' => '1', 'B' => '2']]));
