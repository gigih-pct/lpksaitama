<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluasiKanji extends Model
{
    use HasFactory;

    protected $table = 'evaluasi_kanjis';

    protected $fillable = [
        'kelas_id', 'sensei_id', 'kategori', 'judul', 'tanggal', 'kkm', '',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function sensei()
    {
        return $this->belongsTo(Sensei::class);
    }

    public function nilais()
    {
        return $this->hasMany(NilaiKanji::class, 'evaluasi_kanji_id');
    }
}
