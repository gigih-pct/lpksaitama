<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluasiNilaiAkhir extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas_id',
        'sensei_id',
        'judul',
        'tanggal',
        'kriteria_kolom'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'kriteria_kolom' => 'array',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function sensei()
    {
        return $this->belongsTo(Sensei::class, 'sensei_id');
    }

    public function nilais()
    {
        return $this->hasMany(NilaiAkhir::class, 'evaluasi_id');
    }
}
