<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiWawancara extends Model
{
    use HasFactory;

    protected $table = 'nilai_wawancaras';

    protected $fillable = [
        'eval_wawancara_id', 'siswa_id',
        'materi_program', 'materi_umum', 'materi_jepang', 'materi_indonesia',
        'sikap_cara_duduk', 'sikap_suara', 'sikap_fokus', 'ket', 'catatan'
    ];

    public function evaluasi()
    {
        return $this->belongsTo(EvaluasiWawancara::class, 'eval_wawancara_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
