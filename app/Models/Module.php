<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas_id',
        'subject_id',
        'sensei_id',
        'judul',
        'deskripsi',
        'file_path',
        'tipe_file',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function sensei()
    {
        return $this->belongsTo(Sensei::class, 'sensei_id');
    }
}
