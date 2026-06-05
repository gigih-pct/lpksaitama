<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiKanji extends Model
{
    use HasFactory;

    protected $table = 'nilai_kanjis';

    protected $fillable = [
        'evaluasi_kanji_id', 'siswa_id', 'nilai',
    ];

    public function evaluasi()
    {
        return $this->belongsTo(EvaluasiKanji::class, 'evaluasi_kanji_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
