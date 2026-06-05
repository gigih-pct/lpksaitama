<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiKotoba extends Model
{
    use HasFactory;

    protected $table = 'nilai_kotobas';

    protected $fillable = [
        'evaluasi_kotoba_id', 'siswa_id', 'nilai',
    ];

    public function evaluasi()
    {
        return $this->belongsTo(EvaluasiKotoba::class, 'evaluasi_kotoba_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
