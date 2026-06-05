<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskSubmission extends Model
{
    protected $fillable = ['siswa_id', 'judul_tugas', 'file_path', 'file_type', 'nilai'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
