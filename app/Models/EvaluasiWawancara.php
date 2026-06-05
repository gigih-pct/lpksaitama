<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluasiWawancara extends Model
{
    use HasFactory;

    protected $table = 'evaluasi_wawancaras';

    protected $fillable = [
        'kelas_id', 'sensei_id', 'kategori', 'judul', 'tanggal', 'kkm_materi', 'kkm_sikap',
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
        return $this->hasMany(NilaiWawancara::class, 'eval_wawancara_id');
    }
}
