<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    protected $fillable = [
        'kelas_id',
        'sensei_id',
        'judul',
        'deskripsi',
        'urutan',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function sensei()
    {
        return $this->belongsTo(Sensei::class);
    }
}
