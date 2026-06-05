<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluasiBunpou extends Model
{
    use HasFactory;

    protected $table = 'evaluasi_bunpous';

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
        return $this->hasMany(NilaiBunpou::class, 'evaluasi_bunpou_id');
    }
}
