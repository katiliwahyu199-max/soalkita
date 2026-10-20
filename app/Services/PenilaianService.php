<?php

namespace App\Services;

use App\Models\TerbitanTugas;

class PenilaianService
{
    protected const MAX_SCORE_URAIAN_PER_SOAL = 10.0;
    protected const MIN_SCORE_URAIAN_PER_SOAL = 1.0;

    public function hitungNilaiOtomatis(TerbitanTugas $terbitan, array $jawabanSiswa): array
    {
        $totalPG = 0;
        $correctPG = 0;
        $totalUraian = 0;
        $achievedUraianPoints = 0.0;

        foreach ($terbitan->tugas->soals as $soal) {
            $type = $soal->tipe_soal_di_tugas;

            if ($type === 'pilihan_ganda') {
                $totalPG++;
                $jawaban = $jawabanSiswa[$soal->id] ?? null;
                if (!is_null($jawaban) && (string) $jawaban === (string) ($soal->kunci_jawaban ?? '')) {
                    $correctPG++;
                }
                continue;
            }

            if ($type === 'menjodohkan') {
                $kunci = $this->decodeMatchingAnswer($soal->kunci_jawaban ?? null);
                $jawaban = $this->decodeMatchingAnswer($jawabanSiswa[$soal->id] ?? null);
                $pairCount = count($kunci);

                if ($pairCount > 0) {
                    $totalPG += $pairCount;
                    foreach ($kunci as $leftKey => $correctRight) {
                        $submitted = $jawaban[$leftKey] ?? null;
                        if ((string) $submitted === (string) $correctRight) {
                            $correctPG++;
                        }
                    }
                }
                continue;
            }

            if ($type === 'uraian') {
                $totalUraian++;
                $jawaban = (string) ($jawabanSiswa[$soal->id] ?? '');
                $kunci = (string) ($soal->kunci_jawaban ?? '');

                if (!empty(trim($jawaban)) && !empty(trim($kunci))) {
                    $matchRatio = $this->getUraianKeywordMatch($jawaban, $kunci);
                    $scoreRange = self::MAX_SCORE_URAIAN_PER_SOAL - self::MIN_SCORE_URAIAN_PER_SOAL;
                    $achievedUraianPoints += self::MIN_SCORE_URAIAN_PER_SOAL + ($scoreRange * $matchRatio);
                }
            }
        }

        $maxUraianPoints = $totalUraian * self::MAX_SCORE_URAIAN_PER_SOAL;
        $skorPG = $totalPG > 0 ? ($correctPG / $totalPG) * 100.0 : 0.0;
        $skorUraian = $maxUraianPoints > 0 ? ($achievedUraianPoints / $maxUraianPoints) * 100.0 : 0.0;

        $jenisTugas = $terbitan->tugas->jenis_tugas;
        $nilaiOtomatis = 0.0;

        if ($jenisTugas === 'gabungan') {
            $nilaiOtomatis = ($skorPG * 0.70) + ($skorUraian * 0.30);
        } elseif ($totalPG > 0 && $totalUraian === 0) {
            $nilaiOtomatis = $skorPG;
        } elseif ($totalUraian > 0 && $totalPG === 0) {
            $nilaiOtomatis = $skorUraian;
        }

        return [
            'nilai_otomatis' => round($nilaiOtomatis, 2),
            'status_penilaian' => 'sudah_dinilai',
        ];
    }

    protected function getUraianKeywordMatch(string $siswaJawab, string $kunci): float
    {
        $cleanKunci = strtolower(preg_replace('/[^a-z0-9\s]/', '', $kunci));
        $cleanJawab = strtolower(preg_replace('/[^a-z0-9\s]/', '', $siswaJawab));

        $cleanKunci = trim((string) preg_replace('/\s+/', ' ', $cleanKunci));
        $cleanJawab = trim((string) preg_replace('/\s+/', ' ', $cleanJawab));

        if ($cleanKunci === '' || $cleanJawab === '') {
            return 0.0;
        }

        similar_text($cleanKunci, $cleanJawab, $fullPercent);
        if ($fullPercent >= 80) {
            return 1.0;
        }

        $kunciWords = array_values(array_unique(array_filter(explode(' ', $cleanKunci))));
        $jawabWords = array_values(array_unique(array_filter(explode(' ', $cleanJawab))));

        if (empty($kunciWords) || empty($jawabWords)) {
            return 0.0;
        }

        $matchCount = 0;
        foreach ($kunciWords as $kataKunci) {
            foreach ($jawabWords as $kataJawab) {
                if ($kataKunci === $kataJawab) {
                    $matchCount++;
                    continue 2;
                }

                if (str_contains($kataJawab, $kataKunci) || str_contains($kataKunci, $kataJawab)) {
                    $matchCount++;
                    continue 2;
                }

                $distance = levenshtein($kataKunci, $kataJawab);
                $maksTypo = strlen($kataKunci) >= 6 ? 2 : 1;
                if ($distance <= $maksTypo) {
                    $matchCount++;
                    continue 2;
                }
            }
        }

        $matchRatio = $matchCount / max(count($kunciWords), count($jawabWords));
        if ($matchRatio < 0.6) {
            return 0.0;
        }

        return min(1.0, $matchRatio);
    }

    protected function decodeMatchingAnswer($answer): array
    {
        if (is_array($answer)) {
            return $answer;
        }

        if (is_string($answer) && trim($answer) !== '') {
            $decoded = json_decode($answer, true);
            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }
}
