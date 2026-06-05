<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelas extends Model
{
    use HasFactory;
    
    protected $table = 'classes';
    protected $guarded = [];

    public function siswas()
    {
        return $this->hasMany(Siswa::class, 'class_id');
    }

    public function curriculums()
    {
        return $this->hasMany(Curriculum::class, 'kelas_id');
    }
}
