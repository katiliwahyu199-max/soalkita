<?php

namespace Tests\Unit;

use App\Models\Soal;
use App\Models\TerbitanTugas;
use App\Models\Tugas;
use App\Services\PenilaianService;
use Illuminate\Support\Collection;
use Tests\TestCase;

class PenilaianServiceTest extends TestCase
{
    public function test_uraian_tidak_memberi_nilai_penuh_untuk_jawaban_yang_masih_salah(): void
    {
        $terbitan = new TerbitanTugas();
        $terbitan->setRelation('tugas', new Tugas(['jenis_tugas' => 'uraian']));
        $soalUraian = new Soal([
            'tipe_soal_di_tugas' => 'uraian',
            'kunci_jawaban' => 'Fotosintesis adalah proses tumbuhan membuat makanan sendiri',
        ]);
        $soalUraian->id = 10;
        $terbitan->tugas->setRelation('soals', new Collection([$soalUraian]));

        $result = (new PenilaianService())->hitungNilaiOtomatis($terbitan, [
            10 => 'Tumbuhan membuat makanan sendiri di siang hari',
        ]);

        $this->assertLessThan(60, $result['nilai_otomatis']);
        $this->assertNotEquals(100.0, $result['nilai_otomatis']);
    }

    public function test_menjodohkan_memberi_skor_berdasarkan_pencocokan_yang_tepat(): void
    {
        $terbitan = new TerbitanTugas();
        $terbitan->setRelation('tugas', new Tugas(['jenis_tugas' => 'gabungan']));
        $soalMatching = new Soal([
            'tipe_soal_di_tugas' => 'menjodohkan',
            'kunci_jawaban' => json_encode([
                'A' => '1',
                'B' => '2',
            ]),
        ]);
        $soalMatching->id = 20;
        $terbitan->tugas->setRelation('soals', new Collection([$soalMatching]));

        $result = (new PenilaianService())->hitungNilaiOtomatis($terbitan, [
            20 => ['A' => '1', 'B' => '2'],
        ]);

        $this->assertSame(70.0, $result['nilai_otomatis']);
    }
}
