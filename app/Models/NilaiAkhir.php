<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiAkhir extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluasi_id',
        'siswa_id',
        'nilai_data',
        'catatan'
    ];

    protected $casts = [
        'nilai_data' => 'array',
    ];

    public function evaluasi()
    {
        return $this->belongsTo(EvaluasiNilaiAkhir::class, 'evaluasi_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}
