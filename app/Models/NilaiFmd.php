<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiFmd extends Model
{
    use HasFactory;

    protected $table = 'nilai_fmds';

    protected $fillable = [
        'evaluasi_fmd_id', 'siswa_id',
        'skor_mtk', 'skor_lari', 'skor_push_up', 'skor_sit_up', 'ket'
    ];

    public function evaluasi()
    {
        return $this->belongsTo(EvaluasiFmd::class, 'evaluasi_fmd_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
