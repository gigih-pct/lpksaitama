<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiBunpou extends Model
{
    use HasFactory;

    protected $table = 'nilai_bunpous';

    protected $fillable = [
        'evaluasi_bunpou_id', 'siswa_id', 'nilai',
    ];

    public function evaluasi()
    {
        return $this->belongsTo(EvaluasiBunpou::class, 'evaluasi_bunpou_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
