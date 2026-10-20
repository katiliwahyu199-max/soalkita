<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanSiswa extends Model
{
    use HasFactory;

    protected $table = 'jawaban_siswa';

    protected $fillable = [
        'terbitan_tugas_id',
        'student_name',
        'total_waktu_menit',
        'total_soal',
        'jawaban_json', 
        'focus_loss_count',
        'last_focus_loss_at',
        'status_penilaian',
        'nilai_otomatis',
        'nilai_uraian',
    ];

    protected $casts = [
        'jawaban_json' => 'array',
        'last_focus_loss_at' => 'datetime',
        'focus_loss_count' => 'integer',
    ];

    public function terbitanTugas()
    {
        return $this->belongsTo(TerbitanTugas::class);
    }
}