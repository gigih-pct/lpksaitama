<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluasiFmd extends Model
{
    use HasFactory;

    protected $table = 'evaluasi_fmds';

    protected $fillable = [
        'kelas_id', 'sensei_id', 'kategori', 'judul', 'tanggal', 'kkm',
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
        return $this->hasMany(NilaiFmd::class, 'evaluasi_fmd_id');
    }
}
